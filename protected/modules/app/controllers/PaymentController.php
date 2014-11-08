<?php

/**
 * Контроллер платежей
 *
 * @author hash
 */
class PaymentController extends JAppController
{
        /**
         * Действие вывода формы оплаты с помощью смс
         * 
         * @param integer $op тип операции
         */
        public function actionSms($op)
        {
                if(!in_array($op, JPayment::getOperations()))
                        throw new CHttpException('404', 'Страница не существует');
                
                $additionalData = array(
                        'id_user' => Yii::app()->user->id,
                );

                switch($op)
                {
                        case JPayment::OPERATION_OFFERNOTICE :
                                $shortNumber = JPayment::getShortNumber(JPayment::COST_OFFERNOTICE);
                                $tariffs = Yii::app()->smsOnline->getTariffList($shortNumber);
                            
                                if(!isset($_POST['id_offer']))
                                        throw new CHttpException('404', 'Страница не существует');
                                
                                $offer = Offer::model()->findByPk($_POST['id_offer']);
                                
                                if($offer == null)
                                        throw new CHttpException('404', 'Страница не существует');
                                
                                $viewData = compact('offer');
                                
                                $additionalData['id_offer'] = $offer->id;
                                $additionalData['id_reciever'] = $offer->interlocutor->id;
                                
                                break;
                        case JPayment::OPERATION_RATING :      
                                $shortNumber = JPayment::getShortNumber(JPayment::COST_RATING);
                                $tariffs = Yii::app()->smsOnline->getTariffList($shortNumber);
                                
                                // полчаем url аватарки пользователя
                                $userpicId = Yii::app()->user->getUserpicID();
                                if(!empty($userpicId))
                                {
                                        $model = Photo::model()->findByPk($userpicId);
                                        $photo = $model->mediumURL;
                                }
                                else
                                {
                                        $photo = User::getNoPic('medium');
                                }
                                
                                $viewData = compact('photo');
                                
                                break;
                        case JPayment::OPERATION_GIFT :
                                // проверка полученных данных
                                if(!isset($_POST['GiftForm']))
                                        throw new CHttpException('404', 'Страница не существует');
                                
                                // получаем данные, необходимые, для вывода на страницу
                                $gift = Gift::model()->findByPk($_POST['GiftForm']['id_gift']);
                                $user = User::getBaseInfo($_POST['GiftForm']['id_reciever']);
                                $form = $_POST['GiftForm'];
                                //и статус предложения
                                $offerStatus = Offer::isUsersInOfferList(Yii::app()->user->id, $user['id']);

                                
                                if(empty($gift) || empty($user) || empty($form))
                                        throw new CHttpException('404', 'Страница не существует');
                                $viewData = compact('gift', 'user', 'form', 'offerStatus');
                                
                                // дополнительные данные
                                $additionalData['id_sender'] = Yii::app()->user->id;
                                $additionalData['id_reciever'] = $_POST['GiftForm']['id_reciever'];
                                $additionalData['id_gift'] = $_POST['GiftForm']['id_gift'];
                                $additionalData['postcard'] = $_POST['GiftForm']['postcard'];
                                $additionalData['is_private'] = $_POST['GiftForm']['is_private'];
                                                                
                                // получение короткого номера и цен
                                $shortNumber = JPayment::getShortNumber(floor($gift->cost));
                                $tariffs = Yii::app()->smsOnline->getTariffList($shortNumber);
                                break;
                        default:
                                throw new CHttpException('404', 'Страница не существует'); 
                                break;
                }
                  
                $code = Yii::app()->smsOnline->createUniqueCode($op, Yii::app()->user->id, $additionalData);
                
                if(!$code)
                        throw new CHttpException('404', 'Страница не существует');

                $this->renderPartial('_sms', array(
                        'operation' => $op,
                        'code' => $code,
                        'tariffs' => $tariffs,
                        'shortNumber' => $shortNumber,
                        'viewData' => $viewData,
                        'operator' => JDefPrefics::getOperatorByPhone(Yii::app()->user->getPhone()),
                ), false, true);
        }
        
