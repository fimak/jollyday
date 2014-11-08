<?php
/**
 * Базовый контроллер модуля настроек сайта
 */
class JStatisticsController extends JBackendController
{
        /**
         * Метод возвращает массив элементов бокового меню в текущем контроллере
         * 
         * @var array элементы бокового меню
         */
        public $menu=array(
                array('label' => 'География', 'url' => array('/statistics/geography/index')),
                array('label' => 'Мобильные', 'url' => array('/statistics/mobile/index')),
                array('label' => 'Пользователи', 'url' => array('/statistics/users/index')),
        );      
}