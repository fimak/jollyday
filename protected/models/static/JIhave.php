<?php

/**
 * Модель списка целей знакомства
 *
 */
class JIhave
{   
        /**
         * Статичные данные списка
         * 
         * @return type
         */
        protected static function data()
        {
                return array(
                        1 => 'кошка',
                        2 => 'собака',
                        3 => 'велосипед',
                        4 => 'автомобиль',
                        5 => 'мотоцикл',                 
                );
                
        }
        
        /**
         * Метод получает все id значений списка
         * 
         * @return type
         */
        public static function getIds()
        {
                return array_keys(self::data());
        }
            
        /**
         * Метод возвращает список id - значение
         * 
         * @return type массив: id - значение
         */
        public static function getList()
        {       
                return self::data();
        }
               
        /**
         * Метод возвращает текстовое описание значения списка
         * 
         * @param type $id id
         * @return type текстовое описание
         */
        public static function getDescription($id)
        {
                $data = self::data();
              
                return isset($data[$id]) ? $data[$id] : 'не указано';
        }             
}