        /**
         * Действие вывода формы оплаты с помощью различных платёжных систем
         * 
         * @param integer $op тип операции
         */
        public function actionMerchant($op)
        {
                if(!in_array($op, JPayment::getOperations()))
                        throw new CHttpException('404', 'Страница не существует');

                $userId = Yii::app()->user->id;
                
                // формируем данные платежа
                $eshopId = Yii::app()->intellectmoney->eshopId;
                $orderId = Yii::app()->intellectmoney->getUniquePaymentId();
                $recipientCurrency = Yii::app()->intellectmoney->recipientCurrency;
                $userName = Yii::app()->user->getRealName();
                $user_email = Yii::app()->user->getEmail();
                $successUrl = Yii::app()->intellectmoney->successUrl; 
                $failUrl = Yii::app()->intellectmoney->failUrl;
                $recipientAmount = 0;
                
                
                // массив для дополнительных данных платежа
                $additionalData = array();
                
                // в зависимости от типа операции формируем разные данные для
                // вывода в форме
                switch($op)
                {
                        case JPayment::OPERATION_RATING :
                                $serviceName = "Поднятие пользователя в рейтинге (#$userId )";
                                $recipientAmount = JPayment::getRealCost(JPayment::COST_RATING);
                                
                                // полчаем url аватарки пользователя
                                $userpicId = Yii::app()->user->getUserpicID();
                                if(!empty($userpicId))
                                {
                                        $model = Photo::model()->findByPk($userpicId);
                                        $photo = $model->mediumURL;
                                }
                                else
                                {
                                        $photo = User::getNoPic('medium');
                                }
                                $viewData = compact('photo');
                                
                                break;          
                        case JPayment::OPERATION_GIFT :
                                $serviceName = "Подарок (#$userId)";
                                $additionalData = $_POST['GiftForm']; 
                                
                                // получаем данные, необходимые, для вывода на страницу
                                $gift = Gift::model()->findByPk($_POST['GiftForm']['id_gift']);
                                $user = User::getBaseInfo($_POST['GiftForm']['id_reciever']);
                                $form = $_POST['GiftForm'];                  
                                $offerStatus = Offer::isUsersInOfferList(Yii::app()->user->id, $user['id']);
                                
                                if(empty($gift) || empty($user) || empty($form))
                                        throw new CHttpException('404', 'Страница не существует');
                                $viewData = compact('gift', 'user', 'form', 'offerStatus');
                                
                                $recipientAmount = JPayment::getRealCost($gift->cost);
                                
                                break;
                        default:
                                throw new CHttpException('404', 'Страница не существует'); 
                            break;
                }
                   
                $additionalData['id_user'] = $userId;
                $additionalData['operation'] = $op;
                
                // сериализация дополнительных данных
                $userField_1 = urlencode(serialize($additionalData)); 
                
                // ставим тестовый режим, если надо
                if(Yii::app()->intellectmoney->testMode)
                        $user_email = Yii::app()->intellectmoney->testEmail;
                // подставляем маил поумолчанию если пользоваетль не указал его в настройках
                if(empty($user_email))
                        $user_email = Yii::app()->params['mail']['intellectmoney-default']['address'];
                // формируем массив из данных
                $imData = compact(
                        'eshopId',
                        'orderId',
                        'serviceName',
                        'recipientAmount',
                        'recipientCurrency',
                        'userName',
                        'user_email',
                        'successUrl',
                        'failUrl',
                        'userField_1'
                );
                
                $this->renderPartial('_merchant', array(
                        'operation' => $op,
                        'imData' => $imData,
                        'viewData' => $viewData,
                ));
        }
        
        /**
         * Действие вывода формы оплаты с помощью мобильного платежа
         * 
         * @param integer $op тип операции
         */
        public function actionMcommerceForm($op)
        {
                if(!in_array($op, JPayment::getOperations()))
                        throw new CHttpException('404', 'Страница не существует');
                
                $userId = Yii::app()->user->id;
                
                switch($op)
                {
                        case JPayment::OPERATION_RATING:                               
                                // полчаем url аватарки пользователя
                                $userpicId = Yii::app()->user->getUserpicID();
                                if(!empty($userpicId))
                                {
                                        $model = Photo::model()->findByPk($userpicId);
                                        $photo = $model->mediumURL;
                                }
                                else
                                {
                                        $photo = User::getNoPic('medium');
                                }
                                $viewData = compact('photo');
                                break;
                        case JPayment::OPERATION_GIFT:
                                if(!isset($_POST['GiftForm']['id_gift'], $_POST['GiftForm']['id_reciever']))
                                        throw new CHttpException('404', 'Страница не найдена');
                            
                                // получаем данные, необходимые, для вывода на страницу
                                $gift = Gift::model()->findByPk($_POST['GiftForm']['id_gift']);
                                $user = User::getBaseInfo($_POST['GiftForm']['id_reciever']);
                                $form = $_POST['GiftForm'];                  
                                $offerStatus = Offer::isUsersInOfferList(Yii::app()->user->id, $user['id']);
                                
                                if(empty($gift) || empty($user) || empty($form))
                                        throw new CHttpException('404', 'Страница не существует');
                                $viewData = compact('gift', 'user', 'form', 'offerStatus');
                        default:
                                break;
                }
                  
                $this->renderPartial('_mcommerce', array(
                        'operation' => $op,
                        'viewData' => $viewData,
                ), false, true);
        }
        
