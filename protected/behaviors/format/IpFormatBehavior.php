<?php

/**
 * Форматтер IP-адресов
 */
class IpFormatBehavior extends CBehavior
{
        /**
         * Метод переводит IP-адрес в целое число
         * 
         * @param string $ip IP-адрес
         * @return integer число, соответствующее IP-адресу
         */
        public function formatIP2Long($ip)
        {
                if(!$this->validateIP($ip))
                        return false;

                $data = explode('.', $ip);
                
                return $data[0]*256*256*256 + $data[1]*256*256 + $data[2]*256 + $data[3];
        }
        
        /**
         * Метод переводит целое число в IP адрес
         * 
         * @param type $long
         * @return boolean|string
         */
        public function formatLong2IP($long)
        {
                if($long < 0 || $long > 4294967295)
                        return false;
                
                $ip = "";
                
                for($i = 3; $i >= 0; $i--)
                {
                        $ip .= (int)($long / pow(256,$i));
                        $long -= (int)($long / pow(256,$i))*pow(256,$i);
                        if($i > 0)
                                $ip .= ".";
                }
                return $ip;
        }
        
        /**
         * Метод проверяет, является ли полученная строк аадресом IPv4
         * 
         * @param string $ip строка для проверки
         * @return boolean результат проверки
         */
        private function validateIP($ip)
        {
                $pattern = '/((25[0-5]|2[0-4]\d|[01]?\d\d?)\.){3}(25[0-5]|2[0-4]\d|[01]?\d\d?)/ui';          
                return preg_match($pattern, $ip);
        }
}

?>
