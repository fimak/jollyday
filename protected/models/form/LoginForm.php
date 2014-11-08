<?php

/**
 * Модель формы для входа на сайт.
 */
class LoginForm extends CFormModel
{
        /**
         * @var string номер телефона пользователя
         */
	public $phone;
        
        /**
         * @var string пароль пользователя
         */
	public $password;

        /**
         * @var JUserIdentity поле для компонента идентификации пользователя
         */
	private $_identity;

        /**
         * Метод, возвращающий массив с описанием правил валидации модели
         * 
	 * @return array массив правил валидации
	 */
	public function rules()
	{
		return array(
                        array('phone', 'filter', 
                                'filter' => array($this, 'filterPhone')
                        ),
                        array('phone', 'safe'),
                        array('password', 'authenticate'),
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
			'phone'=>'Номер телефона',
                        'password' => 'Пароль'
		);
	}

	/**
	 * Валидатор логина и пароля на соответствие
	 */
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			$this->_identity=new JUserIdentity($this->phone,$this->password);
			if(!$this->_identity->authenticate())
				$this->addError('password','Логин или пароль не верны');
		}
	}

	/**
	 * Метод, аутентифицирующий пользователя на сайте
         * 
	 * @return boolean успешность авторизации
	 */
	public function login()
	{
		if($this->_identity===null)
		{
			$this->_identity=new JUserIdentity($this->phone,$this->password);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===JUserIdentity::ERROR_NONE)
		{
			$duration = 3600*24*30; 
			Yii::app()->user->login($this->_identity,$duration);
			return true;
                        
		}
		else
			return false;
	}
        
        /**
         * Фильтр поля для номера телеона пользователя
         * 
         * @param string $value неотфильтрованное поле
         * @return string отфильтированное поле
         */
        public function filterPhone($value)
        {
                // вырезаем всё, кроме цифр
                $value = preg_replace('/[^0-9]/', '', $this->phone);
                
                // обрезаем первую семёрку - код страны
                return substr($value, 1);
        }
}
