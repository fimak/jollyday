<?php
/**
 *  Класс для генерации случайных последовательностей
 */
class JRandom
{
        const LENGTH_SALT = 3; 
        const LENGTH_SMS = 6;
        const LENGTH_PASSWORD = 8;

        /**
         * Генерация соли
         * 
         * @return string Сгенерированная соль
         */
        public static function salt()
        {
                $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $length = self::LENGTH_SALT;
                $string = '';

                for ($i = 0; $i < $length; $i++)
                        $string .= substr($chars, rand(1, strlen($chars)) - 1, 1);
                
                return $string;                
        }
    
        /**
         * Генерация случайного хеша md5
         * 
         * @return string Случайный хеш md5
         */
        public static function md5()
        {
                mt_srand((double)microtime()*1000000);
                return md5(time().rand(0,1024));
        }
    
        /**
         * Генерация случайного кода SMS
         * 
         * @return string Случайны код SMS
         */
        public static function smsRegister()
        {
                $chars = '1234567890';
                $length = self::LENGTH_SMS;
                $string = '';

                for ($i = 0; $i < $length; $i++)
                        $string .= substr($chars, rand(1, strlen($chars)) - 1, 1);

                return $string;                  
        }
        
        /**
         * Генерация случайного пароля
         * 
         * @return string сгенерированный пароль
         */
        public static function password()
        {
                $chars = '1234567890abcdefghijklmnopqastuvwxyz';
                $length = self::LENGTH_PASSWORD;
                $string = '';

                for ($i = 0; $i < $length; $i++)
                        $string .= substr($chars, rand(1, strlen($chars)) - 1, 1);

                return $string;              
        }
}
?>
