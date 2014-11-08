<?php

/**
 * Контроллер взаимодействия между пользователями. Сюда входят действия
 * предложения, игнорирования, иключения из чёрного списка
 */
class OfferController extends JAppController
{       
        /**
         * Действие принятия пользователем предложения
         */
        public function actionAccept()
        {
                if(!isset($_POST['oid']) || !isset($_POST['type']) || !isset($_POST['place']))
                        throw new CHttpException('404', 'Страница не существует');
                
                $oid = $_POST['oid'];
                $type = $_POST['type'];
                $place = $_POST['place'];
                
                // проеверяем параметры на допустимость
                if(!in_array($type, array('number','dialog','message')) || !in_array($place, array('compact', 'dialog', 'messages')))
                        throw new CHttpException('404', 'Страница не существует');
                
                $offer = Offer::model()->findByPk($oid);
                       
                if($_POST['type'] == 'number')
                {
                        if(!isset($_POST['digits']))
                        {
                                echo CJSON::encode(array(
                                        'status' => 'error',
                                        'digitsError' => true,
                                ));
                                Yii::app()->end();
                        }
                        $digitsForm = new PhoneLastdigitsForm();
                        $digitsForm->digits = $_POST['digits'];
                        
                        if(!$digitsForm->validate())
                        {
                                echo CJSON::encode(array(
                                        'status' => 'error',
                                        'digitsError' => true,
                                ));
                                Yii::app()->end();
                        }     
                }
                           
                // пытаемся подтвердить предложение
                if(Offer::accept($oid))
                {                    
                        // помечаем сообщения как прочитанные
                        Metamessage::markAsRead($offer->interlocutor->id);
                        
                        // формируем сообщение в зависимости от типа действия после подтверждения предложения
                        switch($type)
                        {
                                case 'number' :
                                        $messageText = $offer->interlocutor->name.', ';
                                        $messageText .= Message::predefined('num_agree', Yii::app()->user->getGender(), $offer->interlocutor->id_gender);
                                        $messageText .= Yii::app()->format->formatPhone(Yii::app()->user->getPhone());
                                        break;
                                case 'message' :
                                        $messageText = $offer->interlocutor->name.', ';
                                        $messageText .= Message::predefined('msg_agree', Yii::app()->user->getGender(), $offer->interlocutor->id_gender);
                                        break;
                                case 'dialog' : 
                                        $messageText = $offer->interlocutor->name.', ';
                                        $messageText .= Message::predefined('dlg_agree', Yii::app()->user->getGender(), $offer->interlocutor->id_gender);
                                        break;
                                default:
                                        break;
                        }

                        // добавляем сообщение в базу
                        if(Message::add(Yii::app()->user->id, $offer->interlocutor->id, $offer->id, $messageText))
                                $result = true;                        
                        
                        $output = array();
                        
                        // если предложение подтвержденео успешно, то отдаём клиенту
                        // представление в зависимости от типа 
                        if($result)
                        {
                                $offer = Offer::model()->findByPk($oid);
                                $output['status'] = 'success';
                                
                                switch($place)
                                {
                                        case 'compact' :                                  
                                                $output['html'] = $this->renderPartial('theme.views.app.message._offer_compact', array(
                                                        'offer' => $offer,
                                                ), true);
                                                break;
                                        case 'dialog' :
                                                break;
                                        case 'messages' :
                                                $output['html'] = $this->renderPartial('theme.views.app.profile._right_messages', array(
                                                        'offer' => $offer,
                                                        'profileType' => 'messages',
                                                ), true);
                                        default:
                                                break;
                                }
                        }
                        else
                        {
                                $output['status'] = 'error';
                        }
                        
                        echo CJSON::encode($output);
                }
        }
        