        /**
         * Действие вывода страницы пополнения счёта пользователя
         */
        public function actionAccount()
        {
                // откелючаем мордоленту
                $this->faceribbonEnable = false;
                  
                // получаем дату регистрации пользователя и флаг получения бонуса пользователем
                $bonusData = Yii::app()->user->getBonusData();
                             
                // получаем значение бонусного коэффициента
                if($bonusData['counter'] < 500 && $bonusData['fl_bonus_available'] == 1)
                        $bonusCoefficient = JPayment::BONUS_MULTIPLIER_FIVE_HUNDREDS;
                elseif($bonusData['counter'] > 500 && $bonusData['fl_bonus_available'] == 1)
                        $bonusCoefficient = JPayment::BONUS_MULTIPLIER_FIRST_DAY;
                else
                        $bonusCoefficient = JPayment::BONUS_MULTIPLIER_NORMAL;
                
                $this->render('account', array(
                        'bonusCoefficient' => $bonusCoefficient,
                        'account' => Yii::app()->user->getAccount(),
                ));
        }
        
        public function actionShowSuccessRating()
        {       
                if(!isset($_POST['id_user']))
                        throw new CHttpException('404', 'Страница не найдена');
               
                $user = User::model()->findByPk($_POST['id_user']);
                
                if(!$user)
                        throw new CHttpException('404', 'Страница не найдена');
                
                $this->renderPartial('theme.views.app.profile._rating_form_success', array(
                        'user' => $user,
                ));
        }

        public function actionShowSuccessGift()
        {
                if(!isset($_POST['id_user'], $_POST['id_reciever'], $_POST['id_gift'], $_POST['id_gift']))
                        throw new CHttpException('404', 'Страница не найдена');
                     
                $gift = Gift::model()->findByPk($_POST['id_gift']);
                $user = User::model()->with(array('city', 'userpic'))->findByPk($_POST['id_reciever']); 
                $offerStatus = Offer::isUsersInOfferList($_POST['id_user'], $_POST['id_reciever']);
            
                if($gift == null || $user == null)
                        throw new CHttpException('404', 'Страница не найдена');
                
                $this->renderPartial('theme.views.app.gift._success', array(
                        'gift' => $gift,
                        'postcard' => isset($_POST['postcard']) ? $_POST['postcard'] : '',
                        'user' => $user,
                        'offerStatus' => $offerStatus,
                ));
        }
        
        public function actionShowSuccessOffernotice()
        {
                if(!isset($_POST['id_offer']))
                        throw new CHttpException(404, 'Страница не существует');
            
                $offer = Offer::model()->findByPk($_POST['id_offer']);
                
                if($offer == null)
                        throw new CHttpException(404, 'Страница не существует');
            
                $this->renderPartial('theme.views.app.offer._offer_notice_success', array(
                        'offer' => $offer
                ));
        }
        
        public function actionShowSuccessAccount()
        {
                if(!isset($_POST['amount']))
                        throw new CHttpException(404, 'Страница не найдена');
                
                $user = User::model()->with(array('city', 'userpic'))->findByPk(Yii::app()->user->id);
            
                $this->renderPartial('theme.views.app.payment._payment_balance_success', array(
                        'amount' => $_POST['amount'],
                        'user' => $user,
                ));
        }

        /**
         * Метод проверяет тип операции на существование
         * 
         * @param integer $operation
         * @throws CHttpException
         */
        private function checkOperation($operation)
        {
                if(!in_array($operation, JPayment::getOperations()))
                        throw new CHttpException('404', 'Страница не существует');
        }
        
