<?php
/**
 *  Класс виджета JQuery Placeholder
 */
class JPlaceholder extends CWidget
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
         * Инициализация виджета
         */
	public function init()
	{
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
		Yii::app()->clientScript->registerScript($this->getId(), "          
                    $(document).ready(function() {
                        $('$this->target').placeholder(); 
                    });
		",  CClientScript::POS_READY);
	}
       
	/**
         * Метод публикации ресурсов виджета
         */
	private function publishAssets()
	{
		$assets = dirname(__FILE__).'/assets';
		$baseUrl = Yii::app()->assetManager->publish($assets);
                
                $cs = Yii::app()->getClientScript();
                
		if(is_dir($assets))
                {
                        // поключение скриптов
			$cs->registerCoreScript('jquery');
                        
                        if(YII_DEBUG)
                                $cs->registerScriptFile($baseUrl . '/jquery.placeholder.js', CClientScript::POS_HEAD);
                        else
                                $cs->registerScriptFile($baseUrl . '/jquery.placeholder.min.js', CClientScript::POS_HEAD);
                        
                        // подключение CSS
                        $cs->registerCssFile($baseUrl . '/jquery.placeholder.css');
		}
                else
			throw new Exception('JPlaceholder - Error: Не найдны ресурсы.');
	}
}