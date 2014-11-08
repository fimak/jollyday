<?php
/**
 * Базовый контроллер модуля зарегистрированного пользователя
 */
class JAppController extends JController
{
        /** 
         * @var string лейаут контроллера 
         */
	public $layout='//layouts/app';

        /** 
         * @var boolean отображать ли мордроленту на странице 
         */
        public $faceribbonEnable = true;
        
        public $bonusWidgetEnable = true;
        
        /** 
         * @var boolean отображать ли только один пункт меню сайта- "выход" 
         */
        public $onlyLogoutMenuItem = false;
             
        /**
         * Метод возвращает массив элементов бокового меню в текущем контроллере
         * 
         * @var array элементы бокового меню
         */
        public $menu=array();
}