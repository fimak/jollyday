<?php

/**
 * Модель формы диалога между пользователями
 */

class Chat extends CFormModel
{
        /**
         * @var string текст сообщения в чате
         */
        public $text;
        
        /**
         * @var integer ID получателя сообщения
         */
        public $recieverID;
        
        /**
         * @var boolean режим отправки сообщения (ctrl+enter или enter)
         * Используется для построения radioButtonList
         */
        public $sendMode;
        
        
        /**
         * Метод, возвращающий массив с описанием правил валидации модели
         * 
	 * @return array массив правил валидации
	 */
	public function rules()
	{
		return array(
                        array('text', 'filter', 'filter' => array($this, 'filterTrim')),
                        array('text', 'required'),
                        array('text', 'length', 'max' => 1000, 'min' => 1),
                        array('recieverID', 'exist', 'className' => 'User', 'attributeName' => 'id'),
		);
	}

        /**
         * @return array массив способов переноса строки
         */
        public function getSendModes()
        {
                return array(
                        0 => '<b>Ctrl+Enter</b> &mdash; отправка<br /><b>Enter</b> &mdash; перевод строки',
                        1 => '<b>Enter</b> &mdash; отправка<br /><b>Ctrl+Enter</b> &mdash; перевод строки',
                );
        }
        
        /**
         * Фильтр, делющий трим строки
         * 
         * @param string $value фильтруемая строка
         * @return string отфильтрованная строка
         */
        public function filterTrim($value)
        {
                return trim($this->text);
        }  
}