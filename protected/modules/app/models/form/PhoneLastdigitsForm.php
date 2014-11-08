<?php

/**
 * Модель формы для изменения адреса электроной почты
 *
 * @author office11
 */
class PhoneLastdigitsForm extends CFormModel
{
        /**
         * @var string новый адрес электронной почты пользователя
         */
        public $digits;
        
        /**
         * Метод, возвращающий массив с описанием правил валидации модели
         * 
	 * @return array массив правил валидации
	 */
        public function rules()
        {
                return array(
                        array('digits', 'vDigits'),
                );
        }
            
        /**
         * Валидатор, проверяет на существование емейла в базе
         */
        public function vDigits()
        {
                if($this->digits != substr(Yii::app()->user->getPhone(), -3))
                        $this->addError ('email', 'Вы неверно указали цифры');
        }
}