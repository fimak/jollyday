<?php

/**
 * Модель формы для подтверждения номера телефона при регистрации
 */
class RegisterConfirmForm extends CFormModel
{  
        /**
         * @var string смс-код
         */
        public $sms;

        /**
         * Метод, возвращающий массив с описанием правил валидации модели
         * 
	 * @return array массив правил валидации
	 */
	public function rules()
	{
		return array(
                        array('sms', 'required', 'message'=> 'Введите, пожалуйста, код'),
                        array('sms', 'vSms'),
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
			'sms' => 'Код из СМС:',                  
		);
	}
        
        /**
         * Валидатор СМС кода
         */
        public function vSms()
        {
            $code = Yii::app()->db->createCommand()
                    ->select('code')
                    ->from('new_user')
                    ->where('code = :code', array('code' => $this->sms))
                    ->queryScalar();
            
            if($code == null)
                $this->addError('sms', 'Код неверен');
        }
        
        /**
         * Метод проверяет, выставлена ли кука, с регистрационным номером телефона
         * и возвращает её значение, если она есть и null, если нету
         * 
         * @return string|null данные
         */
        public static function checkPhoneCookie()
        {
                return (isset(Yii::app()->request->cookies['new_userphone']->value))
                        ? Yii::app()->request->cookies['new_userphone']->value
                        : null;              
        }           
}