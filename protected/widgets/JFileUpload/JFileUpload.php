<?php

/**
 * Класс виджета jQuery-плагина JQuery File Upload
 */
class JFileUpload extends CInputWidget
{
        /**
         * @var array конфигурация jQuery gkfubyf
         */
        public $config;
                        
        /**
         * @var string ID поля для файла
         */
        private $_id;
        
        /**
         * @var string имя поля для файла
         */
        private $_name;

	/**
         * Инициализация виджета
         */
        public function init()
        {
                // Если ID не представлен, то Yii генерирует дефолтный
                if(!isset($this->id))
                        $this->id=$this->getId();
                
                $this->publishAssets();
        }
        
        /**
         * Запуск виджета
         */
        public function run()
        {
                $this->defineNameId(); 
            
                $this->htmlOptions['multiple'] = 'multiple';
                
                $config = CJavaScript::encode($this->config);
                
                // выводим поле для ввода файла
		if($this->hasModel())
			echo CHtml::activeFileField($this->model,$this->attribute,$this->htmlOptions);
		else
			echo CHtml::fileField($this->_name,$this->value,$this->htmlOptions);
                        
                // применяем плагин к элементу
		Yii::app()->clientScript->registerScript($this->getId(), "          
                    $(document).ready(function() {
                        $('#$this->inputId').fileupload($config); 
                    });
		",  CClientScript::POS_READY);
        }
        
        /**
         * Метод публикует ресурсы плагина
         */
        private function publishAssets()
        {
		$assets = dirname(__FILE__).'/assets';
		$baseUrl = Yii::app()->assetManager->publish($assets);
                
                $cs = Yii::app()->getClientScript();
                
		if(is_dir($assets))
                {
			Yii::app()->clientScript->registerCoreScript('jquery.ui');
                        
                        $cs->registerScriptFile($baseUrl . '/jquery.fileupload.js', CClientScript::POS_HEAD);
                        $cs->registerScriptFile($baseUrl . '/jquery.iframe-transport.js', CClientScript::POS_HEAD); 
		}
                else
			throw new Exception('JFileUpload- Error: Не найдны ресурсы.');
        }
        
        /**
         * Метод получает ID поля ввода
         * 
         * @return string ID поля
         */
        protected function getInputId()
        {
                if($this->_id===null)
                        $this->defineNameId();
                return $this->_id;
        }
        /**
         * Метод получает имя поля ввода
         * 
         * @return string имя поля
         */
        protected function getInputName()
        {
            if($this->_name===null)
                    $this->defineNameId();
            return $this->_name;
        }
        /**
         * Метод устанавливает ID и имя поля
         */
        protected function defineNameId()
        {
            list($name,$id)=$this->resolveNameID();
            $this->_id=$this->htmlOptions['id']=$id;
                    $this->_name=$this->htmlOptions['name']=$name;
        }
}