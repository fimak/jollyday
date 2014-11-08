<?php
/**
 * Класс, описывающий компонент пользователя
 */
class JWebUser extends CWebUser
{
        private $_data = null;
        
        // данные для таблицы последнего действия
        public $actionTable;
        public $actionDateField;
        public $actionIdField;
        
        /**
         * Метод возвращает роль пользователя
         * 
         * @return type Роль пользователя
         */
        public function getRole()
        {
                $data = $this->getData();
                return !isset($data['role']) ? User::ROLE_GUEST : $data['role'];
        }

        /**
         * Метод возвращает данные текущего пользователя
         * 
         * @return type Роль пользователя
         */        
        private function getData()
        {
                if (!$this->isGuest && $this->_data === null)
                {
                        $this->_data = Yii::app()->db->createCommand()
                                ->select('id, role, phone, register_step, birthday, name, account, account_bonus, fl_deleted, fl_banned, id_gender, id_userpic, id_region, date_rating, date_register, email, counter_offer, timezone')
                                ->from('user')
                                ->where('id = :userID', array('userID' => $this->id))
                                ->queryRow();
                }
                return $this->_data;
        }
        
        public function hasData()
        {
                return (boolean)$this->getData();
        }
            
        /**
         * Метод возвращает телефона пользователя
         * 
         * @return type Роль пользователя
         */
        public function getPhone()
        {
                $data = $this->getData();
                return !$data ? false : $data['phone'];
        }
        
        /**
         * Метод возвращает флаг, удалена ли анкета пользователя
         */
        public function isDeleted()
        {
                $data = $this->getData();               
                return !$data ? null : $data['fl_deleted'] == 1;
        }

        /**
         * Метод возвращает флаг, удалена ли анкета пользователя
         */
        public function isBanned()
        {
                $data = $this->getData();              
                return !$this->getData() ? null : $data['fl_banned'] == 1;
        }

       /**
        * Метод возвращает шаг регистрации пользователя
        * 
        * @return integer Шаг регистрации пользователя
        */         
        public function getRegisterStep()
        {       
                $data = $this->getData();
                return isset($data['register_step']) ? $data['register_step'] : null;
        }        
        
        /**
         * Метод получает дату, влияющую на рейтинг пользователя
         * 
         * @return string рейтинговая дата
         */
        public function getRatingDate()
        {
                $data = $this->getData();
                return isset($data['date_rating']) ?  $data['date_rating'] : null;
        }
        
       /**
        * Метод возвращает имя пользователя
        * 
        * @return type Имя пользователя
        */         
        public function getRealname()
        {
                $data = $this->getData();
                return isset($data['name']) ?  $data['name'] : null;
        }
        
       /**
        * Метод возвращает email пользователя
        * 
        * @return email Имя пользователя
        */         
        public function getEmail()
        {
                $data = $this->getData();
                return isset($data['email']) ?  $data['email'] : null;
        }
              
        /**
         * Метод возвращает состояние счёта пользователя (монетки) (бонусный и простой)
         * 
         * @return type
         */
        public function getAccount()
        {
                $data = $this->getData();
                return isset($data['account'], $data['account_bonus']) ? $data['account'] + $data['account_bonus'] : 0;
        }
        
        /**
         * Метод возвращает состояние бонусного счёта пользователя (монетки)
         * 
         * @return type
         */
        public function getBonusAccount()
        {
                $data = $this->getData();
                return isset($data['account_bonus']) ? $data['account_bonus'] : 0;
        }
        
        /**
         * Метод возвращает состояние счёта пользователя (НЕ БОНУСНЫЙ, обычный)
         * 
         * @return type
         */
        public function getUsualAccount()
        {
                $data = $this->getData();
                return isset($data['account']) ? $data['account'] : 0;
        }
        
        /**
         * Метод получает пол текущего пользователя
         * 
         * @return type
         */
        public function getGender()
        {
                $data = $this->getData();
                return isset($data['id_gender']) ? $data['id_gender'] : null;
        }
        
        /**
         * Метод получает ID юзерпика текущего пользователя
         * 
         * @return type
         */
        public function getUserpicID()
        {
                $data = $this->getData();
                return isset($data['id_userpic']) ? $data['id_userpic'] : null;
        }
        
