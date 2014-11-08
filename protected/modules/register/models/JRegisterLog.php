<?php
/**
 * Модель системы защиты от нежелательных регистраций
 * Реализована в виде синглтона
 *
 * @author office11
 */
class JRegisterLog
{
        // Максимальное количество попыток регистрации с одного ip адреса в день
        public static $maxAttemptsPerDay = 101;
        public static $maxAttemptsPer15min = 3;
        
        public static $format = "%Y-%m-%d %H:%i:%s";
    
        
        /**
         * Метод вставляет в таблицу попыток регистрации запись с IP-адресом пользователя
         * 
         * @param string $ip ip-адрес пользователя
         */
        public static function logRegisterAttempt($ip)
        {            
                $date = Yii::app()->localtime->UTCNow;
                
                $sql = "INSERT INTO `attempt_register` (`ip`, `date`) VALUES ('".$ip."', '".$date."')";           
                Yii::app()->db->createCommand($sql)->execute();              
        }
        
        /**
         * Метод возвращает количество попыток регистраций с ip адреса за последний день
         * и за полседние 15 минут в втде массива
         * 
         * @param type $ip
         * @return type
         */
        public static function count($ip)
        {
                $dateTo = new DateTime;
                $dateFromDay = new DateTime;
                $dateFrom15Min = new DateTime;
                
                $ip = Yii::app()->request->userHostAddress;
                
                
                $dateFromDay->sub(new DateInterval('PT24H'));
                $dateFrom15Min->sub(new DateInterval('PT15M'));
                
                $dateFromDay = $dateFromDay->format('Y-m-d H:i:s');
                $dateFrom15Min = $dateFrom15Min->format('Y-m-d H:i:s');                
                $dateTo = $dateTo->format('Y-m-d H:i:s');
            
                $sql = "
                        SELECT 
                            (SELECT COUNT(*) FROM `attempt_register`
                                WHERE `ip` = '".$ip."' AND `date` BETWEEN STR_TO_DATE('".$dateFromDay."', '".self::$format."') AND STR_TO_DATE('".$dateTo."', '".self::$format."') LIMIT ".(self::$maxAttemptsPerDay + 1).")
                                    AS `per_day`,
                            (SELECT COUNT(*) FROM `attempt_register`
                                WHERE `ip` = '".$ip."' AND `date` BETWEEN STR_TO_DATE('".$dateFrom15Min."', '".self::$format."') AND STR_TO_DATE('".$dateTo."', '".self::$format."') LIMIT ".(self::$maxAttemptsPer15min + 1).")
                                    AS `per_min`
                       
                ";
                
              
                
                $row = Yii::app()->db->createCommand($sql)->queryAll();
                
                return array('per_day' => $row[0]['per_day'], 'per_min' => $row[0]['per_min']);                  
        }
}

?>