        /**
         * Действие загрузки формы оплаты определённым способом
         * 
         * @param string $method способ оплаты
         * @throws CHttpException
         */
        public function actionLoadAccountForm($method)
        {
                if(!in_array($method, $this->_getAccountPaymentMethods()))
                        throw new CHttpException('404', 'Страница не найдена');
                
                $userId = Yii::app()->user->id;
                
                switch($method)
                {
                        case 'bankCard':
                        case 'terminals':
                        case 'iBank':
                                // формируем данные формы платежа IntellectMoney
                                $eshopId = Yii::app()->intellectmoney->eshopId;
                                $orderId = Yii::app()->intellectmoney->getUniquePaymentId();
                                $recipientCurrency = Yii::app()->intellectmoney->recipientCurrency;
                                $userName = Yii::app()->user->getRealName();
                                $user_email = Yii::app()->user->getEmail();
                                $successUrl = Yii::app()->intellectmoney->successUrl; 
                                $failUrl = Yii::app()->intellectmoney->failUrl;
                                $recipientAmount = JPayment::EXCHANGE_RATE * 1;
                                $serviceName = "Пополнение баланса пользователя (#$userId )";
                                // массив для дополнительных данных платежа
                                $additionalData = array(
                                        'id_user' => $userId,
                                        'id_region' => Yii::app()->user->getRegionID(),
                                        'operation' => JPayment::OPERATION_BALANCE
                                );
                                // сериализация дополнительных данных 
                                $userField_1 = urlencode(serialize($additionalData)); 

                                // ставим тестовый режим, если надо
                                if(Yii::app()->intellectmoney->testMode)
                                        $user_email = Yii::app()->intellectmoney->testEmail;
                                // подставляем маил поумолчанию если пользоваетль не указал его в настройках
                                if(empty($user_email))
                                        $user_email = Yii::app()->params['mail']['intellectmoney-default']['address'];
                                // формируем массив из данных
                                $imData = compact(
                                        'eshopId',
                                        'orderId',
                                        'serviceName',
                                        'recipientAmount',
                                        'recipientCurrency',
                                        'userName',
                                        'user_email',
                                        'successUrl',
                                        'failUrl',
                                        'userField_1'
                                );
                                
                                switch($method)
                                {
                                        case 'bankCard':
                                                $viewFile = '_account_card';
                                                break;
                                        case 'terminals':
                                                $viewFile = '_account_terminal';
                                                break;
                                        case 'iBank':
                                                $viewFile = '_account_ibank';
                                                break;
                                }
                                 
                                $this->renderPartial($viewFile, array(
                                        'imData' => $imData,
                                ));
                                
                                break;
                        case 'mCommerce':                             
                                $this->renderPartial('_account_mcommerce', array(), false, true);
                                break;
                        default:
                                break;
                }
        }
        