        /**
         * Метод получает ID региона пользователя
         * 
         * @return type
         */
        public function getRegionID()
        {
                $data = $this->getData();
                return isset($data['id_region']) ? $data['id_region'] : null;
        }
        
        /**
         * Метод записывает в таблицу дату соврешения последнего действия пользователя,
         * используется для отображения статуса онлайн
         */
        public function setActionDate()
        {
                if(Yii::app()->user->id)
                        Yii::app()->db->createCommand()
                                ->update($this->actionTable,
                                        array(
                                                "{$this->actionDateField}" => Yii::app()->localtime->UTCNow
                                        ),
                                        "{$this->actionIdField} = :userID",
                                        array(
                                                'userID' => (int)Yii::app()->user->id
                                        )
                        );                   
        }
        
        /**
         * Метод проверяет, является ли полученное значение ID текущего юзера
         * 
         * @param mixed $value проверяемое значение
         * @return boolean результат
         */
        public function isMyId($value)
        {
                return $value == Yii::app()->user->id;
        }
        
        /**
         * Метод выставляет временную зону пользователю по ID региона
         * 
         * @param type $regionID ID региона
         */
        public function setTimezone($regionID = null)
        {
                $data = $this->getData();
            
                if(empty($data) || empty($data['timezone']))
                        $timezone = 'Asia/Novosibirsk';
                else
                        $timezone = $data['timezone'];
                
             
                Yii::app()->localtime->setTimezone($timezone ? $timezone : Yii::app()->localtime->TimeZone);
        }
        
        /**
         * Метод чёрный список текущего пользователя в формате массива:
         * '<id пользователя>' => '<boolean: занесён в список текущим пользователем>' 
         * 
         * @return array
         */
        public function getBlackList()
        {
                return;
        }
        
        /**
         * Метод получает список пользователей, которые взаимодействуют с текущим пользователем
         * 
         * @return array
         */
        public function getOfferList()
        {
                return;
        }
        
        /**
         * Метод узнаёт, получен ли бонус пользователем
         * 
         * @return boolean
         */
        public function isBonusRecieved()
        {
                $data = $this->getData();
                
                if(!$data)
                        return null;
                
                return $data['fl_bonus_recieved'];
        }
        
        /**
         * Метод получает дату, влияющую на рейтинг пользователя
         * 
         * @return string рейтинговая дата
         */
        public function getRegisterDate()
        {
                $data = $this->getData();
                return isset($data['date_register']) ?  $data['date_register'] : '0000-00-00 00:00:00';
        }
        
        /**
         * Метод получает дату, влияющую на рейтинг пользователя
         * 
         * @return string рейтинговая дата
         */
        public function getTimezone()
        {
                $data = $this->getData();
                return isset($data['timezone']) ?  $data['timezone'] : 'Europe/Moscow';
        }
        
        /**
         * Метод получает дату, влияющую на рейтинг пользователя
         * 
         * @return string рейтинговая дата
         */
        public function getOfferCounter()
        {
                $data = $this->getData();
                return isset($data['counter_offer']) ?  $data['counter_offer'] : 0;
        }
        
       /**
         * Метод получает данные о бонусной программе пользователя
         * 
         * @param integer $userID ID пользователя
         * @return array флаг получения бонуса и дата регистрации
         */
        public function getBonusData()
        {
                $dateRegister = $this->getRegisterDate();
                $regionID = $this->getRegionID();
                $secondsInDay = 60 * 60 * 24;
                
                if(!empty($dateRegister) && !empty($regionID))
                {                                    
                        $dtNow = time();
                        $dtRegister = strtotime($dateRegister);
                        $secondsLeft = $secondsInDay - ($dtNow - $dtRegister);
                                          
                        // если разница между датой регистрации и текущей датой более одних суток
                        // или произошла ошибка при сравнеии дат, то возвращается false
                        $data['fl_bonus_available'] = $secondsLeft > 0;                
                        $data['seconds_left'] = $secondsLeft;  
                        $data['counter'] = JPayment::getBonusCounter($regionID);                
                        
                        if($data['counter'] === null || $data['counter'] === false)
                                $data['counter'] = 9999;
                }
                else
                        $data = array(
                                'fl_bonus_available' => false,
                                'seconds_left' => $secondsInDay * 365,
                                'counter' => 99999999
                        );
                
                return $data;
        }
}
?>
