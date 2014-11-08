<?php

/**
 * Класс виджета оповещения о технических работах
 */
class JMaintenanceWidget extends CWidget
{
        /** 
         * @var boolean включить ли виджет оповещения 
         */
        public $enabled;
        
        /** 
         * @var string id блока оповещения
         */
        public $id;
    
        
        private $text;
        
	/**
         * Инициализация виджета
         */
        public function init() 
        {
                parent::init();
 
                if(!isset($this->enabled) || empty($this->enabled))
                        $this->enabled = false;
                
                $this->text = Yii::app()->settings->get('Maintenance', 'text', '');
        }
        
        /**
         * Запуск виджета
         */
        public function run() 
        {
                if($this->enabled)
                        echo CHtml::tag('div', array('id' => $this->id), $this->text);
        }
    
}

?>