<?php
/**
 * Модель системы защиты восстановления пароля
 * Реализована в виде синглтона
 *
 * @author office11
 */
class JRecoveryLog
{
        public static $maxSuccessAttempts = 1;
        public static $maxFailAttempts = 500;
        
        public static $format = "%Y-%m-%d %H:%i:%s";
    
        
        /**
         * Метод вставляет в таблицу попыток восстановления пароля запись с IP-адресом пользователя
         * 
         * @param string $ip ip-адрес пользователя
         */
        public static function logRecoveryAttempt($ip, $phone, $result)
        {            
                $date = Yii::app()->localtime->UTCNow;
                
                $sql = "INSERT INTO `attempt_recovery` (`ip`, `phone`, `date`, `result`) VALUES ('".$ip."', '".$phone."', '".$date."', '".$result."')";           
                Yii::app()->db->createCommand($sql)->execute();              
        }
        
        /**
         * Метод возвращает значение возможности восстановления пароля
         * 
         * @param type $ip
         * @return boolean результат (1 - можно регистрироваться, 0 - номер заблокирован на сутки)
         */
        public static function checkRecoveryAllow($ip, $phone)
        {
                $dateTo = new DateTime;
                $dateFrom = new DateTime;
                                         
                $dateFrom->sub(new DateInterval('PT48H'));
                
                $_dateFrom = $dateFrom;
                $_dateTo = $dateTo;
                
                $dateFrom = $dateFrom->format('Y-m-d H:i:s');               
                $dateTo = $dateTo->format('Y-m-d H:i:s');
            
                $sql = "
                        SELECT 
                            (SELECT COUNT(*) FROM `attempt_recovery`
                                WHERE (`ip` = '".$ip."' OR `phone` = '".$phone."') AND `result` = 1 AND `date` BETWEEN STR_TO_DATE('".$dateFrom."', '".self::$format."') AND STR_TO_DATE('".$dateTo."', '".self::$format."') LIMIT ".(self::$maxSuccessAttempts + 1).")
                                    AS `successes`,
                            (SELECT COUNT(*) FROM `attempt_recovery`
                                WHERE (`ip` = '".$ip."' OR `phone` = '".$phone."') AND `result` = 0 AND `date` BETWEEN STR_TO_DATE('".$dateFrom."', '".self::$format."') AND STR_TO_DATE('".$dateTo."', '".self::$format."') LIMIT ".(self::$maxFailAttempts + 1).")
                                    AS `fails`,
                            (SELECT `date` FROM `attempt_recovery`
                                WHERE (`ip` = '".$ip."' OR `phone` = '".$phone."') AND `date` BETWEEN STR_TO_DATE('".$dateFrom."', '".self::$format."') AND STR_TO_DATE('".$dateTo."', '".self::$format."') ORDER BY `date` DESC LIMIT 1)
                                    AS `date_last`
                ";
                                           
                $row = Yii::app()->db->createCommand($sql)->queryAll();
                
                $date_last = $row[0]['date_last'];
                $successes = $row[0]['successes'];
                $fails = $row[0]['fails'];
                
                // если не было попыток восстановления пароля за полсдениу 24 часа, то ок
                // иначе записываем дату послденей попытки
                if($date_last == null)
                        return true;
                else
                        $date_last = new DateTime ($date_last);
                
                // интервал между последней попыткой и текущим временем больше 24 часов или 
                $day_expire = $_dateFrom->diff($_dateTo)->format('%h') >= 24;
          
                // если превышено количество попыток восстановления, то не ОК
                if(($successes >= self::$maxSuccessAttempts || $fails >= self::$maxFailAttempts) && !$day_expire)
                        return false;
                

                return true;
            
        }
}

?>
