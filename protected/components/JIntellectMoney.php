<?php

/**
 * Класс компонента, отвекчающего за IntellectMoney
 */
class JIntellectMoney extends CApplicationComponent
{
               
        const STATUS_OK = 5;
        const STATUS_PROCESS = 3;
        const STATUS_BLOCK = 6;
        const STATUS_CANCEL = 4;
        const STATUS_PARTIAL = 7;

        /**
         * @var integer id магазина
         */
        public $eshopId;

        /**
         * @var string Секретный ключ 
         */
        public $secretKey;

        /**
         * @var string валюта платежа
         */
        public $recipientCurrency;

         /**
         * @var string ссылка на страницу на вашем сайте, куда перейдет пользователь после успешной оплаты (ВАЖНО: не факт что проплата прошла)
         */
        public $successUrl;

        /**
         * @var string ссылка на страницу на вашем сайте, куда перейдет пользователь если откажется от платежа
         */
        public $failUrl;
        
        /**
         * @var boolean тестовый режим
         */
        public $testMode = false;

        /**
         * @var ссылка на обработчик платежей
         */
        public $server = 'https://merchant.intellectmoney.ru/ru/';
        
        /**
         * @var array массив допустимых IP-адресов для ответа от IntellectMoney
         */
        public $allowedIP = array();
        
        /**
         * @var string имя таблицы для записи информации о платеже
         */
        public $tableLog;
        
        /**
         * @var string email для тестирования
         */
        public $testEmail;


        /**
         * Инициализация компонента
         */
        public function init()
        {
                if($this->testMode)
                {
                        $this->server = 'https://merchant.intellectmoney.ru/ru/';
                        $this->recipientCurrency = 'TST';
                }
        }
        
        /**
         * Метод получает уникальный
         * 
         * @return string
         */
        public function getUniquePaymentId()
        {
                return uniqid(Yii::app()->user->id, true);
        }

       
        /**
         * Метод записывает данные в таблицу платежей
         * 
         * @return boolean результат записи
         */
        public function logPayment($request)
        {
                extract($request,EXTR_PREFIX_ALL,'in');
                
                if ($in_paymentStatus == self::STATUS_OK)
                {
                     $userField = urldecode($in_userField_1);
                     $info = unserialize($in_userField_1);
                        Yii::app()->db->createCommand()
                                ->insert($this->tableLog,array(
                                       "id_user" => $info['id_user'],
                                        "amount" =>$in_recipientAmount,		 	 	 	 	 	 		
                                        "data" => $in_userField_1,		 	 	 	 	 	 		
                                        "order_id" => $in_orderId,			 	 	 	 	 	 		
                                        "payment_id" => $in_paymentId,			 	 	 	 	 	 		
                                        "service_name" => iconv("Windows-1251","UTF-8",$in_serviceName),		 	 	 	 	 	 		
                                        "date" => Yii::app()->localtime->getUTCNow()));
                        return true;
                }
                return false;
        }
        
        /**
         * Метод проверяет контрольную подпись
         * 
         * @return boolean результат проверки
         */
        public function checkSignature($request)
        {
            extract($request,EXTR_PREFIX_ALL,'in');
           // print_r($request);
           // $keySecret = iconv("UTF-8","Windows-1251",$keySecret);
           // $in_userName = iconv("Windows-1251","",$in_userName);
           $for_hash = $in_eshopId."::".
			$in_orderId."::".
			$in_serviceName."::".
			$in_eshopAccount."::".
			$in_recipientAmount."::".
			$in_recipientCurrency."::".
			$in_paymentStatus."::".
			$in_userName."::".
			$in_userEmail."::".
			$in_paymentData."::".
			$this->secretKey;
             
                $my_hash = strtoupper(md5($for_hash));
                $hash = strtoupper($in_hash);
                return ($my_hash == $hash);
        }
        
        /**
         * Метод проверяет полученные данные
         * 
         * @return boolean результат проверки
         */
        public function checkRequest($request)
        {
               extract($request,EXTR_PREFIX_ALL,'in');
                if($in_eshopId!=$this->eshopId)
                    return false;

                return isset($in_orderId,
                        $in_serviceName,
                        $in_eshopAccount,
                        $in_recipientAmount,
                        $in_recipientCurrency,
                        $in_paymentStatus,
                        $in_userName,
                        $in_paymentData,
                        $in_userField_1);
        }
        
        /**
         * Отправка ответа серверу Intellectmoney и завершение работы приложения
         * 
         * @param string $message текст ответ. Должен быть 'OK' при успешной обработке платежа
         */
        public function endResponse($message = 'OK')
        {
                echo $message;
                Yii::app()->end();
        }
} 
