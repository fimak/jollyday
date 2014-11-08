<?php

/**
 * Модель формы регистрации - шаг заполнения персональных данных
 */
class RegPersonalForm extends CFormModel
{  
        /**
         * @var string имя пользователя
         */
        public $name;
        
        /**
         * @var string дата рождения пользователя
         */
        public $birthday;
        
        /**
         * @var integer ID региона пользователя
         */
        public $id_region;
        
        /**
         * @var integer ID города пользователя
         */
        public $id_city;
        
        /**
         * @var integer ID пола пользователя
         */
        public $id_gender;
        
        /**
         * @var email пользователя
         */
        public $email;
        
        /**
         * Метод, возвращающий массив с описанием правил валидации модели
         * 
	 * @return array массив правил валидации
	 */
	public function rules()
	{
		return array(
                        array('name, id_region, id_city, id_gender, birthday, email', 
                                'required', 
                                'message' => 'Поле не заполнено'
                        ),
                        array('email', 'unique',  
                                'className' => 'User',
                                'message' => 'Данный E-mail уже используется'
                            
                        ),
                        array('name', 'match', 
                                'pattern' => '/^[\Sa-zA-Zа-яА-ЯёЁ -]+$/iu', 
                                'message' => 'Недопустимые символы в имени'
                        ),
                        array('id_city', 'exist', 
                                'className' => 'City', 
                                'attributeName' => 'id'
                        ),
                        array('id_region', 'exist', 
                                'className' => 'Region', 
                                'attributeName' => 'id'
                        ),
                        array('id_gender', 'in', 
                                'range' => JGender::getIds()
                        ),
                        array('email', 'email',
                                'message' => 'Email введён некорректно',
                                'allowEmpty' => false
                        ),
                        array('birthday', 'application.validators.JAdultValidator', 
                                'minAge' => Profile::AGE_MIN, 
                                'maxAge' => Profile::AGE_MAX,
                        ),
                        array('birthday', 'date', 
                                'format' => 'yyyy-MM-dd', 
                                'skipOnError' => true, 
                                'allowEmpty' => false
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
			'name' => 'Имя',
                        'birthday' => 'Дата рождения',
                        'id_region' => 'Регион',
                        'id_city' => 'Город',
                        'id_gender' => 'Пол',
                        'email' => 'Электронная почта',
		);
	}      
}