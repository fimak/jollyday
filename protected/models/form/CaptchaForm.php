<?php

/**
 * Модель формы с капчей
 */
class CaptchaForm extends CFormModel
{
        /**
         * @var string поле для верификационного кода с капчи
         */
        public $captcha;
        
        /**
         * Метод, возвращающий массив с описанием правил валидации модели
         * 
	 * @return array массив правил валидации
	 */
        public function rules()
        {
                return array(
                        array('captcha', 'captcha', 
                                'captchaAction' => 'site/captcha', 
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
                        'captcha' => 'Введите код',
                );
        }        
}

?>
