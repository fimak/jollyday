<?php
/**
 * Базовый контроллер для модуля регистрации
 */
class JRegisterController extends JController
{
	/**
	 * @var string лейаут контроллера
         */
	public $layout = '//layouts/main';
        
        /**
         * Метод возвращает массив элементов бокового меню в текущем контроллере
         * 
         * @var array элементы бокового меню
         */
	public $menu = array();
        
	/**
	 * @var array массив хлебных крошек
	 */
	public $breadcrumbs=array();
}