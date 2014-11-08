<?php

/**
 * Модель формы для изменения пароля
 *
 * @author office11
 */
class SetPasswordForm extends CFormModel
{
        /**
         * @var string текущий пароль пользователя
         */
        public $current_password;
        
        /**
         * @var string новый пароль пользователя
         */
        public $new_password;
        
        /**
         * @var string подтверждение нового пароля
         */
        public $new_password_confirm;
    
        /**
         * Метод, возвращающий массив с описанием правил валидации модели
         * 
	 * @return array массив правил валидации
	 */
        public function rules()
        {
                return array(
                    array('current_password', 'vCurrentPassword'),                    
                    array('new_password', 'length', 
                            'min' => 6, 
                            'max' => 16, 
                            'allowEmpty' => false,
                            'tooShort' => 'Пароль должен состоять из 6-16 символов',
                            'tooLong' => 'Пароль должен состоять из 6-16 символов',
                    ),
                    array('new_password_confirm', 'compare', 
                        'compareAttribute' => 'new_password',
                        'skipOnError' => true,
                        'message' => 'Пароли не совпадают'
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
                        'current_password' => 'Текущий пароль:',
                        'new_password' => 'Новый пароль:',
                        'new_password_confirm' => 'Повторите пароль:',
                );
        }
        
        /**
         * Валидатор текущего пароля
         */
        public function vCurrentPassword()
        {               
                $row = Yii::app()->db->createCommand()
                        ->select('password, salt')
                        ->from('user')
                        ->where('id = :id', array(':id' => Yii::app()->user->id))
                        ->queryRow();
                
                if($row['password'] != User::hashPassword($row['salt'], $this->current_password))
                        $this->addError('current_password', 'Пароль не совпадает с текущим');
        }
    
}

?>
