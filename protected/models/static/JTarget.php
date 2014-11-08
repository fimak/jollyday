<?php

/**
 * Модель списка целей знакомства
 *
 */
class JTarget
{
        // Особый пункт
        const FRIENDSHIP = 1;
    
        /**
         * Статичные данные списка
         * 
         * @return array
         */
        protected static function data()
        {
                return array(
                        1 => 'дружба и общение',
                        2 => 'провести интересно время',
                        3 => 'романтические отношения',
                        4 => 'путешествия',
                        5 => 'совместные занятия спортом',
                        6 => 'сексуальные отношения'                     
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
         * @return array массив: id - значение
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
              
                return isset($data[$id]) ? $data[$id] : 'Не указано';
        }             
}