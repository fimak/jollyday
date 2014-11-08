<?php

/**
 * Модель для таблицы "user".
 *
 * Столбцы таблицы 'user':
 * @property integer $id ID
 * @property integer $phone номер телефона
 * @property string $salt соль
 * @property string $password пароль
 * @property integer $role роль
 * @property integer $register_step текущий шаг регистрации
 * @property integer $name имя
 * @property string $birthday дата рождения
 * @property integer $id_region ID региона
 * @property integer $id_city ID города
 * @property integer $timezone временная зона
 * @property integer $id_gender ID пола
 * @property string $email адрес электронной почты
 * @property string $date_lastvisit дата последнего входа
 * @property string $date_register дата регистрации
 * @property string $account счёт пользователя
 */
class User extends CActiveRecord
{       
        /**
         * @var array $meetmethodIds массив ID способов знакомства, выбранных текущим пользователем
         */
        public $meetmethodIds = array();
                                    
        public $online;
        
        // роли
        const ROLE_ADMIN = 'admin';
        const ROLE_USER = 'user';
        const ROLE_GUEST = 'guest';
        
        const CACHE_PROFILE_DURATION = 3600;
        const CACHE_MEETMETHODS_DURATION = 3600;
        const CACHE_PHOTOS_DURATION = 3600;
        
        const CACHE_TOPRATED_DURATION = 300;
        
        const BOSS = 2;
          
	/**
	 * Метод возвращает статичную модель определённого AR-класса
         * 
	 * @param string $className имя класса ActiveRecord.
	 * @return User статичная модель класса
	 */ 
        public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
         * Метод возвращает имя связанной с данной моделью таблицы базы данных
         * 
	 * @return string имя таблицы
	 */
	public function tableName()
	{
		return 'user';
	}

        /**
         * Метод возвращает массив с описанием подключаемых поведений
         * 
         * @return array массив описания поведений
         */
        public function behaviors()
        {
                return array(
                        'ActiveRecordStaticInteractionBehavior' => array(
                                'class' => 'ActiveRecordStaticInteractionBehavior',
                                'foreignKey' => 'id_user'
                        )
                );
        } 
        
        /**
         * Метод, возвращающий массив с описанием правил валидации модели
         * 
	 * @return array массив правил валидации
	 */
	public function rules()
	{
		return array(
                        array('phone', 'required', 
                                'message'=> '{attribute} не может быть пустым', 
                                'on' => 'create, update'
                        ),
                        array('password' , 'required', 
                                'on' => 'create'
                        ),
                        array('phone', 'match', 
                                'pattern' => '/^[0-9]{10}$/', 
                                'message' => 'Номер телефона состоит из 10 цифр!'
                        ), 
                        array('phone', 'unique', 
                                'className' => 'User', 
                                'attributeName' => 'phone',
                                'message' => 'На номер номер телефона уже зарегистрирована анкета'
                        ),
                        array('password', 'length', 
                                'on' => 'create', 
                                'min' => 6, 
                                'max' => 16, 
                                'tooShort' => 'Пароль доллжен содержать не менее 6 символов', 
                                'tooLong' => 'Пароль доллжен содержать не более 16 символов'
                        ),
                        array('role', 'safe', 
                                'on' =>'search'
                        ),
                        array('name', 'match', 
                                'allowEmpty' => true, 
                                'pattern' => '/^[\Sa-zA-Zа-яА-ЯёЁ -]+$/iu', 
                                'message' => 'Недопустимые символы в имени'
                        ),
                        array('name', 'length', 
                                'max' => 32
                        ),
                        array('id_city', 'exist', 
                                'className' => 'City', 
                                'attributeName' => 'id',                     
                        ),
                        array('id_city', 'validateCity',
                                'message' => 'Город не соответсвует региону'
                        ),
                        array('id_region', 'exist', 
                                'className' => 'Region', 
                                'attributeName' => 'id'
                        ),
                        array('id_gender', 'in', 
                                'range' => JGender::getIds()
                        ),
                        array('email', 'email'),
                        array('email', 'unique'),
                        array('name', 'safe'),
                        array('birthday', 'date', 
                                'format' => 'yyyy-MM-dd'
                        ),
                        array('meetmethodIds', 'application.validators.JArrayElementsCountValidator', 
                                'min' => 1, 
                                'max' => 15, 
                                'on' => 'register-four, methods-update',
                                'tooFew' => 'Выберите, пожалуйста, хотя бы один способ знакомства'
                        ),

                        //правила валидации при настройках
                        array('name', 'match', 
                                'allowEmpty' => false, 
                                'pattern' => '/^[\Sa-zA-Zа-яА-ЯёЁ -]+$/iu', 
                                'message' => 'Недопустимые символы в имени', 
                                'on' => 'settings'
                        ),
                        array('birthday', 'application.validators.JAdultValidator', 
                                'minAge' => Profile::AGE_MIN, 
                                'maxAge' => Profile::AGE_MAX, 
                                'on' => 'settings'
                        ),
                        array('id, online', 'safe', 
                                'on' => 'search'
                        ),
                        array('account, account_bonus', 'numerical',
                                'on' => 'update',
                        ),
                );
	}