        /**
         * Действие отправки предложения
         */
        public function actionRequest()
        {
                if(!isset($_POST['uid']) || !isset($_POST['mid']) || !isset($_POST['place']))
                        throw new CHttpException('404', 'Страница не найдена');
                
                $id = $_POST['uid']; // ID объекта предложения
                $mid = $_POST['mid']; // ID способа знакомства
                $place = $_POST['place']; // место на сайте, откуда вызвано действие
                $userName = $_POST['username'];
            
                // проверяем ID способа знакомства на существование
                if(!in_array($mid, JMeetmethod::getIds()))
                        throw new CHttpException('404', 'Способ знакомства не существует');            
            
                $sender = Yii::app()->user->id;
                $reciever = $id;
                
                if($sender == $reciever)
                        throw new CHttpException('404', 'Нельзя предлагать самому себе');
                
                // нельзя дружить пользователям из чёрного списка
                if(Blacklist::getBlacklistStatus($sender, $reciever) > Blacklist::STATUS_NO)
                        throw new CHttpException('404', 'Пользователи в чёрном списке!');
                
                // на основе имени получателя строим текст сообщения
                $recieverData = Yii::app()->db->createCommand()
                        ->select('name, birthday, phone')
                        ->from('user')
                        ->where('id = :reciever', array('reciever' => $reciever))
                        ->queryRow();
                
                if(!$recieverData['name'])
                        throw new CHttpException('404', 'Страница не существует');
                      
                $message = $recieverData['name'].', '.Message::predefined($mid);
                                      
                $result = array();
                
                // добавляем предложение в таблицу и шлём сообщение
                if(Offer::addUsersToOfferList($sender, $reciever, $mid))
                {
                        User::updateOfferCounter();
                    
                        // помечаем предыдущие сообщения как прочитанные
                        Metamessage::markAsRead($reciever);                  
                        // добавляем сообщение о предложении
                        Message::add($sender, $reciever, Offer::getOfferId($sender, $reciever), $message);
                        
                        $result['status'] = 'success';
                }
                else
                {
                        $result['status'] = 'error';
                        echo CJSON::encode($result);
                        Yii::app()->end();
                }
                
                
                // выбираем, что отдать клиенту
                switch($place)
                {
                        case 'profile' :
                                $offerData = Offer::getOfferData($sender, $reciever);
                            
                                $result['html'] = $this->renderPartial('theme.views.app.profile._right_offered', array(
                                        'method' => JMeetmethod::getItem($mid),
                                        'userID' => $id,
                                        'offerData' => Offer::getOfferData($reciever, $sender),
                                ), true);
                                $result['message'] = 'Смс-уведомление о вашем предложении <br />
                                        <span class="color-blue">' . JMeetmethod::getDescription($mid) . '</span> <br />
                                        успешно отправленно пользователю <span class="color-blue">' . CHtml::encode($userName) . '</span>';
                                        
                                $offerID = $offerData['id'];
                                break; 
                        case 'compact' :
                                $offer = Offer::model()->find('(id_sender = :id_sender AND id_reciever = :id_reciever) OR (id_sender = :id_reciever AND id_reciever = :id_sender)', array(
                                        'id_sender' => $sender,
                                        'id_reciever' => $reciever
                                ));
                                $result['html'] = $this->renderPartial('theme.views.app.message._offer_compact', array(
                                        'offer' => $offer,
                                ), true);
                                
                                $offerID = $offer->id;
                                break;
                        case 'messages':
                                $offer = Offer::model()->find('(id_sender = :id_sender AND id_reciever = :id_reciever) OR (id_sender = :id_reciever AND id_reciever = :id_sender)', array(
                                        'id_sender' => $sender,
                                        'id_reciever' => $reciever
                                ));
                                $result['html'] = $this->renderPartial('theme.views.app.profile._right_messages', array(
                                        'offer' => $offer,
                                        'profileType' => 'messages',
                                ), true);
                                $offerID = $offer->id;
                                break;
                        case 'dialog':
                                break;
                        default: break;
                }
                
                // в зависимости от того, хватает ли денег пользователю, отдаём на клиент ссылку
                // либо на форму с кнопкой уведомления, либо на форму оплаты уведомления по смс
                $isEnoughMoney = Yii::app()->user->getAccount() >= JPayment::COST_OFFERNOTICE;
                $result['notice_available'] = JSMS::checkSendingAvailability($reciever, JSMS::TYPE_OFFERNOTICE);
                
                if($reciever == User::BOSS)
                        $result['notice_available'] = false;
                
                $result['notice_url'] = $isEnoughMoney
                        ? $this->createUrl('offer/loadnoticeform', array('id' => $offerID))
                        : $this->createUrl('payment/sms', array('op' => JPayment::OPERATION_OFFERNOTICE));
                $result['id_user'] = $reciever;
                $result['id_offer'] = $offerID;
                $result['is_enough_money'] = $isEnoughMoney;
                
                echo CJSON::encode($result);
        }
        
