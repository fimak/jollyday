<?php

/**
 * Базовый контроллер модуля Georaphy
 */
class JGeographyController extends JBackendController
{
        /**
         * Метод возвращает массив элементов бокового меню в текущем контроллере
         * 
         * @var array элементы бокового меню
         */
        public $menu=array(
                array('label'=>'Главная', 'url'=>array('/geography/default')),
                array('label'=>'Регионы', 'url'=>array('region/index')),
                array('label'=>'Города', 'url'=>array('city/index')),
        );               
}

