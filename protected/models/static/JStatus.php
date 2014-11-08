<?php

/**
 * Модель списка целей знакомства
 *
 */
class JStatus
{   
        /**
         * Статичные данные списка
         * 
         * @return array
         */
        protected static function data()
        {
                return array(
                        '' => 'нет ответа',
                        1 => 'нет',
                        2 => 'ничего серьезного',
                        3 => 'да',
                        4 => 'в браке',
                );
                
        }
        
        /**
         * Метод получает все id значений списка
         * 
         * @return array
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
         * @param integer $id id
         * @return string текстовое описание
         */
        public static function getDescription($id)
        {
                $data = self::getList();
              
                return isset($data[$id]) ? $data[$id] : 'не указано';
        }             
}