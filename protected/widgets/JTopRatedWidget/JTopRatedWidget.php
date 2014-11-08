<?php

/**
 * Виджет мордоленты
 */
class JTopRatedWidget extends JWidget
{   
        /**
         * @var boolean
         */
        public $enable = false;
        
        private $_users;
        private $_pages;

        public static function actions()
        {
                return array(
                        'loadProfiles' => 'JTopRatedAction'
                );
        }

        /**
         * Инициализация виджета
         */  
        public function init()
        {
                if(!$this->enable)
                        return;
                
                $users = User::getTopRated($this->controller->regionId);
                
                if(empty($users))
                        $users = User::getTopRated();
                
                if(!$users){
                    $this->_users = array();
                    return;
                }
                
                $criteria = new CDbCriteria();
                $pageSize = Yii::app()->settings->get('Pagination', 'searchResults');
                             
                $pages = new CPagination();               
                $pages->pageSize = $pageSize ;
                $pages->applyLimit($criteria);
                                    
                if(isset($users[0]))
                        $this->_users[] = $users[0];
                if(isset($users[1]))
                        $this->_users[] = $users[1];
                if(isset($users[2]))
                        $this->_users[] = $users[2];
                
                $this->_pages = $pages;
        }
        
        /**
         * Запуск виджета
         */
        public function run()
        {
                if(!$this->enable)
                        return;
                 
                $this->render('theme.views.widgets.jtopratedwidget._jtoprated', array(
                        'users' => $this->_users,
                        'pages' => $this->_pages,
                        'methodList' => JMeetmethod::getList(),
                        'controller' => Yii::app()->controller,
                ));
        }
        
        public static function getBlacklisted()
        {
                $userIds = Yii::app()->cache->get('toprated_blacklist');
                
                if($userIds === false)
                {
                        $userIds = Yii::app()->db->createCommand()
                                ->select('id_user')
                                ->from('blacklist_toprated')
                                ->queryColumn();
                        
                        Yii::app()->cache->set('toprated_blacklist', $userIds, GLOBAL_CACHE_TIME);
                }
                
                return $userIds;
        }
        
        public static function isBlacklisted($userId)
        {
                return Yii::app()->db->createCommand()
                        ->select('COUNT(*)')
                        ->from('blacklist_toprated')
                        ->where('id_user = :userId', array('userId' => $userId))
                        ->queryScalar();
        }


        public static function ban($userId)
        {
                if(!self::isBlacklisted($userId))
                        return Yii::app()->db->createCommand()
                                ->insert('blacklist_toprated', array(
                                        'id_user' => $userId
                                ));
                else
                        return true;
        }
        
        public static function unban($userId)
        {
                return Yii::app()->db->createCommand()
                        ->delete('blacklist_toprated', 'id_user = :userId', array(
                               'userId' => $userId 
                        ));
        }
}
?>
