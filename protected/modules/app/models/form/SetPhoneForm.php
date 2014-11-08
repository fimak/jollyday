<?php

/**
 * Модель формы для изменения номера телефона пользователя
 */
class SetPhoneForm extends CFormModel
{
        /**
         * @var string новый номер телефона
         */
        public $phone;
        
        /**
         * @var string код подтверждения смены номера телефона
         */
        public $code;
        
        /**
         * Метод, возвращающий массив с описанием правил валидации модели
         * 
	 * @return array массив правил валидации
	 */
        public function rules()
        {
                return array(
                        array('phone', 'filter', 
                                'filter' => array($this, 'filterPhone'),
                                'on' => 'request',
                        ),
                        array('phone', 'length', 
                                'is' => 10,
                                'message' => 'Номер телефона введён некорректно',
                                'on' => 'request'
                        ),
                        array('phone', 'required',
                                'on' => 'request'
                        ),
                        array('phone', 'vPhone',
                                'on' => 'request',
                        ),
                        array('code', 'required',
                                'message' => 'Введите, пожалуйста, код',
                                'on' => 'confirm'
                        ),
                        array('code', 'vCode',
                                'on' => 'confirm',
                        )
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
                        'phone' => 'Новый номер:',
                        'code' => 'Код подтверждения:'
                );
        }
        
        /**
         * Валидатор, проверяет номер на уникальность
         */
        public function vPhone()
        {
                if($this->getScenario() != 'request')
                        return;
            
                $row = Yii::app()->db->createCommand()
                        ->select('phone')
                        ->from('user')
                        ->where('phone = :phone', array(':phone' => $this->phone))
                        ->queryRow();
                
                if($row)
                        $this->addError ('phone', 'На такой номер телефона уже зарегистрирована анкета');
        }
        
        /**
         * Валидатор, проверяет, существует ли код подтверждения номера
         */
        public function vCode()
        {
                if($this->getScenario() != 'confirm')
                        return;
            
                $row = Yii::app()->db->createCommand()
                        ->select('phone')
                        ->from('new_phone')
                        ->where('code = :code AND id_user = :id_user', array(':code' => $this->code, 'id_user' => Yii::app()->user->id))
                        ->queryRow();
                
                if(!$row)
                        $this->addError ('code', 'Неверный код');
        }
        
        /**
         * Фильтр поля для номера телефона пользователя
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

?>
