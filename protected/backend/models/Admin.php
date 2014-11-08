<?php

/**
 * Модель администратора сайта
 *
 * @property integer $id ID администратора
 * @property string $username имя пользователя
 * @property string $password пароль администратора
 * @property string $salt соль
 * @property string $name имя
 * @property string $role роль на сайте
 * @property string $email адрес электронной почты
 * @property string $date_register дата регистрации
 * @property string $date_lastvisit дата последнего входа
 */
class Admin extends CActiveRecord
{
        /** 
         * @var boolean пометка, меняется ли пароль администратора с помощью этой модели 
         */
        public $isNewPassword = false;
    
	/**
	 * Метод возвращает статичную модель определённого AR-класса
         * 
	 * @param string $className имя класса ActiveRecord.
	 * @return Admin статичная модель класса
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
         * Метод, возвращающий условие запросов по умолчанию
         * (работаем только с админами из таблицы)
         */
        public function defaultScope()
        {
                return array(
                        'condition'=>"role = 'admin'",
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
			array('phone, name', 'required'),
                        array('password', 'required', 'on' => 'create'),
                        array('phone, email', 'unique'),
			array('phone', 'length', 'max'=>32, 'min' => 5),
			array('password', 'length', 'max'=>32, 'min' => 5),
			array('name', 'length', 'max'=>64),
                        array('email', 'email'),
			array('id, phone, name, email, date_lastvisit', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array массив реляционных правил
	 */
	public function relations()
	{
		return array(
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
			'phone' => 'Логин',
			'password' => 'Пароль',
			'salt' => 'Соль',
			'name' => 'Имя',
			'role' => 'Роль',
			'email' => 'Email',
			'date_register' => 'Дата создания',
			'date_lastvisit' => 'Дата последнего входа',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('role',$this->role,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('date_register',$this->date_register,true);
		$criteria->compare('date_lastvisit',$this->date_lastvisit,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        /**
         * Хеширование пароля
         * 
         * @param type $salt Соль
         * @param type $password Пароль
         */
        public static function hashPassword($salt, $password)
        {
                return md5($salt.$password);
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
                        $this->role = User::ROLE_ADMIN;
                        $this->date_register = date('Y-m-d H:i:s');                     
                }
                
                // если при обновлении модели заполнено поле с паролем,
                // то хешируем заного
                if($this->getScenario() == 'update' && $this->isNewPassword)
                {
                        $this->salt = JRandom::salt();
                        $this->password = self::hashPassword($this->salt, $this->password);
                }
                
                                                     
                return parent::beforeSave();
        }
        
}