	/**
	 * @return array реляционные связи.
	 */
	public function relations()
	{
		return array(
                        'city' => array(self::BELONGS_TO, 'City', 'id_city'),
                        'region' => array(self::BELONGS_TO, 'Region', 'id_region'),
                        'profile'=> array(self::HAS_ONE, 'Profile', 'id_user',
                                'with' => array('iHave','meetTargets'),
                        ),
                        'userpic' => array(self::BELONGS_TO, 'Photo', 'id_userpic'),
                        'lastAction' => array(self::HAS_ONE, 'Action', 'id_user',
                                'select' => 'date'
                        ),
                );
	}

	/**
         * Метод возвращает массив настраиваемых названий
         * атрибутов модели (имя атрибута => название)
         * 
	 * @return array массив названий атрибутов
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'phone' => 'Номер телефона',
			'salt' => 'Соль',
			'password' => 'Пароль',
			'role' => 'Роль',
			'register_step' => 'Шаг регистрации',
			'name' => 'Имя',
			'birthday' => 'Дата рождения',
			'id_region' => 'Регион',
			'id_city' => 'Город',
			'timezone' => 'Временная зона',
			'id_gender' => 'Пол',
			'email' => 'Email',
			'code_sms' => 'Код SMS',
			'code_email' => 'Код активации Email',
			'date_lastvisit' => 'Дата последнего входа',
			'date_register' => 'Дата регистрации',
			'status' => 'Статус',
			'account' => 'Счёт',
                        'account_bonus' => 'Бонусный счёт',
                        'meetmethodIds' => 'Выберите интересующие вас способы знакомства',
                        'new_password' => 'Новый пароль',
                        'new_password_confirm' => 'Повторите новый пароль',
                        'old_password' => 'Старый пароль',
                        'new_phone' => 'Номер телефона',
                        'new_email' => 'Адрес электронной почты',
                        'fl_banned' => 'Забанен',
                        'fl_deleted' => 'Удалён',
		);
	}
        
        /**
         * Массив определяет параметры юзерпиков
         * 
         * @return array массив с данными о размерах фотографий пользователя
         */
        public static function userpicDimensionsList()
        {
                return array(
                        'small' => array(
                                'attribute' => 'filename_small',
                                'nopic' => '/images/nopic_small.jpg',
                                'admin' => '/images/adminpic_small.png',
                        ),
                        'medium' => array(
                                'attribute' => 'filename_medium',
                                'nopic' => '/images/nopic_medium.jpg',
                                'admin' => '/images/adminpic_medium.png',
                                'blacklist' => '/images/blacklist.jpg'
                        ),                    
                        'big' => array(
                                'attribute' => 'filename_big',
                                'nopic' => '/images/nopic_big.jpg',
                                'admin' => '/images/adminpic_big.png',
                        ),
                        'faceribbon' => array(
                                'attribute' => 'filename_faceribbon',
                                'nopic' => '/images/nopic_faceribbon.jpg',
                                'admin' => '/images/adminpic_faceribbon.png',
                        ),
                );
        }
        
