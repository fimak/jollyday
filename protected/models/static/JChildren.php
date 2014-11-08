<?php

/**
 * Модель списка целей знакомства
 *
 */
class JChildren
{   
        /**
         * Статичные данные списка
         * 
         * @return type
         */
        protected static function data()
        {
                return array(
                        '' => 'нет ответа',
                        1 => 'нет',
                        2 => 'нет, но хочу',
                        3 => 'есть, живём раздельно',
                        4 => 'есть, живём вместе',
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
                $data = self::getList();
              
                return isset($data[$id]) ? $data[$id] : 'не указано';
        }             
}