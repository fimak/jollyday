<?php

/**
 * Виджет мордоленты
 */
class JFaceRibbon extends JWidget
{
        const MESSAGE_RATING = 0;
        const MESSAGE_DELETED = 1;
        const MESSAGE_NOPHOTO = 2;
    
        /**
         * @var boolean отображать ли мордоленту
         */
        public $enabled = true;
        
        /**
         * @var JWebUser компонент текущего пользователя
         */
        public $webUser;
        
        /**
         * @var integer количество пользователей на мордоленте. По умолчанию 12
         */
        public $pageSize = 12;
        
        /**
         * @var integer номер страницы в мордолнете. По умолчанию 1
         */
        public $page = 1;
        
        
        /**
         * @var integer ID региона пользователя
         */
        private $_regionID;
        
        /**
         * @var float счёт пользователя
         */
        private $_userAccount;
        
        /**
         * @var string рейтинговая дата
         */
        private $_userRatingDate;
        
        /**
         * @var integer ID аватарки пользователя
         */
        private $_userpicID;
        
        /**
         * @var boolean удалён ли профиль пользователя
         */
        private $_isProfileDeleted;
        
        /**
         * @var array данные для вывода в мордоленту
         */
        private $_data;
        
        /**
         * @var integer позиция текущего пользователя в рейтинге
         */
        private $_position;
        
        /**
         * @var boolean включен ли пользователь в систему рейтинга
         */
        private $_isRatingEnabled; 
        
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
                        'loadFaces' => 'JFaceRibbonPageAction',
                );
        }

        /**
         * Инициализация виджета
         */  
        public function init()
        {
                // проверка данных на корректность
                if(!($this->webUser instanceof JWebUser) || $this->webUser == null)
                        $this->enabled == false;
                
                // не получаем данные, если не надо
                if(!$this->enabled)
                        return;
                
                // получаем данные текущего пользователя
                $this->_regionID = $this->webUser->getRegionID();
                $this->_userAccount = $this->webUser->getAccount();
                $this->_userRatingDate = $this->webUser->getRatingDate();
                $this->_userpicID = $this->webUser->getUserpicID();
                $this->_isProfileDeleted = $this->webUser->isDeleted();
                
                // по умолчанию счёт ползователя равен 0
                if(empty($this->_userAccount))
                        $this->_userAccount = 0;
            
                // проверка рейтинговой даты на корректность
                if(!$this->checkTimestamp($this->_userRatingDate))
                        $this->_userRatingDate = '1700-01-01';
   
                // получаем построитель запросов
                $this->_commandBuilder = Yii::app()->getDb()->getCommandBuilder();
                               
                // строим критерий выборки пользователей
                $criteria = new CDbCriteria;
                
                // получаем пользователей в мордоленту
                $this->_data = $this->getFaceribbonData();
                         
                // получаем номер позиции в рейтинге
                $this->_position = $this->getRatingPosition();
        }
        
        /**
         * Запуск виджета
         */
        public function run()
        {
                // если мордолента отключена, то не выводим её
                if(!$this->enabled)
                        return;
            
                $viewPath = 'theme.views.widgets.jfaceribbon.faceribbon';
                
                // определяем, что показывать в левой колонке мордоленты
                if(empty($this->_userpicID))
                        $messageCode = self::MESSAGE_NOPHOTO;
                elseif($this->_isProfileDeleted)
                        $messageCode = self::MESSAGE_DELETED;
                else
                        $messageCode = self::MESSAGE_RATING;

                if(fmod($this->_userAccount , 1)==0)
                        Yii::app()->format->numberFormat['decimals']= 0;
                // отображение мордоленты
                $this->render($viewPath, array(
                        'users' => $this->_data,
                        'account' => $this->_userAccount,
                        'position' => $this->_position,
                        'messageCode' => $messageCode,
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
         * Метод получает позицию текущего пользователя в рейтинге
         * 
         * @return integer номер позиции в рейтинге
         */
        private function getRatingPosition()
        {
                $criteria = new CDbCriteria;
                
                // критерий по региону
                if(!empty($this->_regionID))
                        $criteria->compare('t.id_region', $this->_regionID);
                              
                // с аватаркой, зарегистрированный, не забаненый и не удалёный пользователь
                $criteria->addCondition('t.id_userpic IS NOT NULL');
                $criteria->compare('t.register_step', 0);
                $criteria->compare('t.role', User::ROLE_USER);
                $criteria->compare('t.fl_deleted', 0);
                
                // у кого рейтинг больше, чем у текущего пользователя
                $criteria->addCondition("t.date_rating > '$this->_userRatingDate'");
                
                $position = $this->_commandBuilder->createCountCommand('user', $criteria)->queryScalar() + 1;
                
                return $position;
                
        }
        
        /**
         * Метод получает даные пользователей в мордоленту
         * 
         * @return array данные пользователей в виде ассоциативного массива
         */
        private function getFaceribbonData()
        {
               $criteria = new CDbCriteria;
                
                // критерий по региону
                if(!empty($this->_regionID))
                        $criteria->compare('t.id_region', $this->_regionID);
                
                // с аватаркой, зарегистрированный, не забаненый и не удалёный пользователь
                $criteria->compare('t.register_step', 0);
                $criteria->compare('t.role', User::ROLE_USER);
                $criteria->compare('t.fl_deleted', 0);
                
                $criteria->select = '   
                        t.id AS id, 
                        t.name AS username,
                        t.birthday AS birthday,
                        photo.filename_faceribbon AS userpic,
                        city.name AS city,
                        profile.age_min AS agemin,
                        profile.age_max AS agemax,
                        profile.id_seeking AS seeking
                ';
                $criteria->join = 'JOIN photo ON t.id_userpic = photo.id';
                $criteria->join .= ' JOIN city ON t.id_city = city.id';
                $criteria->join .= ' JOIN profile ON profile.id_user = t.id';
                
                $this->_usersCount = $this->_commandBuilder->createCountCommand('user', $criteria)->queryScalar();
                
                // сортировка по рейтингу
                $criteria->order = 't.date_rating DESC';
                $criteria->limit = $this->pageSize;
                
                return $this->_commandBuilder->createFindCommand('user', $criteria)->queryAll();
        }
}

?>
