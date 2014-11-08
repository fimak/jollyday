<?php

/**
 * Контроллер API для Intellectmoney
 */
class IntellectmoneyController extends JApiController
{       
        /**
         * Фильтры контроллёра
         * 
         * @return array
         */
        public function filters()
        {
                return array(
                        'accessControl',
                );
        }
        
        /**
         * Права доступа
         * 
         * @return array
         */
        public function accessRules() 
        {
                return array(
                        array('allow',
                                'actions' => array('index'),
                                'ips' => array(
                                    '127.0.1.1',
                                    '91.212.151.242'
                                ),
                        ),
                        array('deny', 
                                'users' => array('*'),
                        ),
                );
        }

        public function actionIndex()
        {       
                // подключение нужных классов
                Yii::import('application.modules.app.models.form.GiftForm');
                Yii::import('application.helpers.JMail');
                Yii::import('application.helpers.JPayment');
                Yii::import('application.extensions.yii-mail');
                $isSuccesfullTransaction = false;
                         
		if(isset($_REQUEST))
                {
                        // проверка данных от интеллектмани
			if(Yii::app()->intellectmoney->checkRequest($_REQUEST)&& Yii::app()->intellectmoney->checkSignature($_REQUEST))
                        {
                                extract($_REQUEST,EXTR_PREFIX_ALL,'in');
                                
                                // если статус оплаты отличный от "успешно", то конец связи
                                if ($in_paymentStatus == JIntellectMoney::STATUS_PROCESS){
                                        Yii::app()->intellectmoney->endResponse();
                                }
                                if ($in_paymentStatus == JIntellectMoney::STATUS_OK)
                                {
                                        // разсериализовываем дополнительные данные
                                        $additionalData = unserialize(urldecode($in_userField_1));
                                        
                                        // проверка на существование обязательных полей с ID пользователя и ID операцииs
                                        if(!isset($additionalData['id_user']) || !isset($additionalData['operation']))
                                                Yii::app()->intellectmoney->endResponse();
                                        
                                        // пользователь также должен существовать
                                        if(!User::checkID($additionalData['id_user']))
                                                Yii::app()->intellectmoney->endResponse();
                                        
                                        // количество монет, соответствующих сумме платежа
                                        $coin = $in_recipientAmount / JPayment::EXCHANGE_RATE;
                                        
                                        // выполянем действие в зависимости от типа операции
                                        switch ($additionalData['operation'])
                                        {
                                                case JPayment::OPERATION_BALANCE :                  
                                                        // получаем дату регистрации пользователя и флаг получения бонуса пользователем
                                                        $bonusData = JPayment::getBonusData($additionalData['id_user']);
                                                        
                                                        // ID региона должен быть получен от клиента
                                                        if(!isset($additionalData['id_region'])){
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
                                                        JPayment::addMoney($additionalData['id_user'], $coin, $coin * $bonusCoefficient);
                                                        JPayment::updateBonusCounter($bonusData['id_region']);
                                                        // сохраняем сумму для уведомления
                                                        $additionalData['amount'] = $coin * $bonusCoefficient + $coin;
                                                        $isSuccesfullTransaction = true;
                                                        break;
                                                case JPayment::OPERATION_RATING :
                                                        // начисляем пользователю полученную сумму
                                                        JPayment::addMoney($additionalData['id_user'], $coin);
                                                    
                                                        // проверяем баланс 
                                                        if(!User::checkBalance($additionalData['id_user'], JPayment::COST_RATING))
                                                                Yii::app()->intellectmoney->endResponse();
                                                                    
                                                        if(JPayment::subMoney($additionalData['id_user'], JPayment::COST_RATING) && User::setMaxRating($additionalData['id_user']))
                                                                $isSuccesfullTransaction = true;                           
                                                        break;
                                                case JPayment::OPERATION_GIFT :
                                                        if(!isset($additionalData['id_reciever'], $additionalData['id_gift'],$additionalData['postcard'],$additionalData['is_private'],$additionalData['id_user']))
                                                                Yii::app()->intellectmoney->endResponse('gift data error');
                                                    
                                                        $model = new GiftForm();        
                                                        $model->id_reciever=$additionalData['id_reciever'];
                                                        $model->id_gift= $additionalData['id_gift'];
                                                        $model->postcard = $additionalData['postcard'];
                                                        $model->is_private = $additionalData['is_private'];
                                                        $model->id_sender = $additionalData['id_user'];
                                                        if($model->validate())
                                                        {
                                                                $gift = Gift::model()->findByPk($model->id_gift);
                                                                
                                                                JPayment::addMoney($additionalData['id_user'], $coin);
                                                                
                                                                // проверяем баланс 
                                                                if(!User::checkBalance($additionalData['id_user'], $gift->cost))
                                                                        Yii::app()->intellectmoney->endResponse();
                                                                
                                                                if(JPayment::subMoney($model->id_sender, $gift->cost)  && $model->sendGift())
                                                                        $isSuccesfullTransaction = true;
                                                        }
                                                        break;
                                                default :
                                                        // если тип операции неверен, то завершаем работу
                                                        Yii::app()->intellectmoney->endResponse();
                                                        break;
                                        }
                                }
                                if($isSuccesfullTransaction)
                                {
                                        // TODO: вынести оповещение в отдельный скрипт
                                        @Yii::app()->intellectmoney->logPayment($_REQUEST);
                                        @JMail::intellectMoneyMail(
                                            $in_userEmail, 
                                            $in_paymentData,
                                            $in_recipientAmount,
                                            $in_serviceName,
                                            $coin,
                                            $in_orderId,
                                            $in_userName
                                        );
                                        @JAlertWidget::createAlert($additionalData['id_user'], $additionalData['operation'], $additionalData);
                                        Yii::app()->intellectmoney->endResponse();
                                }
                        }   
                                    
                }
        }
}
