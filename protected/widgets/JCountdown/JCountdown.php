<?php
/**
 *  Класс виджета JQuery kkcountdown
 */
class JCountdown extends CWidget
{
        /**
         * @var string ID виджета
         */
	public $id;
        
        /**
         * @var array конфигурация плагина
         */
        public $config;
        
        /**
         * @var string селектор целевого элемента плагина jQuery
         */
	public $targetElement;
        
        /**
         * @var string направление счётчика 'until' или 'since' 
         */
        public $direction = 'until';
        
        /*
         * @var string целевое время счётчика (в формате для конструктора объекта Date javaScript - 'year, mth - 1, day, hr, min, sec')
         */
        public $date;
	
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
                
                if(!in_array($this->direction, array('until', 'since')))
                        throw new CException(__CLASS__.': параметр $direction должен быть "since" или "until"');
                
                if(empty($this->date))
                        throw new CException(__CLASS__.': параметр $date должен принимать объект DateTime');
	}
	
        /**
         * Запуск виджета
         */
        public function run()
        {       
                if($this->direction == 'until')
                        $this->config['until'] = $this->date;
                elseif($this->direction == 'since')
                        $this->config['since'] = $this->date;
            
                $config = CJavaScript::encode($this->config);
            
		Yii::app()->clientScript->registerScript($this->getId(), "          
                    $(document).ready(function() {
                        $('$this->targetElement').countdown($config); 
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
			$cs->registerCoreScript('jquery');
                        $cs->registerScriptFile($baseUrl . '/jquery.countdown.js', CClientScript::POS_HEAD);
		}
                else
			throw new Exception('JCountdown - Error: Не найдны ресурсы.');
	}
}