        /**
         * Метод возвращает список знаков зодиака
         * 
         * @return array список знаков зодиака
         */
        public static function zodiacList()
        {
                return array(
                        1 => 'Овен',
                        2 => 'Телец',
                        3 => 'Близнецы',
                        4 => 'Рак',
                        5 => 'Лев',
                        6 => 'Дева',
                        7 => 'Весы',
                        8 => 'Скорпион',
                        9 => 'Стрелец',
                        10 => 'Козерог',
                        11 => 'Водолей',
                        12 => 'Рыбы',                    
                );
        }
         
        /**
         * Хеширование пароля
         * 
         * @param string $salt Соль
         * @param string $password Пароль
         */
        public static function hashPassword($salt, $password)
        {
                return md5($salt.$password);
        }

        /**
         * Метод загружает модель текущего пользователя
         * 
         * @return User Модель пользователя
         */
        public static function current()
        {            
                $user = User::model()->with(array('lastAction', 'city', 'region', 'userpic'))->findByPk(Yii::app()->user->id);
                
                if($user === null)
                        throw new CHttpException('404', 'Страница не найдена');
                
                return $user;
        }        
        
       /**
        * Метод загружает данные о пользователе по его ID
        * 
        * @param integer $id ID пользователя
        * @param boolean $returnArray Возвращать ли данные в виде массива, а не 
        * в виде объекта
        * @return mixed Данные пользователя
        * @throws CHttpException
        */
        public static function id($id, $returnArray = false)
        {
                if(!$returnArray)     
                {
                    $model=User::model()->with(array('lastAction', 'city', 'region', 'userpic'))->findByPk($id);
                    if($model===null)
                            throw new CHttpException(404,'Страница не существует.');
                    return $model;
                }
                else
                {
                        $row = Yii::app()->db->createCommand()
                                ->select('*')
                                ->from('user')
                                ->where('id = :id', array('id' => $id))
                                ->queryRow();
                        
                        if($row == 0 || $row == null)
                                throw new CHttpException(404,'Страница не существует.');
                        
                        return $row;
                }
        }             
        
        /**
         * Событие, происходящее перед сохранением модели
         * 
         * @return boolean результат срабатывания события
         */
        public function beforeSave()
        {          
                if($this->isNewRecord)
                {
                        $this->salt = JRandom::salt();
                        $this->password = self::hashPassword($this->salt, $this->password);
                        $this->role = self::ROLE_USER;
                        $this->register_step = 2;
                        $this->date_register = Yii::app()->localtime->getUTCNow();
                        $this->account = 0;
                        $this->account_bonus = 0;
                        $this->counter_offer = 0;
                        
                        $this->fl_banned = 0;
                        $this->fl_deleted = 0;
                                                            
                        return true; 
                }
                
                if($this->getScenario() == 'set-password')
                {
                        $this->password = self::hashPassword($this->salt, $this->password);
                        return true;
                }
                
                // сохраняем связанные данные
                $this->saveRelatedStaticData('im_user_meetmethod', 'meetmethodIds', 'id_meetmethod', Yii::app()->user->id);                
                              
                return parent::beforeSave();
        }
                     
        /**
         * Событие, происходящее после поиска с помощью find-методов модели
         */
        public function afterFind()
        {
                parent::afterFind();           
                $this->meetmethodIds = self::getMeetmethods($this->id);        
        }
   
        /**
         * Метод возвращает список ролей
         * 
         * @return array Список ролей в виде массива
         */
        public static function getRoleList()
        {
                return array(
                    self::ROLE_USER => 'Пользователь',
                    self::ROLE_ADMIN => 'Админ',
                );
        }
        
        /**
         * Метод возвращает текстовое описание роли по её id
         * 
         * @param type $roleId роль
         * @return type текстовое описание роли
         */
        public static function getRoleDescription($roleId)
        {
                $data = self::getRoleList();
                              
                return isset($data[$roleId]) ? $data[$roleId] : 'Роль не указана';
        }
               