        /**
         * Действие загрузки окна установки уведомления о предложении
         * 
         * @param integer $id ID предложения
         */
        public function actionLoadNoticeForm($id)
        {
                $offer = Offer::model()->findByPk($id);
                
                if($offer == null)
                        throw new CHttpException('404', 'Страница не существует');
            
                $this->renderPartial('_form_offernotice', array(
                    'user' => $offer->interlocutor,
                    'offerID' => $offer->id,
                    'methodID' => $offer->id_method,
                    'account' => Yii::app()->user->getAccount(),
                ), false, true);
        }
        
        /**
         * Действие оплаты уведомления со счёта пользователя
         * 
         * @param integer $id ID предложения
         */
        public function actionNotice($id)
        {
                $offer = Offer::model()->findByPk($id);
                
                if(!$offer)
                        throw new CHttpException('404', 'Страница не существует');
                
                $html = '';
                $account = Yii::app()->user->getAccount();
                
                
                if($account < JPayment::COST_OFFERNOTICE)
                        $status = 'error';

                elseif(Offer::setPaidNotice($id) && JPayment::subMoney(Yii::app()->user->id, JPayment::COST_OFFERNOTICE))
                {
                        JSMS::offerMessage($offer->interlocutor->phone, Yii::app()->user->getRealname());
                        JSMS::updateSmsLogTime($offer->interlocutor->id, JSMS::TYPE_OFFERNOTICE);
                        $status = 'success';
                        $html = $this->renderPartial('_offer_notice_success', array(
                                'offer' => $offer
                        ), true);
                        $account = JPayment::formatAmount($account - JPayment::COST_OFFERNOTICE);
                        $moneyWord = JPayment::formatMoneyWord($account - JPayment::COST_OFFERNOTICE);
                }
                else
                        $status = 'error';
                
                echo CJSON::encode(array(
                        'status' => $status,
                        'html' => $html,
                        'account' => $account,
                        'moneyWord' => $moneyWord,
                ));
                
        }
        
        /**
         * Действие игнорирования пользователя
         */
        public function actionIgnore()
        {
                if(!isset($_POST['id_user']) || !isset($_POST['place']))
                               throw new CHttpException('404','Страница не найдена');
                
                $id_user = $_POST['id_user']; // ID игнорируемого пользователя
                $place = $_POST['place']; // место, откуда вызвано действие игнорирования
                if(isset($_POST['spam']))
                        $spam = $_POST['spam']; // пометка о спаме
                
                // добавляем пользователя в чёрный список
                if(!Blacklist::addUserToMyBlackList($id_user))
                        throw new CHttpException('404', 'Не удалось добавить пользователя в чёрный список');
                
                // если есть пометка о спаме, записываем это в таблицу (для просмотра админом)
                if($spam == 1)
                        Spam::complaint(Yii::app()->user->id, $id_user);
                
                // выбираем представление в зависимости от типа
                switch($place)
                {
                        case 'compact' :
                                $html = $this->renderPartial('theme.views.app.message._compact_metamessages', array(
                                        'metaMessages' => Metamessage::getLastMetaMessages(Yii::app()->user->id, Yii::app()->settings->get('Pagination','compactMessages'))
                                ), true);
                                echo CJSON::encode(array(
                                        'result' => 'success',
                                        'html' => $html,
                                ));
                                break;
                        case 'dialog' : 
                                echo CJSON::encode(array('result' => 'success'));
                                break;
                        case 'messages': 
                                echo CJSON::encode(array('result' => 'success'));
                                break;
                        default:
                                break;
                }
        }
        
        /**
         * Действие загружает форму подтверждения предложения
         */
        public function actionLoadOfferForm()
        {
                $offerID = isset($_POST['id_offer']) ? $_POST['id_offer'] : 0;
                
                if(!isset($_POST['id_user']) || !isset($_POST['id_method']) || !isset($_POST['place']))
                        throw new CHttpException('404','Страница не найдена');
                
                $user  = User::model()->with(array('userpic'))->findByPk($_POST['id_user']);
                
                if($user == null || !JMeetmethod::checkID($_POST['id_method']) || !$this->checkPlace($_POST['place']))
                        throw new CHttpException('404','Страница не найдена');
                
                
                $this->renderPartial('_form_offer', array(
                        'user' => $user,
                        'meetmethod' => JMeetmethod::getItem($_POST['id_method']),
                        'place' => $_POST['place'],
                        'offerID' => $offerID,
                        'account' => Yii::app()->user->getAccount(),
                ), false, true);
        }
        
