<?php
/**
 * Модель формы для входа в админку
 */
class BackendLoginForm extends CFormModel
{
        /**
         * @var string номер телефона пользователя
         */
	public $username;
        
        /**
         * @var string пароль пользователя
         */
	public $password;
        
        /**
         * @var string капча 
         */
        public $captcha;

        /**
         * @var JBackendUserIdentity поле для компонента идентификации администратора
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
                        array('username', 'safe'),
			array('password', 'authenticate'),
                        array('captcha', 'captcha', 
                                'captchaAction' => '/default/captcha', 
                                'message' => 'Проверочный код введен неверно'
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
			'username'=>'Логин',
                        'password' => 'Пароль',
                        'captcha' => 'Проверочный код',
		);
	}

	/**
	 * Валидатор логина и пароля
	 */
	public function authenticate($attribute,$params)
	{              
		if(!$this->hasErrors())
		{
			$this->_identity=new JBackendUserIdentity($this->username,$this->password);
			if(!$this->_identity->authenticate())
				$this->addError('password','Логин или пароль не верен.');
		}
	}

	/**
	 * Метод, аутентифицирующий админа
         * 
	 * @return boolean результат аутентификации
	 */
	public function login()
	{
		if($this->_identity===null)
		{
			$this->_identity=new JBackendUserIdentity($this->username,$this->password);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===JBackendUserIdentity::ERROR_NONE)
		{
			$duration = 3600; 
			Yii::app()->user->login($this->_identity,$duration);
			return true;
                        
		}
		else
			return false;
	}
}
