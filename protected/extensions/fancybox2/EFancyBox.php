<?php
/**
 *  Класс виджета EFancyBox.
 */
class EFancyBox extends CWidget
{
	/**
         * @var string ID виджета
         */
	public $id;
        
	/**
         * @var string селектор, к которму будет применён виджет
         */
	public $target;
        
	/**
         * @var boolean подключать ли скрипты-хелперы (к fancyBox)
         */
	public $helpersEnabled=false;
        
	/**
         * @var boolean подключать ли скрипты управления fancyBox с помощью мыши
         */
	public $mouseEnabled=true;
	
        /**
         * @var array массив с конфигурацией fancyBox
         */
	public $config=array();
	
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
		$config = CJavaScript::encode($this->config);
		Yii::app()->clientScript->registerScript($this->getId(), "
			$('$this->target').fancybox($config);
		");
	}
	
	/**
         * Метод, публикации ресурсов виджета
         * 
         * @throws CException
         */
	public function publishAssets()
	{
		$assets = dirname(__FILE__).'/assets';
		$baseUrl = Yii::app()->assetManager->publish($assets);
		if(is_dir($assets))
                {
			Yii::app()->clientScript->registerCoreScript('jquery');
			Yii::app()->clientScript->registerScriptFile($baseUrl . '/jquery.fancybox.pack.js', CClientScript::POS_HEAD);
										
			Yii::app()->clientScript->registerCssFile($baseUrl . '/jquery.fancybox.css');
			
			// Если действия мышью включены, то подключаем скрипт
			if ($this->mouseEnabled)
				Yii::app()->clientScript->registerScriptFile($baseUrl . '/jquery.mousewheel-3.0.6.pack.js', CClientScript::POS_HEAD);
			
			//если хелперы вклдючены, то подключаем ресурсы
			if ($this->helpersEnabled)
                        {
                                Yii::app()->clientScript->registerScriptFile($baseUrl . '/helpers/jquery.fancybox-buttons.js', CClientScript::POS_HEAD);
                                Yii::app()->clientScript->registerScriptFile($baseUrl . '/helpers/jquery.fancybox-media.js', CClientScript::POS_HEAD);
                                Yii::app()->clientScript->registerScriptFile($baseUrl . '/helpers/jquery.fancybox-thumbs.js', CClientScript::POS_HEAD);
                                Yii::app()->clientScript->registerCssFile($baseUrl . '/helpers/jquery.fancybox-buttons.css');
                                Yii::app()->clientScript->registerCssFile($baseUrl . '/helpers/jquery.fancybox-thumbs.css');
                        }
		}
                else
			throw new CException('EFancyBox: Не найдны ресурсы.');
	}
}