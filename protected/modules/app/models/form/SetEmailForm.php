<?php

/**
 * Модель формы для изменения адреса электроной почты
 *
 * @author office11
 */
class SetEmailForm extends CFormModel
{
        /**
         * @var string новый адрес электронной почты пользователя
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
                        array('email', 'email',
                            'allowEmpty' => false,
                            'message' => 'Некорректный адрес',
                        ),
                        array('email', 'vNewEmail'),
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
                        'email' => 'Новый адрес'
                );
        }
        
        /**
         * Валидатор, проверяет на существование емейла в базе
         */
        public function vNewEmail()
        {
                $count = Yii::app()->db->createCommand()
                        ->select('COUNT(*)')
                        ->from('user')
                        ->where('email = :email', array(':email' => $this->email))
                        ->queryScalar();
                
                if($count)
                        $this->addError ('email', 'Выбранный вами адрес уже существует на сайте');
        }
}