        /**
         * Действие загружает форму подтверждения игнорирования
         */
        public function actionLoadIgnoreForm()
        {
                if(!isset($_POST['id_user']) || !isset($_POST['reasons']) ||!isset($_POST['place']))
                        throw new CHttpException('404','Страница не найдена');
                
                $redirect = isset($_POST['redirect']) ? $_POST['redirect'] : 0;
                
                $user = User::id($_POST['id_user']);
                
                if(empty($user))
                        throw new CHttpException('404', 'Страница не существует');
                
                $this->renderPartial('_form_ignore', array(
                        'userID' => $_POST['id_user'],
                        'reasons' => $_POST['reasons'],
                        'redirect' => $redirect,
                        'place' => $_POST['place'],
                        'user' => $user,
                ));
        }
        
        /**
         * Действие загружает форму подтверждения игнорирования
         */
        public function actionLoadAcceptForm()
        {
                if(!isset($_POST['id_offer']) ||!isset($_POST['place']))
                        throw new CHttpException('404','Страница не найдена');
                
                $redirect = isset($_POST['redirect']) ? $_POST['redirect'] : 0;
                
                $offer = Offer::model()->findByPk($_POST['id_offer']);
                
                if($offer == null)
                    throw new CHttpException('404','Страница не найдена');
                
                $this->renderPartial('_form_accept', array(
                        'place' => $_POST['place'],
                        'offer' => $offer,
                        'lastDigitsForm' => new PhoneLastdigitsForm(),
                        'phone' => Yii::app()->format->formatPhone(Yii::app()->user->getPhone(), true)
                ));
        }
        
        /**
         * Действие загружает форму подтверждения исключения из чёрного списка
         */
        public function actionLoadWhitelistForm()
        {
                if(!isset($_POST['id_user']))
                        throw new CHttpException('404','Страница не найдена');
            
                $this->renderPartial('_form_whitelist', array(
                        'id_user' => $_POST['id_user']
                ));
        }
        
        /**
         * Действие исключает пользователя из чёрного списка,
         * обратно отдаёт блок со способами знакомства
         */
        public function actionToWhiteList()
        {
                if(!isset($_POST['id_user']))
                        throw new CHttpException('404', 'Страница не найдена 1');
                else
                        $id_user = $_POST['id_user'];
                               
                Blacklist::deleteFromMyBlackList($id_user);
            
                $user = User::id($id_user);
                                   
                $this->renderPartial('theme.views.app.profile._right_uncontacted',array(
                        'userMethods' => $user->meetmethodIds,
                        'listMethods' => JMeetmethod::getData(),
                        'userID' => $user->id,
                        'profileType' => 'search'
                ));
        }
        
        /**
         * Метод проверяет место вызова какого-либо действия на сайте. Название 
         * действия передаётся со страницы
         * 
         * @param string $place место на сайте
         * @return boolean результат проверки
         */
        private function checkPlace($place)
        {
                return in_array($place, array('compact', 'messages', 'profile', 'dialog'));
        }
        
        /**
         * Метод проверяет действие при подтверждении предложения
         * (отправить свой номер, предложить написать первым, перейти к диалогу)
         * 
         * @param string $type действие
         * @return boolean результат проверки
         */
        private function checkAcceptType($type)
        {
                return in_array($type, array('number', 'message', 'dialog'));
        }
        
        /**
         * Действие вывода формы предложения после подарка
         * 
         * @param integer $uid ID пользователя
         */
        public function actionOfferMethods()
        {
                if(!isset($_POST['place']) && !$this->checkPlace($_POST['place']))
                        throw new CHttpException('404', 'Страница не существует');
                else
                        $place = $_POST['place'];
                
                if(!isset($_POST['id_method']))
                        $currentMethod = false;
                else
                        $currentMethod = $_POST['id_method'];
                        
                $user = User::model()->with(array('userpic'))->findByPk($_POST['id_user']);
                
                if($user === null)
                        throw new CHttpException('404', 'Страница не существует');
                
                
                $offerID = Offer::getOfferId(Yii::app()->user->id, $_POST['id_user']);
                                      
                $this->renderPartial('_offer_methods', array(
                        'user' => $user,
                        'place' => $place,
                        'currentMethod' => $currentMethod,
                        'offerID' => $offerID,
                ));
        }
}
?>
