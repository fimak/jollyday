<?php

/**
 * Класс для работы со статистикой сайта
 */

class JStat
{       
        /**
         * Метод возвращает количество зарегистрированных на сайте пользователей
         * 
         * @return integer количество зарегистрированных на сайте пользователей
         */
        public static function getUserCount()
        {
                return User::model()->count();
        }
        
        /**
         * метод возвращает общее количество предложений познакомиться
         * 
         * @return integer общее количество предложений познакомиться
         */
        public static function getOfferCount()
        {
                return Offer::model()->count();
        }
        
        /**
         * Метод возвращает общее количесвто загруженных фотографий
         * 
         * @return integer общее количесвто загруженных фотографий
         */
        public static function getPhotoCount()
        {
                return Photo::model()->count();
        }
        
        /**
         * Метод возвращает количесвто видов подарков на сайте
         * 
         * @return integer количесвто видов подарков на сайте
         */
        public static function getGiftCount()
        {
                return Gift::model()->count();
        }
        
        /**
         * Метод возвращает колчичесвто годов в базе сайта
         * 
         * @return integer колчичесвто годов в базе сайта
         */
        public static function getCityCount()
        {
                return City::model()->count();
        }
        
        /**
         * Метод возвращает количество регионов в базе сайта
         * 
         * @return integer количество регионов в базе сайта
         */
        public static function getRegionCount()
        {
                return Region::model()->count();
        }
        
        /**
         * Метод возвращает количество пользователей онлайн, исходя
         * из количества существующих сессий на сайте
         * 
         * @return integer количество пользователей онлайн
         */
        public static function getOnlineUsers()
        {
                return Yii::app()->db->createCommand('SELECT COUNT(*) FROM _action WHERE date <> 0 AND (TO_SECONDS(NOW()) - TO_SECONDS(date) < 900)')->queryScalar();
        }
        
        /**
         * Метод возвращающий массив для поля attributes виджета CDetailView,
         * описывающий распределение пользователей по регионам
         * 
         * @return array сформированный массив
         */
        public static function userByRegions()
        {
                $data = Yii::app()->db->createCommand()
                                ->select('COUNT(*) AS count, region.name AS name')
                                ->from('user')
                                ->join('region', 'user.id_region = region.id')
                                ->group('user.id_region')
                                ->order('count DESC')
                                ->queryAll();
                
                $items = array();
                
                foreach($data as $item => $value)
                {
                        $items[] = array(
                            'label' => $value['name'],
                            'value' => $value['count'],
                        );
                }   
                              
                return $items;
        }
        
        public static function getLastDigitProvider($regionId = null, $cityId = null)
        {   
                if($cityId)
                        $sql = "
                            SELECT 
                                RIGHT(`phone`, 1) AS `lastdigit`, 
                                (SELECT COUNT(*) FROM `user` WHERE RIGHT(`phone`, 1) = `lastdigit` AND user.id_city = $cityId) AS `count`
                                    FROM `user` WHERE user.role = 'user'
                                    GROUP BY `lastdigit`
                        ";
                elseif($regionId)
                        $sql = "
                            SELECT 
                                RIGHT(`phone`, 1) AS `lastdigit`, 
                                (SELECT COUNT(*) FROM `user` WHERE RIGHT(`phone`, 1) = `lastdigit` AND user.id_region = $regionId) AS `count`
                                    FROM `user` WHERE user.role = 'user'
                                    GROUP BY `lastdigit`
                        ";
                else
                        $sql = "
                            SELECT 
                                RIGHT(`phone`, 1) AS `lastdigit`, 
                                (SELECT COUNT(*) FROM `user` WHERE RIGHT(`phone`, 1) = `lastdigit`) AS `count`
                                    FROM `user` WHERE user.role = 'user'
                                    GROUP BY `lastdigit`
                        ";

                                       
                return new CSqlDataProvider($sql, array(
                        'id' => 'lastDigitProvider',
                        'keyField' => 'lastdigit',
                        'sort'=>array(
                                'attributes'=>array(
                                     'lastdigit', 'count',
                                ),
                        ),
                        'pagination' => array(  
                                'pageSize' => 25,  
                        ),  
                )); 
        }
}

?>
