<?php

/**
 * Класс виджета jQuery-плагина Uploadify
 */
class JUploadify extends CInputWidget
{
        /**
         * @var array конфигурация jQuery gkfubyf
         */
        public $config;
        
        /**
         * @var string название ключа сессии
         */
        public $sessionKey='SESSION_ID';
        
        /**
         * @var string поле для пути до флеш-загрузчика
         */
        private $_swf;
        
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
            
                $this->config['swf'] = $this->_swf;
                $this->config['formData'][$this->sessionKey] = Yii::app()->session->sessionId;
                $this->config['formData'][$this->_name] = ' '; 
                $this->config['fileObjName'] = $this->_name; 
                $config = CJavaScript::encode($this->config);
                
                // выводим поле для ввода файла
		if($this->hasModel())
			echo CHtml::activeFileField($this->model,$this->attribute,$this->htmlOptions);
		else
			echo CHtml::fileField($this->_name,$this->value,$this->htmlOptions);
                        
                // применяем плагин к элементу
		Yii::app()->clientScript->registerScript($this->getId(), "          
                    $(document).ready(function() {
                        $('#$this->inputId').uploadify($config); 
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
                        // регистрация скриптов
			Yii::app()->clientScript->registerCoreScript('jquery');
                        
                        if(YII_DEBUG)
                                $cs->registerScriptFile($baseUrl . '/jquery.uploadify-3.1.js', CClientScript::POS_HEAD);
                        else
                                $cs->registerScriptFile($baseUrl . '/jquery.uploadify-3.1.min.js', CClientScript::POS_HEAD);
                        
                        // регистрация css
                        //$cs->registerCssFile($baseUrl.'/uploadify.css');
                        
                        // получение пути до swf-загрузчика
                        $this->_swf = $baseUrl.'/uploadify.swf';
		}
                else
			throw new Exception('JUploadify - Error: Не найдны ресурсы.');
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

?>