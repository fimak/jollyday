<?php
/**
 * статичная модель способов знакомства
 *
 * @author hash
 */
class JMeetmethod
{
        const CORRESPONDENCE = 1;
   
        const COMIX_FOLDER = 'comix';
    
        /**
         * Метод возвращает список способов знакомства.
         * Иконка способа должна быть определена в css-файле (будет возможность
         * подключать различные скины на сайт)
         * 
         * @return array массив данных
         */
        public static function data()
        {
                return array(
                        1 => array(
                                'id' => 1,
                                'description' => 'Переписка на сайте',
                                'htmlClass' => 'correspondence',
                                'accusative' => 'переписку на сайте',
                                'short' => 'Переписка',
                        ),
                        2 => array(
                                'id' => 2,
                                'description' => 'Общение по телефону',
                                'htmlClass' => 'sms',
                                'accusative' => 'общение по телефону',
                                'short' => 'Телефон',
                        ),
                        3 => array(
                                'id' => 3,
                                'description' => 'Прогулка по городу',
                                'htmlClass' => 'walking',
                                'accusative' => 'прогулку по городу',
                                'short' => 'Прогулка',
                        ),
                        4 => array(
                                'id' => 4,
                                'description' => 'Выпить кофе или чай',
                                'htmlClass' => 'coffee',
                                'accusative' => 'выпить кофе или чай',
                                'short' => 'Кафе',
                        ),
                        5 => array(
                                'id' => 5,
                                'description' => 'Сходить в кино',
                                'htmlClass' => 'cinema',
                                'accusative' => 'сходить в кино',
                                'short' => 'Кино',
                        ),
                        6 => array(
                                'id' => 6,
                                'description' => 'Прокатиться на авто',
                                'htmlClass' => 'riding',
                                'accusative' => 'прокатиться на авто',
                                'short' => 'Прокатиться',
                        ),
                        7 => array(
                                'id' => 7,
                                'description' => 'Романтический ужин в ресторане',
                                'htmlClass' => 'dinner',
                                'accusative' => 'романтический ужин в ресторане',
                                'short' => 'Ресторан',
                        ),
                        8 => array(
                                'id' => 8,
                                'description' => 'Сходить в боулинг или бильярд',
                                'htmlClass' => 'bowling',
                                'accusative' => 'сходить в боулинг или бильярд',
                                'short' => 'Боулинг',
                        ),
                        9 => array(
                                'id' => 9,
                                'description' => 'Сходить в ночной клуб',
                                'htmlClass' => 'club',
                                'accusative' => 'сходить в ночной клуб',
                                'short' => 'Клуб',
                        ),
                        10 => array(
                                'id' => 10,
                                'description' => 'Совместный шоппинг',
                                'htmlClass' => 'shopping',
                                'accusative' => 'совместный шоппинг',
                                'short' => 'Шоппинг',
                        ),
                        11 => array(
                                'id' => 11,
                                'description' => 'Покататься на роликах или коньках',
                                'htmlClass' => 'skates',
                                'accusative' => 'покататься на роликах или коньках',
                                'short' => 'Ролики/Коньки',
                        ),
                        12 => array(
                                'id' => 12,
                                'description' => 'Отдых за городом или за рубежом',
                                'htmlClass' => 'travel',
                                'accusative' => 'отдых за городом или за рубежом',
                                'short' => 'Путешествие',
                        ),
                        13 => array(
                                'id' => 13,
                                'description' => 'Исполнение любого желания',
                                'htmlClass' => 'desire',
                                'accusative' => 'исполнение любого желания',
                                'short' => 'Желание',
                        ),
                        14 => array(
                                'id' => 14,
                                'description' => 'Экстремальное приключение',
                                'htmlClass' => 'extreme',
                                'accusative' => 'экстремальное приключение',
                                'short' => 'Экстрим',
                        ),
                        15 => array(
                                'id' => 15,
                                'description' => 'Сходить в баню или сауну',
                                'htmlClass' => 'bathhouse',
                                'accusative' => 'сходить в баню или сауну',
                                'short' => 'Сауна',
                        ),
                );                              
        }
        
        /**
         * Метод получает все id значений списка
         * 
         * @return array массив id
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
                $result = array();
                
                $data = self::data();
                
                
                foreach($data as $item => $value)                            
                        $result[$item] = $value['description'];
                
                return $result;
        }
        
        public static function getShortList()
        {       
                $result = array();
                
                $data = self::data();
                
                
                foreach($data as $item => $value)                            
                        $result[$item] = $value['short'];
                
                return $result;
        }
        
        /**
         * Метод массив всех данных о способах знакомства
         * 
         * @return type массив: id - значение
         */
        public static function getData()
        {       
                $result = array();
                
                $data = self::data();
                
                foreach($data as $item => $value)
                        $result[$item] = $value;
                
                return $result;
        }
        
