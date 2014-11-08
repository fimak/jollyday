<?php

/**
 * Модель списка целей знакомства
 *
 */
class JWelfare
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
                        1 => 'непостоянные заработки',
                        2 => 'постоянный небольшой доход',
                        3 => 'стабильный средний доход',
                        4 => 'хорошо зарабатываю / обеспечен',                 
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
         * @param integer $id id
         * @return string текстовое описание
         */
        public static function getDescription($id)
        {
                $data = self::getList();
              
                return isset($data[$id]) ? $data[$id] : 'не указано';
        }             
}