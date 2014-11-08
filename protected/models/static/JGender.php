<?php

/**
 * Модель списка полов
 *
 */
class JGender
{
        const MALE = 0;
        const FEMALE = 1;    
    
        /**
         * Статичные данные списка
         * 
         * @return type
         */
        protected static function data()
        {
                return array(
                        self::MALE => 'парень',
                        self::FEMALE => 'девушка'
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
         * @return type массив полов: id - значение
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
                           
                return isset($data[$id]) ? $data[$id] : 'пол не указан';
        }
        
        /**
         * Метод возвращает массив полов в зависимости от падежа
         * 
         * @param string $case падеж : nominative - именительный, genitive - родительный, ablative - творительный
         * @return array массив полов
         */
        public static function getFormattedList($case = 'nominative')
        {
                if(!in_array($case, array('nominative','genitive','ablative')))
                        throw new Exception('Падеж должен принимать значения : nominative, genitive, ablative');
                
                // формируем массив
                switch($case)
                {
                        case 'nominative' :
                                $data = array(
                                        self::MALE => 'парень',
                                        self::FEMALE => 'девушка'
                                );
                                break;
                        case 'genitive' :
                                $data = array(
                                        self::MALE => 'парня',
                                        self::FEMALE => 'девушку'
                                );
                                break;
                        case 'ablative' :
                                $data = array(
                                        self::MALE => 'парнем',
                                        self::FEMALE => 'девушкой'
                                );
                                break;
                }
                                   
                return $data;
        }
        
        /**
         * Метод получает отформатированное значение пола пользователя
         * 
         * @param integer $genderID
         * @param string $case падеж : nominative - именительный, genitive - родительный, ablative - творительный
         * @return string текстовое описание пола
         */
        public static function formatGender($genderID, $case = 'nominative')
        {
                if(!in_array($case, array('nominative','genitive','ablative')))
                        throw new Exception('Падеж должен принимать значения : nominative, genitive, ablative');
            
                $data = self::getFormattedList($case);
                
                return $data[$genderID];
        }
}