        /**
         * Метод получает возраст пользователя по дате его рождения
         * 
         * @return string возраст юзера
         */
        public function getAge()
        {
                $now = new DateTime;
                
                if(!$this->birthday)
                        return null;
                
                $birthday = new DateTime($this->birthday);
                
                if(!$birthday)
                        return null;                
                
                $offset = $birthday->diff($now);
            
                return $offset->format('%y');                
        }
        /**
         * 
         */
        public function getAgeUser($birthday=null)
        {
                $now = new DateTime;
                
                if(!$birthday)
                        return null;
                
                $birthday = new DateTime($birthday);

                $offset = $birthday->diff($now);
            
                return $offset->format('%y');                
        }
	/**
	 * Возвращает список моделей, соответствующий условиям поиска
	 * @return CActiveDataProvider поставщик данных, который может возврнатить список моделей, соответствующий условиям поиска
	 */
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('t.id',$this->id);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('role',$this->role,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('id_gender',$this->id_gender);
                $criteria->compare('id_region',$this->id_region);
		$criteria->compare('email',$this->email,true);
                $criteria->addCondition('role <> "admin"');
                $criteria->with = array('userpic', 'lastAction');

                if($this->online)
                {
                        $now = Yii::app()->localtime->getUTCNow();
                        $expireTime = new DateTime;    
                        $expireTime = $expireTime->sub(new DateInterval("PT15M"))->format('Y-m-d H:i:s');
                        
                        $criteria->join .= ' JOIN _action ON _action.id_user = t.id';
                        $criteria->addCondition("_action.date > STR_TO_DATE('$expireTime', '%Y-%m-%d %H:%i:%s')");
                }
                        
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'pagination' => array(
                                'pageSize' => 50
                        ),
		));              
	}
            
        /**
         * Дополнительные действия после успешной регистрации пользователя
         */
        private function regInit()
        {                                                                                                              
                // создаём пустую анкету пользователя в бд
                Yii::app()->db->createCommand()
                        ->insert('profile', array(
                                'id_user' => $this->id
                        )
                );
                
                // создаём запись в таблице последний действий пользователя
                Yii::app()->db->createCommand()
                        ->insert('_action', array(
                                'id_user' => $this->id
                        )
                );
        }
        
        /**
         * Событие, происходящее после сохранения модели
         * 
         * @return boolean результат срабатывания события
         */
        protected function afterSave()
        {   
                parent::afterSave();
                
                if($this->isNewRecord)
                {
                        $this->regInit();
                        self::setMaxRating($this->id);
                }
                            
                $this->updateTimezone();
                
                return true;
        }
        
        /**
         * Метод обновляет дату последнего посещения сайта
         * 
         * @param integer $userID ID пользователя
         */
        public static function updateLastVisitDate($userID)
        {
                $now = date('Y-m-d H:i:s');
                            
                $sql = "UPDATE `user` SET `date_lastvisit` = '".$now."' WHERE `id` = '".$userID."'";
                
                Yii::app()->db->createCommand($sql)->execute();
        }
        
        /**
         * Метод помечает анкету пользователя как удалённую
         * 
         * @param integer $userID ID пользователя
         * @return boolean результат
         */
        public static function markProfileAsDelete($userID)
        {
                $date = date('Y-m-d H:i:s');
                
                Yii::app()->db->createCommand()
                        ->update('message', array(
                                'status' => Message::STATUS_READ,
                        ), 
                        'id_sender = :id OR id_reciever = :id',
                        array(
                                'id' => $userID
                        )
                );
                
                return Yii::app()->db->createCommand()
                        ->update('user', array(
                                'fl_deleted' => 1,
                        ), 
                        'id = :id',
                        array(
                                'id' => $userID
                        )
                );
        }
        
        /**
         * Метод восстанавливает удаленную анкету пользователя
         * 
         * @param integer $userID ID пользователя
         * @return boolean результат
         */
        public static function markProfileAsActive($userID)
        {               
                return Yii::app()->db->createCommand()
                        ->update('user', array(
                                'fl_deleted' => 0,
                        ), 
                        'id = :id',
                        array(
                                'id' => $userID
                        )
                );
        }    
         
        /**
         * Метод получает юзерпик заданного размера
         * 
         * @param string $dimension размер юзерпика
         * @return string url юзерпика
         */
        public function getUserpic($dimension)
        {
                $data = self::userpicDimensionsList();
                
                // получаем имя атрибута (поля) модели с названием файла
                $attribute  = $data[$dimension]['attribute'];  

                if($this->userpic != null && !empty($this->userpic))
                        return '/' . Photo::UPLOAD_FOLDER . '/' . $this->id . '/' . $this->userpic->{$attribute};
                else    
                        return $data[$dimension]['nopic'];           
        }
        
        /**
         * Загрузить подарки определенного пользователя
         * 
         * @param integer $id_reciever ID получателя
         * @return array массив подарков
         */
        public static function getGifts($userID)
        {
                $gifts = Yii::app()->cache->get('gifts_'.$userID);
            
                // кеш нужно очищать при удалении подарка, дарении подарка, удалении открытки подарка
                if($gifts === false)
                {             
                        $gifts = Yii::app()->db->createCommand()
                                ->select('
                                        gift.id AS id_gift,
                                        gift.image AS image, 
                                        reciever.id AS id_reciever,
                                        sender.id AS id_sender,
                                        sender.name AS name, 
                                        photo.filename_small AS userpic, 
                                        sender.birthday AS birthday,
                                        city.name AS city, 
                                        region.name AS region,  
                                        im_user_gift.postcard AS postcard, 
                                        im_user_gift.is_private AS is_private,
                                        im_user_gift.date AS date,
                                        im_user_gift.id AS id')
                                ->from('im_user_gift')
                                ->join('gift', 'im_user_gift.id_gift = gift.id')
                                ->join('user sender', 'im_user_gift.id_sender = sender.id')
                                ->join('user reciever', 'im_user_gift.id_reciever = reciever.id')
                                ->join('city', 'sender.id_city = city.id')
                                ->join('region', 'sender.id_region = region.id')
                                ->leftJoin('photo', 'sender.id_userpic = photo.id')
                                ->where('id_reciever = :recieverID' , array('recieverID' => $userID))
                                ->order('date DESC')
                                ->queryAll();
                        
                                Yii::app()->cache->set('gifts_'.$userID, $gifts, GLOBAL_CACHE_TIME);
                                
                                return $gifts;
                }
                else
                {
                        return $gifts;
                }
        }
        
        /**
         * Метод возвращает последний подарок, подаренный первым пользователем второму.
         * 
         * @param integer $senderID
         * @param integer $recieverID
         * @return array данные подарка
         */
        public static function getLastGift($senderID, $recieverID)
        {
                return Yii::app()->db->createCommand()
                        ->select('
                                gift.id AS id_gift,
                                gift.image AS image, 
                                reciever.id AS id_reciever,
                                sender.id AS id_sender,
                                sender.name AS name, 
                                photo.filename_small AS userpic, 
                                sender.birthday AS birthday,
                                city.name AS city, 
                                region.name AS region,  
                                im_user_gift.postcard AS postcard, 
                                im_user_gift.is_private AS is_private,
                                im_user_gift.date AS date,
                                im_user_gift.id AS id')
                        ->from('im_user_gift')
                        ->join('gift', 'im_user_gift.id_gift = gift.id')
                        ->join('user sender', 'im_user_gift.id_sender = sender.id')
                        ->join('user reciever', 'im_user_gift.id_reciever = reciever.id')
                        ->join('city', 'sender.id_city = city.id')
                        ->join('region', 'sender.id_region = region.id')
                        ->leftJoin('photo', 'sender.id_userpic = photo.id')
                        ->where('id_reciever = :recieverID AND id_sender = :senderID' , array('recieverID' => $recieverID, 'senderID' => $senderID))
                        ->order('date DESC')
                        ->limit(1)
                        ->queryRow();
        }
        
        /**
         * Метод получает заменитель для отсутствующего юзерпика
         * 
         * @param string $dimension размер юзерпика
         * @return string url изображения
         */
        public static function getNoPic($dimension)
        {
                $data = self::userpicDimensionsList();
                return $data[$dimension]['nopic']; 
        }

        /**
         * Метод получает аватарку администратора
         * 
         * @param string $dimension размер автарки
         * @return string url изображения
         */
        public static function getAdminPic($dimension)
        {
                $data = self::userpicDimensionsList();
                return $data[$dimension]['admin']; 
        }
        
        /**
         * Метод получает изображение, иллюстрирующее наличие пользователя
         * в чёрном списке
         * 
         * @param string $dimension размер изображения
         * @return string url изображения
         */
        public static function getBlaclistPic($dimension)
        {
                $data = self::userpicDimensionsList();
                return $data[$dimension]['blacklist']; 
        }
        

         
        /**
         * Метод возвращает знак зодиака пользователя (у текущей модели)
         * 
         * @return string текстовое описание зодиака
         */
        public function getZodiac()
        {
                return self::getZodiacDescription($this->birthday);
        }
        
        /**
         * Метод возвращает знак зодиака, соответсвующий дате рождения пользователя
         * 
         * @param string $birthday
         * @return string знак зодиака
         */
        public static function getZodiacDescription($birthday)
        {
                $date = DateTime::createFromFormat('Y-m-d', $birthday);
            
                if(!$date)
                        return '';
                
                $month = $date->format('n');
                $day = $date->format('j');
            
                $signs = array(
                        "Козерог",
                        "Водолей",
                        "Рыбы",
                        "Овен",
                        "Телец",
                        "Близнецы",
                        "Рак",
                        "Лев",
                        "Дева", 
                        "Весы", 
                        "Скорпион", 
                        "Стрелец",
                );
                
                $signsstart = array(
                        1 => 22, 
                        2 => 21, 
                        3 => 19,
                        4 => 21, 
                        5 => 21, 
                        6 => 21, 
                        7 => 22,
                        8 => 23, 
                        9 => 24, 
                        10 => 24, 
                        11 => 24, 
                        12 => 23,
                        13 => 22,
                );
                
                return $day < $signsstart[$month + 1] ? $signs[$month - 1] : $signs[$month % 12];
        }
        
        
        /**
         * Метод получает статус оналйн пользователя
         * 
         * @return boolean статус "онлайн" пользователя
         */
        public function getOnlineStatus()
        {
                // получаем текущую дату и дату последнего действия пользователя            
                $now = Yii::app()->localtime->getUTCNow();
                $lastActionDate = $this->getLastActionDate();
                
                // если данные неверны, то ничего не делаем       
                if(empty($lastActionDate) || empty($now))
                        return false;
                
                // находим разницу между датами
                $delta = strtotime($now) - strtotime($lastActionDate);
                                           
                // если пользователь совершал действие менее, чем 15 (900 секунд) минут назад, то он - онлайн
                return $delta < 900;      
        }
        
        /**
         * Метод возвращает массив с фотографиями пользователя
         * 
         * @param integer $userID, ID пользователя, чьи фотографии выбираются
         * @param userpcID $userID, ID аватарки пользователя, если не пуста, то
         * фотография с таким ID становится первой
         * 
         * @return array
         */
        public static function getPhotos($userID, $userpicID = null)
        {       
                // кешируем фото пользователя. Кеш надо изменять при удалении или добавлении фото
                $photos = Yii::app()->cache->get('photos_'.$userID);
            
                if($photos === false)
                {
                        $photos = Yii::app()->db->createCommand()
                                ->select('id, id_user, filename_medium, filename_big')
                                ->from('photo')
                                ->where('id_user = :userID', array('userID' => $userID))
                                ->order('id DESC')
                                ->queryAll();

                        Yii::app()->cache->set('photos_'.$userID, $photos, GLOBAL_CACHE_TIME);
                }
                                    
                // если у пользователя установлена аватарка, то обрабатываем массив так,
                // что фотография, выбранная в качестве аватарки ставится первой
                if(!empty($userpicID))
                {                  
                        foreach($photos as $key => $value)
                                if($value['id'] == $userpicID)
                                        $userpicKey = $key;
                                                  
                        // если нет автарки, то возвращаем альбом без изменений
                        if(!empty($userpicKey))
                        {
                                // часть массива, начинающаяся с аватарки пользователя
                                $headPart = array_slice($photos, $userpicKey, count($photos), true);
                                // часть массива, до аватарки пользователя
                                $lastPart = array_slice($photos, 0, $userpicKey, true);

                                // ключи в результирующем массиве не сортируются
                                $photos =  $headPart + $lastPart;
                        }
                }
                
                // делаем сразу ссылки на изображения
                foreach ($photos as $key => $value)
                {
                        $photos[$key]['filename_big'] = Photo::getUploadFolderURL($value['id_user']) . $value['filename_big'];
                        $photos[$key]['filename_medium'] = Photo::getUploadFolderURL($value['id_user']) . $value['filename_medium'];
                }
                
                return $photos;
        }
        
        /**
         * Метод получает способы знакомства пользователя.
         * Используется кеширование
         * 
         * @param integer $userID ID пользователя
         * @return array ID способов знакомства
         */
        public static function getMeetmethods($userID)
        {
                $meetmethods = Yii::app()->cache->get('meetmethods'.$userID);
                
                // кеш надо сбрасывать при обновлении данных. (регистрация и профиль)
                if(empty($meetmethods))
                {
                        $meetmethods = Yii::app()->db->createCommand()
                                ->select('id_meetmethod')
                                ->from('im_user_meetmethod')
                                ->where('id_user = :userID', array('userID' => $userID))
                                ->order('id_meetmethod ASC')
                                ->queryColumn();
                        
                        Yii::app()->cache->set('meetmethods'.$userID, $meetmethods, GLOBAL_CACHE_TIME);
                        
                        return $meetmethods;
                }
                else
                {
                        return $meetmethods;
                }
        }
    
                
        /**
         * Метод возвращает дату совершения последнего действия пользователя
         * 
         * @return string дата последнего действия
         */
        public function getLastActionDate()
        {
                return $this->lastAction === null ? null : $this->lastAction->date;
        }
        
        /**
         * Метод создаёт профиль, привязанный к текущему юзеру
         */
        public function createProfile()
        {
                // создаём пустую анкету пользователя в бд
                Yii::app()->db->createCommand()
                        ->insert('profile', array(
                                'id_user' => $this->id
                        )
                );
        }
        
        /**
         * Метод выставляет максимальный рейтинг пользователю
         * 
         * @param integer $userID ID пользователя
         * @return boolean произошло ли увеличение рейтинга
         */
        public static function setMaxRating($userID)
        {
                if(empty($userID))
                        return false;
            
                return Yii::app()->db->createCommand()
                        ->update(
                                'user',
                                array(
                                        'date_rating' => Yii::app()->localtime->getUTCNow()
                                ), 
                                'id = :userID',
                                array(
                                        'userID' => $userID
                                )
                        );
        }

        public static function getBaseInfo($userID)
        {
                return Yii::app()->db->createCommand()
                        ->select('user.id, user.name, user.phone, user.birthday, photo.filename_medium AS userpic, user.id_gender')
                        ->from('user')
                        ->leftJoin('photo', 'user.id_userpic = photo.id')
                        ->where('user.id = :userID', array('userID' => $userID))
                        ->queryRow();
        }
        
        public static function getPhone($userID)
        {
                return Yii::app()->db->createCommand()
                        ->select('phone')
                        ->from('user')
                        ->where('id = :userID', array('userID' => $userID))
                        ->queryScalar();
        }
        
        public static function checkID($userID)
        {
                $count = Yii::app()->db->createCommand()
                        ->select('COUNT(*)')
                        ->from('user')
                        ->where('id = :userID', array('userID' => $userID))
                        ->queryScalar();
                
                return (boolean)$count;
        }
        
        public static function checkBalance($userID, $amount)
        {
                $balance = Yii::app()->db->createCommand()
                        ->select('account + account_bonus')
                        ->from('user')
                        ->where('id = :userID', array('userID' => $userID))
                        ->queryScalar();
                
                return $balance >= $amount;
        }
        
        public static function updateOfferCounter()
        {
                $currentCount = Yii::app()->user->getOfferCounter();
                $userID = Yii::app()->user->id;
                
                // если счётчик неотрицательный - увеличиваем его.
                // если отрицательный - значит бонус у юзера отключен
                if($currentCount >= 0)
                {
                        // если фоток меньше пяти и предложение первое - отключаем бонус
                        $photosCount = Yii::app()->db->createCommand()
                                ->select('COUNT(*)')
                                ->from('photo')
                                ->where('id_user = :userID', array('userID' => $userID))
                                ->queryScalar();
                        
                        if($photosCount < 5 && $currentCount == 0)
                                return self::stopOfferCounter($userID);
                    
                        $currentCount++;
                        
                        // увеличиваем счётчик
                        $result = Yii::app()->db->createCommand()
                                ->update('user', array(
                                        'counter_offer' => $currentCount
                                ), 
                                'id = :userID', 
                                array(
                                        'userID' => $userID)
                                );

                        // если достигло 200, устанавливаем метку о возможном получении бонуса в таблице и
                        // останавливаем увеоичение счётчика
                        if($currentCount == 200 && self::setBonusMoneybackDemand($userID))
                                $result = self::stopOfferCounter($userID);
                        
                        return $result;
                }
                else
                        return true;
        }
        
        public static function getTopRated($regionId = false, $limit = 18)
        {
                $cacheKey = 'top_rated_users';
                
                if($regionId)
                        $cacheKey .= $regionId;             
                
                $users = Yii::app()->cache->get($cacheKey);

                if(!$users)
                {
                        $criteria = new CDbCriteria();
                        $criteria->select = "
                                t.id,
                                t.name,
                                t.id_userpic,
                                t.birthday,
                                t.id_gender,
                                (SELECT COUNT(*) FROM offer WHERE id_reciever = t.id) AS offer_count
                        ";
                        $criteria->with = array('userpic', 'lastAction', 'city', 'profile');
                        $criteria->addCondition('t.id_userpic IS NOT NULL');
                        $criteria->compare('t.register_step', 0);
                        $criteria->compare('t.role', User::ROLE_USER);
                        $criteria->compare('t.fl_deleted', 0);
                        $criteria->addNotInCondition('t.id', JTopRatedWidget::getBlacklisted());
                        $criteria->order = 'offer_count DESC, t.date_rating DESC';
                        $criteria->limit = $limit;
                        
                        if($regionId)
                                $criteria->compare('t.id_region', $regionId);
                        
                        $users = self::model()->findAll($criteria);
                        
                        Yii::app()->cache->set($cacheKey, $users, self::CACHE_TOPRATED_DURATION);
                }
                
                return $users;
        }

        public static function setBonusMoneybackDemand($userID)
        {
                return Yii::app()->db->createCommand()
                        ->insert('bonus_money_return', array(
                                'id_user' => $userID,
                        ));
        }
        
        public static function stopOfferCounter($userID)
        {
                return Yii::app()->db->createCommand()
                        ->update('user', array(
                                'counter_offer' => -1
                        ), 
                        'id = :userID', 
                        array(
                                'userID' => $userID)
                        );
        }
        
        public function getNewEmail(){
   
                $mail = Yii::app()->db->createCommand()
                            ->select('email')
                            ->from('new_email')
                            ->where('id_user = :userId', array('userId'=> $this->id))
                            ->queryRow();
                return $mail['email'];
        }
        
        public function updateTimezone()
        {
                $timeZone = Yii::app()->db->createCommand()
                        ->select('timezone')
                        ->from('region')
                        ->where('id = :regionID', array('regionID' => $this->id_region))
                        ->queryScalar();            
                
                return Yii::app()->db->createCommand()
                        ->update('user', array('timezone' => $timeZone), 'id = :userID', array('userID' => $this->id));                    
        }
        
        public function validateCity()
        {
                $regionByCity = Yii::app()->db->createCommand()
                        ->select('id_region')
                        ->from('city')
                        ->join('region', 'region.id = city.id_region')
                        ->where('city.id = :cityId', array('cityId' => $this->id_city))
                        ->queryScalar();
                
                if($this->id_region != $regionByCity)
                        $this->addError('city', 'Город не соответствует региону');
        }
}
