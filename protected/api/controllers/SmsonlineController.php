<?php

/**
 * Контроллер API для SMS-Online
 */
class SmsonlineController extends JApiController
{
        public $sms;
        
        public function filters()
        {
                return array(
                        'accessControl',
                );
        }
    
        public function accessRules() 
        {
                return array(
                        array('allow',
                                'actions' => array('index'),
                                'ips' => array(
                                        '127.0.0.1',
                                        '95.163.74.*',
                                        '89.111.54.*',
                                        '91.142.251.*',
                                        '194.67.81.*',
                                        '85.192.45.*',
                                ),
                        ),
                        array('deny',
                                'users' => array('*')
                        ),
                );
        }
        
        public function init() 
        {
                parent::init();
                
                $this->sms = Yii::app()->smsOnline;
        }
    
        public function actionIndex()
        {         
                // проверка полученных данных
                if(!$this->sms->checkRequest($_POST))
                        $this->sms->responseMessage('Не все данные получены');
                     
                // проверка контрольной подпись
                //if(!$this->sms->checkSignature($_POST))
                        //$this->sms->responseMessage('Ошибка проверки подписи');
                
                $smsCode = trim($_POST['txt']);
                
                $smsData = $this->sms->getSmsData($smsCode);
                
                if(empty($smsData))
                        $this->sms->responseMessage('Код не существует');
                          
                $userData = unserialize($smsData['data']);
                
                switch($smsData['operation'])
                {
                        case JPayment::OPERATION_GIFT: 
                                $gift = Gift::model()->findByPk($userData['id_gift']);
                                if($gift == null)
                                        $this->sms->responseMessage('Подарок не существует');
                            
                                // смс должно прийти не соответствующий номер
                                if(JPayment::getShortNumber(floor($gift->cost)) != $_POST['sn'])
                                        $this->sms->responseMessage('Неверный короткий номер');
                                
                                Yii::import('application.modules.app.models.form.GiftForm');
                                
                                // прошли все проверки - дарим подарок
                                $model = new GiftForm();
                                                   
                                $model->id_reciever=$userData['id_reciever'];
                                $model->id_gift= $userData['id_gift'];
                                $model->postcard = $userData['postcard'];
                                $model->is_private = $userData['is_private'];
                                $model->id_sender = $userData['id_sender'];
                                
                                if($model->sendGift())
                                {
                                        $this->sms->setPaidStatus($smsCode, $_POST['phone'], $_POST['tid']);
                                        JAlertWidget::createAlert($userData['id_sender'], JPayment::OPERATION_GIFT, $userData);
                                        $this->sms->responseMessage('Ваш подарок успешно отправлен. www.jollyday.ru ');
                                }
                                else
                                {
                                        $this->sms->responseMessage('Ошибка отправки подарка');
                                }
                                break;
                        case JPayment::OPERATION_RATING:
                                if(JPayment::getShortNumber(JPayment::COST_RATING) != $_POST['sn'])
                                        $this->sms->responseMessage('Неверный короткий номер');
                                
                                if(User::setMaxRating($userData['id_user']))
                                {
                                        $this->sms->setPaidStatus($smsCode, $_POST['phone'], $_POST['tid']);
                                        JAlertWidget::createAlert($userData['id_user'], JPayment::OPERATION_RATING, $userData);
                                        $this->sms->responseMessage('Вы успешно поднялись на 1 место в рейтинге. www.jollyday.ru ');
                                }
                                else
                                {
                                        $this->sms->responseMessage('Ошибка поднятия в рейтинге');
                                }
                                break;
                        case JPayment::OPERATION_OFFERNOTICE:
                                if(JPayment::getShortNumber(JPayment::COST_OFFERNOTICE) != $_POST['sn'])
                                        $this->sms->responseMessage('Неверный короткий номер');
                               
                                if(Offer::setPaidNotice($userData['id_offer']))
                                {
                                        if(!isset($userData['id_user'], $userData['id_reciever'], $userData['id_offer']))
                                                $this->sms->responseMessage('Недостаточно данных');
                                                                                            
                                        $senderData = User::getBaseInfo($userData['id_user']);                                
                                        $recieverData = User::getBaseInfo($userData['id_reciever']);
                                        
                                        if(!$senderData || !$recieverData)
                                                $this->sms->responseMessage('Отправитель не существует');
                                                               
                                        JSMS::offerMessage($recieverData['phone'], $senderData['name']);
                                        JSMS::updateSmsLogTime($userData['id_reciever'], JSMS::TYPE_OFFERNOTICE);
                                        
                                        JAlertWidget::createAlert($userData['id_user'], JPayment::OPERATION_OFFERNOTICE, $userData);
                                        $this->sms->responseMessage("СМС о Вашем предложении успешно доставлено пользователю. www.jollyday.ru ");
                                }
                                else
                                        $this->sms->responseMessage('Ошибка оплаты уведомления');
                                
                                break;
                        default:
                                $this->sms->responseMessage('Операция задана неверно');
                                break;
                }
                
                $this->sms->responseMessage('');
        }
}