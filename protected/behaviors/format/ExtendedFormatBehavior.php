<?php

/**
 * Расширенный форматтер строк 
 */
class ExtendedFormatBehavior extends CBehavior
{   
        /**
         * Метод приводит первый символ форматируемой строки к верхнему регистру
         * 
         * @param string $string форматируемая строка
         * @return string отформатированная строка
         */
        public function formatUcfirstMb($string)
        {
                if(!is_string($string))
                        $string = false;
                else
                        $string = mb_strtoupper(mb_substr($string, 0, 1, 'UTF-8')) . mb_substr($string, 1);
                
                return $string;
        }
        
        /**
         * Метод обрезает строку в кодировке UTF-8 до указаннного количества символов
         * 
         * @param string $text форматируемая строка
         * @param integer $length длина строки после обрезания
         * @return string отформатированная строка
         */
        public function formatCrop($text, $length)
        {
                if(mb_strlen($text, 'UTF-8') > $length)
                {
                        $text = mb_substr($text, 0, $length, 'UTF-8');      
                        $text .= '…';
                }
                        
                return $text;
        }
}

?>