        /**
         * Метод возвращает текстовое описание значения списка
         * 
         * @param integer $id id
         * @return integer текстовое описание
         */
        public static function getDescription($id)
        {
                $data = self::getList();
              
                return isset($data[$id]) ? $data[$id] : 'не указано';
        }
        
        /**
         * Метод получает массив с описание способа знакомства с указанным ID
         * 
         * @param integer $id ID способа знакомства
         * @return array массив данных о способе знакомства
         */
        public static function getItem($id)
        {
                $data = self::data();
                
                return isset($data[$id]) ? $data[$id] : null;
        }
        
        /**
         * Метод получает значение HTML-класса, привязанного
         * к определённому способу знакомства
         * 
         * @param integer $id ID способа знакомства
         * @return string HTML-класс
         */
        public static function getHtmlClass($id)
        {
                $data = self::data();
                
                return isset($data[$id]) ? $data[$id]['htmlClass'] : '';
        }
        
        public static function getAccusative($id)
        {
                $data = self::data();
                
                return isset($data[$id]) ? $data[$id]['accusative'] : '';
        }
        
        /**
         * Метод проверяет, существует ли ID способа знакомства в списке
         * 
         * @param integer $id ID способа знакомства
         * @return boolean результат проверки
         */
        public static function checkID($id)
        {
                return in_array($id, self::getIds());
        }
        
        
        /**
         * Метод возвращает массив данных о изображениях, привязанных к определённому
         * способу знакомства на главной странице. Массив обрабатывается так, что 
         * на выходе мы получаем не имена файлов а относительные URL до изображений.
         * 
         * @param string $way способ знакомства (HTML-класс)
         * @return array массив путей до изображений
         * @throws CException
         */
        public static function getComixImages($way)
        {            
                $comixData = self::getComixData();
                
                $data = $comixData[$way];

                foreach($data['slide'] as $key => $value)
                        $data['slide'][$key] = Yii::app()->baseUrl.'/images/'.JMeetmethod::COMIX_FOLDER.'/'.$way.'/'.$data['slide'][$key];
                foreach($data['collage'] as $key => $value)
                        $data['collage'][$key] = Yii::app()->baseUrl.'/images/'.JMeetmethod::COMIX_FOLDER.'/'.$way.'/'.$data['collage'][$key];
                
                return $data;
        }

