<?php
/**
 * Базовый контроллер модуля сущностей сайта
 */
class JEntityController extends JBackendController
{
        /**
         * Метод возвращает массив элементов бокового меню в текущем контроллере
         * 
         * @var array элементы бокового меню
         */
        public $menu = array(
                array('label' => 'Главная', 'url' => array('/entity/default')),
                array('label' => 'Подарки', 'url' => array('/entity/gift')),
        );
}