<?php

/**
 * Виджет мордоленты
 */
class JMainRibbon extends JWidget
{   
        /**
         * @var integer количество пользователей на мордоленте. По умолчанию 12
         */
        public $pageSize = 15;        
        /**
         * @var integer номер страницы в мордолнете. По умолчанию 1
         */
        public $page = 1;
                  
        /**
         * @var array данные для вывода в мордоленту
         */
        private $_data;
        
        /**
         * @var CCommandBuilder построитель запросов
         */
        private $_commandBuilder;
                    
        /**
         * @var integer количество пользователей, попадающих под критерий выборки
         */
        private $_usersCount;
        
        public static function actions()
        {
                return array(
                        'loadFaces' => 'JMainRibbonAction',
                        'loadInfo'=>'JMainRibbonInfoAction'
                );
        }

        /**
         * Инициализация виджета
         */  
        public function init()
        {
                // получаем построитель запросов
                $this->_commandBuilder = Yii::app()->getDb()->getCommandBuilder();
                // получаем пользователей в мордоленту
                $this->_data = $this->getFaceribbonData();
        }
        
        /**
         * Запуск виджета
         */
        public function run()
        {      
                // отображение мордоленты
                $this->render('theme.views.widgets.jmainribbon.mainribbon', array(
                        'users' => $this->_data,
                        'page' => $this->page,
                        'pageSize' => $this->pageSize,
                        'isLastPage' => (int)($this->pageSize >= $this->_usersCount),
                ));
        }
        
        /**
         * Метод проверяет TIMESTAMP на корректность
         * 
         * @param type $timestamp
         * @return type
         */
        private function checkTimestamp($timestamp)
        {
                return date('Y-m-d H:i:s', strtotime($timestamp)) == $timestamp;
        }
        
        /**
         * Метод получает даные пользователей в мордоленту
         * 
         * @return array данные пользователей в виде ассоциативного массива
         */
        private function getFaceribbonData()
        {
                $criteria = new CDbCriteria;
                
                $criteria->compare('t.register_step', 0);
                $criteria->compare('t.role', User::ROLE_USER);
                $criteria->compare('t.fl_deleted', 0);
                
                $criteria->select = '   
                        t.id AS id, 
                        t.name AS username,
                        photo.filename_faceribbon AS userpic
                ';
                $criteria->join = 'JOIN photo ON t.id_userpic = photo.id';
                
                $this->_usersCount = $this->_commandBuilder->createCountCommand('user', $criteria)->queryScalar();
                
                // сортировка по рейтингу
                $criteria->order = 't.date_rating DESC';
                $criteria->limit = $this->pageSize;
                          
                return $this->_commandBuilder->createFindCommand('user', $criteria)->queryAll();
        
    }
}
?>
