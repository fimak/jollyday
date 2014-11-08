<?php
/**
 *  Класс виджета Jquery Form Styler
 */
class JFormStyler extends CWidget
{
        /**
         * @var string ID виджета
         */
	public $id;

        /**
         * @var string селектор целевого элемента плагина jQuery
         */
	public $target;
	
        /**
         * @var array конфигурация плагина
         */
	public $config = array();
        
        /**
         * @var string скин
         */
        public $skin;
	
	/**
         * Инициализация виджета
         */
	public function init()
	{
                if(empty($this->skin))
                        throw new CException('JFormStyler: не указан скин');
            
		// Если ID не представлен, то Yii генерирует дефолтный
		if(!isset($this->id))
			$this->id=$this->getId();
		// Публикация ресурсов
		$this->publishAssets();
	}
	
        /**
         * Запуск виджета
         */
        public function run()
        {
		$config = CJavaScript::encode($this->config);
                
                // применяем плагин для существующего контента + для подгружаемого контента
		Yii::app()->clientScript->registerScript($this->getId(), "          
                    $(document).ready(function() {
                        $('$this->target').styler($config); 
                    });
                    $(document).ajaxSuccess(function(){
                        $('$this->target').styler($config); 
                    });
		",  CClientScript::POS_READY);
	}
       
	/**
         * Метод, публикубщий ресурсы JFormStyler
         * 
         * @throws Exception
         */
	private function publishAssets()
	{
		$assets = dirname(__FILE__).'/assets';
		$baseUrl = Yii::app()->assetManager->publish($assets);
                
		if(is_dir($assets))
                {
			Yii::app()->clientScript->registerCoreScript('jquery');
			Yii::app()->clientScript->registerScriptFile($baseUrl . '/jquery.formstyler.js', CClientScript::POS_HEAD);
                        
                        Yii::app()->clientScript->registerCssFile($baseUrl . "/jquery.formstyler.$this->skin.css");
		}
                else
			throw new Exception('JFormStyler - Error: Не найдны ресурсы.');
	}
}