        /**
         * Метод возвращает массив с описанием изображений, использующихся на
         * главной странице в комиксах. К каждому способу знакомства привязаны
         * шесть слайдов для слайдера и два изображения, отображающих уменьшеные
         * копии слайдов
         * 
         * @return array массив данных
         */
        public static function getComixData()
        {
                return array(
                        'correspondence' => array(
                                'slide' => array(
                                    1 => 'correspondence_slide_1.jpg',
                                    2 => 'correspondence_slide_2.jpg',
                                    3 => 'correspondence_slide_3.jpg',
                                    4 => 'correspondence_slide_4.jpg',
                                    5 => 'correspondence_slide_5.jpg',
                                    6 => 'correspondence_slide_6.jpg'
                                ),
                                'collage' => array(
                                    1 => 'correspondence_collage_top.jpg',
                                    2 => 'correspondence_collage_bottom.jpg',
                                )
                        ),
                        'sms' => array(
                                'slide' => array(
                                    1 => 'sms_slide_1.jpg',
                                    2 => 'sms_slide_2.jpg',
                                    3 => 'sms_slide_3.jpg',
                                    4 => 'sms_slide_4.jpg',
                                    5 => 'sms_slide_5.jpg',
                                    6 => 'sms_slide_6.jpg'
                                ),
                                'all_slides' => 'sms_all.jpg'
                        ),
                        'walking' => array(
                                'slide' => array(
                                    1 => 'walking_slide_1.jpg',
                                    2 => 'walking_slide_2.jpg',
                                    3 => 'walking_slide_3.jpg',
                                    4 => 'walking_slide_4.jpg',
                                    5 => 'walking_slide_5.jpg',
                                    6 => 'walking_slide_6.jpg'
                                ),
                                'all_slides' => 'walking_all.jpg'
                        ),
                        'coffee' => array(
                                'slide' => array(
                                    1 => 'coffee_slide_1.jpg',
                                    2 => 'coffee_slide_2.jpg',
                                    3 => 'coffee_slide_3.jpg',
                                    4 => 'coffee_slide_4.jpg',
                                    5 => 'coffee_slide_5.jpg',
                                    6 => 'coffee_slide_6.jpg'
                                ),
                                'all_slides' => 'coffee_all'
                        ),
                        'cinema' => array(
                                'slide' => array(
                                    1 => 'cinema_slide_1.jpg',
                                    2 => 'cinema_slide_2.jpg',
                                    3 => 'cinema_slide_3.jpg',
                                    4 => 'cinema_slide_4.jpg',
                                    5 => 'cinema_slide_5.jpg',
                                    6 => 'cinema_slide_6.jpg'
                                ),
                                'all_slides' => 'cinema_all.jpg'
                        ),
                        'riding' => array(
                                'slide' => array(
                                    1 => 'riding_slide_1.jpg',
                                    2 => 'riding_slide_2.jpg',
                                    3 => 'riding_slide_3.jpg',
                                    4 => 'riding_slide_4.jpg',
                                    5 => 'riding_slide_5.jpg',
                                    6 => 'riding_slide_6.jpg'
                                ),
                                'all_slides' => 'riding_all.jpg'
                        ),
                        'dinner' => array(
                                'slide' => array(
                                    1 => 'dinner_slide_1.jpg',
                                    2 => 'dinner_slide_2.jpg',
                                    3 => 'dinner_slide_3.jpg',
                                    4 => 'dinner_slide_4.jpg',
                                    5 => 'dinner_slide_5.jpg',
                                    6 => 'dinner_slide_6.jpg'
                                ),
                                'all_slides' => 'dinner_all.jpg'
                        ),
                        'bowling' => array(
                                'slide' => array(
                                    1 => 'bowling_slide_1.jpg',
                                    2 => 'bowling_slide_2.jpg',
                                    3 => 'bowling_slide_3.jpg',
                                    4 => 'bowling_slide_4.jpg',
                                    5 => 'bowling_slide_5.jpg',
                                    6 => 'bowling_slide_6.jpg'
                                ),
                                'all_slides' => 'bowling_all.jpg'
                        ),
                        'club' => array(
                                'slide' => array(
                                    1 => 'club_slide_1.jpg',
                                    2 => 'club_slide_2.jpg',
                                    3 => 'club_slide_3.jpg',
                                    4 => 'club_slide_4.jpg',
                                    5 => 'club_slide_5.jpg',
                                    6 => 'club_slide_6.jpg'
                                ),
                                'all_slides' => 'club_all.jpg'
                        ),
                        'shopping' => array(
                                'slide' => array(
                                    1 => 'shopping_slide_1.jpg',
                                    2 => 'shopping_slide_2.jpg',
                                    3 => 'shopping_slide_3.jpg',
                                    4 => 'shopping_slide_4.jpg',
                                    5 => 'shopping_slide_5.jpg',
                                    6 => 'shopping_slide_6.jpg'
                                ),
                                'all_slides' => 'shopping_all.jpg'
                        ),
                        'skates' => array(
                                'slide' => array(
                                    1 => 'skates_slide_1.jpg',
                                    2 => 'skates_slide_2.jpg',
                                    3 => 'skates_slide_3.jpg',
                                    4 => 'skates_slide_4.jpg',
                                    5 => 'skates_slide_5.jpg',
                                    6 => 'skates_slide_6.jpg'
                                ),
                                'all_slides' => 'skates_all.jpg'
                        ),
                        'travel' => array(
                                'slide' => array(
                                    1 => 'travel_slide_1.jpg',
                                    2 => 'travel_slide_2.jpg',
                                    3 => 'travel_slide_3.jpg',
                                    4 => 'travel_slide_4.jpg',
                                    5 => 'travel_slide_5.jpg',
                                    6 => 'travel_slide_6.jpg'
                                ),
                                'all_slides' => 'travel_all.jpg'
                        ),
                        'desire' => array(
                                'slide' => array(
                                    1 => 'desire_slide_1.jpg',
                                    2 => 'desire_slide_2.jpg',
                                    3 => 'desire_slide_3.jpg',
                                    4 => 'desire_slide_4.jpg',
                                    5 => 'desire_slide_5.jpg',
                                    6 => 'desire_slide_6.jpg'
                                ),
                                'all_slides' => 'desire_all.jpg'
                        ),
                        'extreme' => array(
                                'slide' => array(
                                    1 => 'extreme_slide_1.jpg',
                                    2 => 'extreme_slide_2.jpg',
                                    3 => 'extreme_slide_3.jpg',
                                    4 => 'extreme_slide_4.jpg',
                                    5 => 'extreme_slide_5.jpg',
                                    6 => 'extreme_slide_6.jpg'
                                ),
                                'all_slides' => 'extreme_all.jpg'
                        ),
                        'bathhouse' => array(
                                'slide' => array(
                                    1 => 'bathhouse_slide_1.jpg',
                                    2 => 'bathhouse_slide_2.jpg',
                                    3 => 'bathhouse_slide_3.jpg',
                                    4 => 'bathhouse_slide_4.jpg',
                                    5 => 'bathhouse_slide_5.jpg',
                                    6 => 'bathhouse_slide_6.jpg'
                                ),
                                'all_slides' => 'bathhouse_all.jpg'
                        ),
                );
        }
}

?>
