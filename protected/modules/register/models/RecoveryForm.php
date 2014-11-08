<?php

/**
 * Модель формы восстановления пароля
 */
class RecoveryForm extends CFormModel
{
        /**
         * @var string номер телефона пользователя
         */
        public $phone;
        
        /**
         * @var string верификационный код с капчи
         */
        public $verifyCode;
        
        /**
         * @var string дата рождения пользователя
         */
        public $birthday;
        
        /**
         * @var mixed вспомогательное поле для выода ошибки формы
         */
        public $error;
        
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
                        array('phone', 'required',
                                'message' => 'Введите, пожалуйста, телефон',
                        ),
                        array('phone', 'exist', 
                                'className' => 'User', 
                                'attributeName' => 'phone', 
                                'message' => 'На указанный номер не зарегистрирована анкета'
                        ),                    
                        array('verifyCode', 'captcha', 
                                'captchaAction' => 'site/captcha', 
                                'message' => 'Проверочный код введен неверно'
                        ),
                        array('birthday', 'date', 
                                'format' => 'yyyy-MM-dd', 
                                'allowEmpty' => false, 
                                'message' => 'Дата рождения указана неверно', 
                                'skipOnError' => true
                            ),
                        array('birthday', 'vBirthday'),
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
			'phone' => 'Телефон:',
                        'birthday' => 'Дата рождения:',
                        'captcha' => 'Проверочный код:',
                        'verifyCode' => 'Введите код:',
		);
	}
        
        /**
         * Событие, происходящее после валидации модели
         */
        public function afterValidate()
        {
                parent::afterValidate();

                /*
                $ip = Yii::app()->request->userHostAddress;
                
                // если номер не существует, то не писать логи
                if($this->hasErrors('phone'))
                        return false;
            
                // проверяем на возможномть восстановления пароля
                $result = JRecoveryLog::checkRecoveryAllow($ip, $this->phone);
        
                if(!$result)
                {
                        $this->addError('error', 'Превышено количество попыток восстановления пароля в сутки');
                        return false;
                }
                */
                
               return true;
                   
        }

        /**
         * Валидатор проверяет верность указания даты рождения пользователя
         */
        public function vBirthday()
        {
                $user = User::model()->findByAttributes(array('phone' => $this->phone));
                
                if($user == null)
                {
                        $this->addError ('phone', 'Номер телефона неверен');
                        return;
                }
                
                if($user->birthday != $this->birthday)
                        $this->addError ('birthday', 'Дата рождения указана неверно');
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