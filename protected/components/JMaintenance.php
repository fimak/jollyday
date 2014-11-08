<?php
/**
 * Компонент режима техобслуживания сайта
 */
class JMaintenance extends CComponent
{
        /**
         * @var boolean включить режим техобслуживания сайта
         */
        public $enabledMode = true;
        
        /**
         * @var string маршрут заглушки
         */
        public $capUrl = 'maintenance/index';
        
        /**
         * @var string сообщение
         */
        public $message = "Извините, на сайте ведутся технические работы.";

        /**
         * @var array массив пользователей, которым насрать на техобслуживание
         */
        public $users = array('admin');
        
        /**
         * @var array массив IP, которым насрать на техобслуживание
         */
        public $allowedIPs = array();

        /**
         * Инициализация компонента
         */
        public function init()
        {
                // получаем режим технических работ из настроек сайта
                $this->enabledMode = Yii::app()->settings->get('SiteAccess', 'maintenanceMode');

                $allowed = !$this->enabledMode;
                
                if ($this->enabledMode)
                {
   			foreach($this->allowedIPs as $ip)
   				if($_SERVER['REMOTE_ADDR'] == $ip)
   					$allowed = true;
                    
                        // проверка допустимых юзеров
                        if(in_array(Yii::app()->user->name, $this->users))
                                $allowed = true;

                        // если доступ на сайт разрешён, то перенаправляем на страницу с ообщением
                        if (!$allowed)                
                                Yii::app()->catchAllRequest = array($this->capUrl);
                }
        }
}
