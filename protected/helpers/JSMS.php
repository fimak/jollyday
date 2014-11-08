<?php

/**
 * Класс для работы с смс
 */
class JSMS
{
        const TEMPLATE_REGISTER = 'Ваш код для бесплатной регистрации на сайте www.jollyday.ru: {code}';
        const TEMPLATE_OFFERNOTICE = 'Вам пришло новое предложение от пользователя {username}';
        const TEMPLATE_RECOVERY_PASSWORD = 'Ваш новый пароль: {password}';
        const TEMPLATE_GIFT = 'Вам пришёл новый подарок от пользователя {username}';
        const TEMPLATE_NEWPHONE = 'Ваш код для смены номера мобильного телефона: {code}';

        const TYPE_REGISTER = 0;
        const TYPE_OFFERNOTICE = 1;
        const TYPE_GIFT = 2;
        const TYPE_RECOVERY = 3;
        const TYPE_NEWPHONE = 4;
      
        /**
         * Метод отправляет сообщенеие на указанный телефон
         * 
         * @param $phone телефон получателя 
         * @param $messageт екст сообщения
         * @return boolean результат обновления
         */
        public static function send($phone,$message)
        {
                Yii::app()->sms16->singleSMS($phone, $message);
        }
        
        /**
         * Метод отсылает смс с кодом для подтверждения регистрации
         * 
         * @param string $phone номер телефона пользователя (без семёрки!)
         * @param string $registerCode код регистрации
         */
        public static function registerMessage($phone, $registerCode)
        {
                $message = str_replace('{code}', $registerCode, self::TEMPLATE_REGISTER);
                $phone = '7' . $phone;
                
                return self::send($phone, $message);
        }
        
        /**
         * Метод отсылает смс с кодом для подтверждения смены номера
         * мобильного телефона
         * 
         * @param string $phone номер телефона пользователя (без семёрки!)
         * @param string $registerCode код активации
         */
        public static function newphoneMessage($phone, $newphoneCode)
        {
                $message = str_replace('{code}', $newphoneCode, self::TEMPLATE_REGISTER);
                $phone = '7' . $phone;
                
                return self::send($phone, $message);
        }
        
        /**
         * Метод отсылает уведомление о предложении
         * 
         * @param string $phone номер телефона пользователя (без семёрки!)
         * @param string $username имя отправителя сообщения
         */
        public static function offerMessage($phone, $username)
        {
                $message = str_replace('{username}', $username, self::TEMPLATE_OFFERNOTICE);
                $phone = '7' . $phone;
                
                return self::send($phone, $message);   
        }
        
        /**
         * Метод отсылает уведомление о предложении
         * 
         * @param string $phone номер телефона пользователя (без семёрки!)
         * @param string $username имя отправителя сообщения
         */
        public static function giftMessage($phone, $username)
        {
                $message = str_replace('{username}', $username, self::TEMPLATE_GIFT);
                $phone = '7' . $phone;
                         
                return self::send($phone, $message);   
        }
        
        /**
         * Метод отсылает смс с новым паролем
         * 
         * @param string $phone
         * @param type $recoveryCode
         * @return type
         */
        public static function recoveryPasswordMessage($phone, $recoveryCode)
        {
                $message = str_replace('{password}', $recoveryCode, self::TEMPLATE_RECOVERY_PASSWORD);
                $phone = '7' . $phone;
                
                return self::send($phone, $message); 
        }
          
        /**
         * Метод получает данные для проверки доступности отправки СМС пользователю
         * 
         * @param integer $userId ID пользователя
         * @return array
         */
        public static function getSendingAvailabilityData($userId)
        {
                return Yii::app()->db->createCommand()
                        ->select('user.timezone, _action.date AS date_lastaction, sms_log.date_offernotice, sms_log.date_gift')
                        ->from('user')
                        ->leftJoin('_action', '_action.id_user = user.id')
                        ->leftJoin('sms_log', 'sms_log.id_user = user.id')
                        ->where('user.id = :userId', array('userId' => $userId))
                        ->queryRow();
        }
        
        /**
         * Метод проверяет, входит ли текущее время в интервал допустимого для отправки СМС
         * времени (с 10:00 до 21:00) с учётом локального времени пользователя
         * 
         * @param string $timezone временная зона
         * @return boolean
         */
        public static function checkAllowableTimeInterval($timezone = 'UTC')
        {     
                $localNow = new DateTime('now', new DateTimeZone($timezone));
                             
                $localHours = $localNow->format("G");
                                        
                return $localHours >= 10 && $localHours < 21;
        }
        
        /**
         * Метод выставляюет текущую дату в поле даты отправки СМС-уведомления
         * определённого типа
         * 
         * @param integer $userId ID пользователя
         * @param integer $type тип СМС
         * @return boolean
         */
        public static function updateSmsLogTime($userId, $type)
        {
                switch($type)
                {
                        case self::TYPE_OFFERNOTICE :
                                $field = 'date_offernotice';
                                break;
                        case self::TYPE_GIFT :
                                $field = 'date_gift';
                                break;
                        default :
                                return false;
                }
            
                $now = Yii::app()->localtime->getUTCNow();
                $count = Yii::app()->db->createCommand()
                        ->select('COUNT(*)')
                        ->from('sms_log')
                        ->where('id_user = :userId', array('userId' => $userId))
                        ->queryScalar();
                
                if($count)          
                        return Yii::app()->db->createCommand()
                                ->update('sms_log', array($field => $now), 'id_user = :userId', array('userId' => $userId));
                else
                        return Yii::app()->db->createCommand()
                                ->insert('sms_log', array(
                                        $field => $now,
                                        'id_user' => $userId
                                ));
        }
        
        /**
         * Метод проверяет возможность получения СМС определённого типа
         * в текущий момент времени
         * 
         * @param integer $userId ID пользователя
         * @param integer $type тип сообщения
         * @return boolean
         */
        public static function checkSendingAvailability($userId, $type)
        {
                switch($type)
                {
                        case self::TYPE_OFFERNOTICE :
                                $field = 'date_offernotice';
                                $smsInterval = 60 * 60 * 2;
                                break;
                        case self::TYPE_GIFT :
                                $field = 'date_gift';
                                $smsInterval = 60 * 60 * 24;
                                break;
                        default :
                                return false;
                }
                
                $now = Yii::app()->localtime->getUTCNow();
                $data = self::getSendingAvailabilityData($userId);
                
                // заполняем значения по-умолчанию
                if(!$data['date_lastaction'])
                        $data['date_lastaction'] = '1970-01-01 00-00-00';
                if(!$data['timezone'])
                        $data['timezone'] = 'asia/novosibirsk';
                            
                // проверка на онлайн
                if(strtotime($now) - strtotime($data['date_lastaction']) < 900)
                        return false;
            
                if(!self::checkAllowableTimeInterval($data['timezone']))
                        return false;
                
                if(!$data[$field])
                        return true;
                        
                if(strtotime($data['date_lastaction']) > strtotime($data[$field]))
                        return true;
                
                if(strtotime($now) - strtotime($data[$field]) > $smsInterval)
                        return true;
                else
                        return false;
        }
}