        public function actionMcommerce($op)
        {                   
                if(!$_POST['anotherPhone'])
                        $phone = '7'.Yii::app ()->user->getPhone();
                else
                        $phone = preg_replace('/[^0-9]/', '', $_POST['phone']);
                
                $userId = Yii::app()->user->id;
                $html = '';
                            
                switch($op)
                {
                        case JPayment::OPERATION_BALANCE:
                                if(!isset($_POST['price']) || !is_numeric($_POST['price']))
                                        throw new CHttpException('404', 'Страница не существует');
                            
                                $additionalData = array(
                                    'id_user' => $userId,
                                );
                                $merchant_order_id = Yii::app()->aviso->generateOrder($userId, JPayment::OPERATION_BALANCE, $additionalData);
                                $description = "Пополнение баланса на jollyday.ru";
                                $price = $_POST['price'];
                                
                                
                                $money = $price / JPayment::EXCHANGE_RATE;
                                $moneyWord = Yii::t('jolly', 'moneyAccusative', (int)$money);
                                
                                $success_message = "Ваш счет на сайте jollyday.ru успешно пополнен на $money $moneyWord + бонус.";
                                                    
                                $result = Yii::app()->aviso->createOrder($description, $price, $success_message, $phone, $merchant_order_id);
                                
                                if(isset($result['status']))
                                {                                   
                                        $message = $result['status'] == 0
                                                ? 'Дальнейшие инструкции по совершению платежа придут вам на мобильный телефон'
                                                : Yii::app()->aviso->getCreateOrderStatusDescription($result['status']);
                                        $status = $result['status'] == 0 ? 'success' : 'error';
                                        
                                        if($status == 'success'){
                                            
                                                $bonus = isset($_POST['bonus']) && is_numeric(str_replace(',', '.', $_POST['bonus'])) 
                                                        ? str_replace(',', '.', $_POST['bonus']) 
                                                        : 0;                    
                                                
                                                $html = $this->renderPartial('_account_mcommerce_success', array(
                                                        'money' => Yii::app()->format->formatNumber($money),
                                                        'moneyWord' => $moneyWord,
                                                        'price' => Yii::app()->format->formatNumber($price),
                                                        'bonus' => Yii::app()->format->formatNumber($bonus),
                                                        'summ' => Yii::app()->format->formatNumber($money + $bonus),
                                                        'phone' => Yii::app()->format->formatPhone(substr($phone, 1), true, true),
                                                ), true);
                                        }
                                }
                                else
                                {
                                        $message = 'Неизвестная ошибка';
                                        $status = 'error';
                                }
                                break;
                                
                        case JPayment::OPERATION_RATING:
                                $additionalData = array(
                                    'id_user' => $userId,
                                );
                                $merchant_order_id = Yii::app()->aviso->generateOrder($userId, JPayment::OPERATION_RATING, $additionalData);
                                $description = "Поднятие в рейтинге на jollyday.ru";
                                $price = JPayment::COST_RATING * JPayment::EXCHANGE_RATE;
                                $success_message = 'Вы успешно поднялись на 1 место в рейтинге. www.jollyday.ru';
                                
                                $result = Yii::app()->aviso->createOrder($description, $price, $success_message, $phone, $merchant_order_id);
                                
                                if(isset($result['status']))
                                {
                                        if(isset($result['operator']) && $result['operator'] == 'beeline')
                                        {
                                                $result['status'] == 2;
                                                $message = 'Оплата услуг с помощью мобильной коммерции недоступна для абонентов Belline';
                                        }
                                        
                                        $message = $result['status'] == 0
                                                ? 'Дальнейшие инструкции по совершению платежа придут вам на мобильный телефон'
                                                : Yii::app()->aviso->getCreateOrderStatusDescription($result['status']);
                                        $status = $result['status'] == 0 ? 'success' : 'error';
                                }
                                else
                                {
                                        $message = 'Неизвестная ошибка';
                                        $status = 'error';
                                }
                                
                                break;
                        case JPayment::OPERATION_GIFT:
                                if(!isset($_POST['id_reciever'], $_POST['id_gift']))
                                        throw new CHttpException('404', 'Страница не нашлась');
                                
                                $postcard = isset($_POST['postcard']) ? $_POST['postcard'] : ''; 
                                $is_private = isset($_POST['is_private']) ? $_POST['is_private'] : '';
                                $reciever = User::getBaseInfo($_POST['id_reciever']);
                                $gift = Gift::model()->findByPk($_POST['id_gift']);
                                
                                if(empty($reciever) || empty($gift))
                                        throw new CHttpException('404', 'Страница не нашлась');
                                
                                $additionalData = array(
                                        'id_sender' => $userId,
                                        'id_user' => $userId,
                                        'id_reciever' => $_POST['id_reciever'],
                                        'postcard' => $postcard,
                                        'is_private' => $is_private,
                                        'id_gift' => $gift->id
                                );
                                
                                $merchant_order_id = Yii::app()->aviso->generateOrder($userId, JPayment::OPERATION_GIFT, $additionalData);
                                $description = "Подарок на на jollyday.ru";
                                $price = $gift->cost * JPayment::EXCHANGE_RATE;
                                $success_message = 'Ваш подарок успешно отправлен. www.jollyday.ru';
                                
                                $result = Yii::app()->aviso->createOrder($description, $price, $success_message, $phone, $merchant_order_id);
                                
                                if(isset($result['status']))
                                {
                                        if(isset($result['operator']) && $result['operator'] == 'beeline')
                                        {
                                                $result['status'] == 2;
                                                $message = 'Оплата услуг с помощью мобильной коммерции недоступна для абонентов Belline';
                                        }
                                        
                                        $message = $result['status'] == 0
                                                ? 'Дальнейшие инструкции по совершению платежа придут вам на мобильный телефон'
                                                : Yii::app()->aviso->getCreateOrderStatusDescription($result['status']);
                                        $status = $result['status'] == 0 ? 'success' : 'error';
                                }
                                else
                                {
                                        $message = 'Неизвестная ошибка';
                                        $status = 'error';
                                }
                                
                                break;
                        default:
                                $status = 'error';
                                $message = 'Неизвестная ошибка';
                                break;
                }
                
                echo CJSON::encode(array(
                        'status' => $status,
                        'message' => $message,
                        'html' => $html,
                ));
        }
        
        
        private function _getAccountPaymentMethods()
        {
                return array(
                        'bankCard',
                        'terminals',
                        'iBank',
                        'mCommerce',
                );
        }
}