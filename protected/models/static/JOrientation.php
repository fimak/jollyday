<?php

/**
 * Модель списка ориентаций
 *
 */
class JOrientation
{
        
        /**
         * Метод возвращает список ориентаций
         * 
         * @param mixed $homoByGender - подставлять ли гомоориентацию для м и ж
         */
        public static function data($homoByGender = false)
        {
                return array(
                        '' => 'нет ответа',
                        1 => 'гетеро',
                        2 => 'би',
                        3 => $homoByGender === false || $homoByGender == JGender::MALE ? 'гей' : 'лесби',
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
         * Метод получает текстовое описание ориентации по ID
         */
        public static function getList($homoByGender = false)
        {          
                return self::data($homoByGender);
        }
        
        public static function getDescription($id)
        {
                $data = self::getList();
              
                return isset($data[$id]) ? $data[$id] : 'не указано';
        }           
}