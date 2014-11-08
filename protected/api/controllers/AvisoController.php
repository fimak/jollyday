<?php

/**
 * Контроллер API для SMS-Online
 */
class AvisoController extends JApiController
{
        /**
         * @var JAvisoSMS компонент ависо 
         */
        public $aviso;
        
        public function init() 
        {
                parent::init();
                
                $this->aviso = Yii::app()->aviso;
        }
    
        public function actionIndex()
        {       
                // получение данных от ависо
                if(!$data = $this->aviso->getResponse())
                        $this->aviso->responseResult(0);
                else
                        extract($data);
                
                // проверка контрольной подписи
                if(!$this->aviso->checkSignature($phone, $order_status, $sign))
                        $this->aviso->responseResult(0);
                
                // полученные данных по платежу из таблицы
                if(!$orderData = $this->aviso->getOrderData($merchant_order_id))
                        $this->aviso->responseResult(0);
                
                $isSuccesfullTransaction = false;
                
                // если не оплачено, то конец связи
                if($order_status != JAvisoSms::STATUS_SUCCESS)
                        $this->aviso->responseResult(0);
                       
                $additionalData = $orderData['data'];
                
                // выбор операции
                switch($orderData['type'])
                {      
                        case JPayment::OPERATION_BALANCE :                                                     
                                // получаем дату регистрации пользователя и флаг получения бонуса пользователем
                                $bonusData = JPayment::getBonusData($additionalData['id_user']);

                                // ID региона должен быть получен от клиента
                                if(!isset($bonusData['id_region'])){
                                        $bonusData['fl_bonus_available'] = 0;
                                        $bonusData['id_region'] = 0;
                                }

                                // данные о бонусной программе также должны существовать
                                if($bonusData === array()){                                                      
                                        $bonusData['fl_bonus_available'] = 0;
                                        $bonusData['counter'] = 99999;
                                }

                                // получаем значение бонусного коэффициента
                                if($bonusData['counter'] < 500 && $bonusData['fl_bonus_available'] == 1)
                                        $bonusCoefficient = JPayment::BONUS_MULTIPLIER_FIVE_HUNDREDS;
                                elseif($bonusData['counter'] > 500 && $bonusData['fl_bonus_available'] == 1)
                                        $bonusCoefficient = JPayment::BONUS_MULTIPLIER_FIRST_DAY;
                                else
                                        $bonusCoefficient = JPayment::BONUS_MULTIPLIER_NORMAL;
                              
                                // всё отлично - начисляем деньги пользователю
                                $coin = $merchant_price / JPayment::EXCHANGE_RATE;
                                JPayment::addMoney($additionalData['id_user'], $coin, $coin * $bonusCoefficient);
                                JPayment::updateBonusCounter($bonusData['id_region']);
                                // сохраняем сумму для уведомления
                                $additionalData['amount'] = $coin * $bonusCoefficient + $coin;
                                $isSuccesfullTransaction = true;         
                                break;
                                
                        case JPayment::OPERATION_RATING:
                                if(User::setMaxRating($additionalData['id_user']))
                                        $isSuccesfullTransaction = true;
                                break;
                                
                        case JPayment::OPERATION_GIFT:
                                // через модель формы подарка делаем подарок
                                Yii::import('application.modules.app.models.form.GiftForm');
                            
                                if(!isset($additionalData['id_reciever'], $additionalData['id_gift'],$additionalData['postcard'],$additionalData['is_private'],$additionalData['id_user']))
                                        $this->aviso->responseResult(0);
                                        
                                $model = new GiftForm();        
                                $model->id_reciever=$additionalData['id_reciever'];
                                $model->id_gift= $additionalData['id_gift'];
                                $model->postcard = $additionalData['postcard'];
                                $model->is_private = $additionalData['is_private'];
                                $model->id_sender = $additionalData['id_user'];
                                
                                if($model->validate() && $model->sendGift())
                                        $isSuccesfullTransaction = true;
                                else
                                        $this->aviso->responseResult(0); 
                                
                                break;
                        default:
                                break;
                }
                
                if($isSuccesfullTransaction)
                {
                        // создание уведомления пользователя, выставление статуса "оплачено"
                        @JAlertWidget::createAlert($additionalData['id_user'], $orderData['type'], $additionalData);
                        @$this->aviso->setPaidOrderStatus($merchant_order_id, $order_id, $phone);
                        $this->aviso->responseResult(0);
                }
        }
}