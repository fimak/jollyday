<?php
/**
 * Класс для шоткатов
 */
class J
{
        /**
         * Шоткат для создания ссылки
         * 
         * @param mixed $route Маршрут
         * @param array $params Параметры
         * @param string $ampersand Амперсанд
         * @return string Ссылка
         */
        public static function url($route, $params = array(), $ampersand = '&')
        {
                return Yii::app()->getController()->createUrl($route, $params, $ampersand);
        }
        
        /**
         * Шоткат для определения возраста по дате рождения
         * 
         * @param string $date Строка, содержащая дату
         * @param string $format Формат строки
         */
        public static function age($date, $format = 'Y-m-d')
        {      
                $date = DateTime::createFromFormat($format, $date);
                
                return $date ? $date->diff(new DateTime)->format('%y') : '';  
        }
                   
        /**
         * Шоткат для форматирования даты в относительный вид. По типу "2 дня назад"
         * 
         * @param string $value время в формате 'Y-m-d H:i:s'
         * @return string отформатированное время
         */
        public static function ago($value)
        {           
                $localTime = Yii::app()->format->formatTimeago($value);
                                                                    
                return $localTime;    
        }
}

?>
