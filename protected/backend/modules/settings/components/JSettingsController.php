<?php
/**
 * Базовый контроллер модуля настроек сайта
 */
class JSettingsController extends JBackendController
{
        /**
         * Метод возвращает массив элементов бокового меню в текущем контроллере
         * 
         * @var array элементы бокового меню
         */
        public $menu=array(
                array('label'=>'Основные настройки', 'url'=>array('/settings/default')),
                array('label'=>'Пагинация', 'url'=>array('/settings/default/pagination')),
                array('label'=>'Администраторы', 'url'=>array('/settings/admin')),
        );      
}