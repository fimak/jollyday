<?php
/**
 * Базовый контроллер модуля работы с аудиторией сайта
 */
class JAudithoryController extends JBackendController
{
        /**
         * Метод возвращает массив элементов бокового меню в текущем контроллере
         * 
         * @var array элементы бокового меню
         */
        public $menu = array(
                array('label' => 'Главная', 'url' => array('/audithory/default/index')),
                array('label' => 'Пользователи', 'url' => array('/audithory/user/index')),
                array('label' => 'Спам', 'url' => array('/audithory/spam/index')),
                array('label' => 'Новости', 'url' => array('/audithory/news/index')),
                array('label' => 'Служба поддержки', 'url' => array('/audithory/feedback/index')),
                array('label' => 'Самые популярные', 'url' => array('/audithory/toprated/index')),
        );  
}