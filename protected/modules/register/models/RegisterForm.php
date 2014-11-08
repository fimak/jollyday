<?php
/**
 * Модель формы регистрации - шаг 1
 */
class RegisterForm extends CFormModel
{  
        /**
         * @var string номер телефона пользователя
         */
        public $phone;
        
        /**
         * @var string пароль
         */
        public $password;
        
        /**
         * @var string повтор пароля
         */
        public $passwordConfirm;
        
        /**
         * @var string верификационный код для капчи
         */
        public $verifyCode;
        
        /**
         * @var mixed поля для привязывании ошибки, связанной с превышением лимита попыток регистрации
         */
        public $attempt;

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
                    array('phone, password', 
                            'required', 
                            'message'=> 'Поле обязательно для заполнения'
                    ),
                    array('phone', 'match', 
                            'pattern' => '/^[0-9]{10}$/', 
                            'message' => 'Некорректный номер телефона'
                    ), 
                    array('phone', 'unique', 
                            'className' => 'User', 
                            'attributeName' => 'phone',
                            'message' => 'Этот номер уже есть в базе'
                    ),
                    array('passwordConfirm', 'compare', 
                            'compareAttribute' => 'password', 
                            'message' => 'Пароли не совпадают'
                    ),
                    array('password', 'length', 
                            'min' => 6, 
                            'max' => 16, 
                            'tooShort' => 'Пароль слишком короткий', 
                            'tooLong' => 'Пароль слишком длинный'
                    ),
                    array('verifyCode', 'captcha', 
                            'captchaAction' => 'site/captcha', 
                            'allowEmpty' => !Yii::app()->settings->get('SiteAccess', 'regCaptcha'),
                            'message' => 'Неверный проверочный код'
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
			'phone' => 'Номер телефона:',
			'password' => 'Пароль:',
			'passwordConfirm' => 'Повторите пароль:',
                        'verifyCode' => 'Код с картинки:',
		);
	}

        /**
         * Событие, происходящее после валидации модели
         */
        public function afterValidate()
        {               
                parent::afterValidate();

                //получаем параметры защиты из настроек
                $Protection15Min = Yii::app()->settings->get('SiteAccess', 'regProtection15Min');
                $ProtectionDay = Yii::app()->settings->get('SiteAccess', 'regProtectionDay');
                
                // если включены ограничения на регистрацию
                if($Protection15Min || $ProtectionDay)
                {
                        if(!$this->hasErrors())
                        {
                                // если форма успешно свалидировалась, то проверяем сколько раз
                                // регигись с ip юзера
                                $ip = Yii::app()->request->userHostAddress;

                                $countAttempts = JRegisterLog::count($ip);


                                if($countAttempts['per_day'] > JRegisterLog::$maxAttemptsPerDay && Yii::app()->params['enableRegisterProtectionDay'])
                                {
                                        // если непозволительно много, то выписываем юзеру бороду
                                        $this->addError('attempt', 'Вы превысили количество попыток регистраций в день с вашего IP-адреса');
                                        return false;
                                }
                                elseif($countAttempts['per_min'] >= 2 && $Protection15Min)
                                {
                                        // также ограничиваем двумя попытками в 15 минут
                                        $this->addError('attempt', 'Не более двух попыток за 15 минут!');
                                        return false;                                    
                                }
                                else
                                {
                                        // иначе засчитываем юзеру попытку регистрации
                                        JRegisterLog::logRegisterAttempt($ip);
                                }
                                return true;
                        }
                }    
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