<?php

/**
 * Поведение, содеражащее методы для форматирования номера телефона
 */

class PhoneFormatBehavior extends CBehavior
{
        /** 
         * @var string телефонный код страны 
         */
        public $countryCode = '+7';
    
        /**
         * Метод форматироует номер телефона (добавляет скобки и тире)
         * 
         * @param string $phone номер телефона
         * @return mixed отформатированный номер телефона
         */
        public function formatPhone($phone, $addCountryCode = false, $hideDigits = false)
        {
                $phone = preg_replace("[^0-9]",'',$phone); 
                
                if($hideDigits)
                        $phone = substr_replace($phone,'***',-3);
                       
                if(strlen($phone) != 10)
                        return false; 
                $area = substr($phone, 0,3); 
                $prefix = substr($phone,3,3); 
                $number = substr($phone,6,4); 
                $phone = "(".$area.") ".$prefix." - ".$number; 
                
                return $addCountryCode ? $this->countryCode.' '.$phone : $phone;
        }
}

?>
