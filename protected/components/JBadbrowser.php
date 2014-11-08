<?php
/**
 * Компонент режима техобслуживания сайта
 */
class JBadbrowser extends CComponent
{
        /**
         * @var boolean включение заглушки
         */
        public $enable = true;
        
        /**
         * @var string маршрут действия отображения заглушки
         */
        public $capRoute = '/site/badbrowser';
        
        /**
         *
         * @var array список браузеров для редиректа (user-agent)
         */
        public $browsers = array();
        
        /**
         * Инициализация компонента
         */
        public function init()
        {
                if($this->enable)
                {  
                        $userAgent = Yii::app()->request->userAgent;
                        $isBadBrowser = false;

                        foreach($this->browsers as $browser)
                                if(stripos($userAgent, $browser) !== false)
                                        $isBadBrowser = true;

                        if($isBadBrowser)                
                                Yii::app()->catchAllRequest = array($this->capRoute);
                }
        }
}