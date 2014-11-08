<?php
/**
 * Базовый контроллер админки
 */
class JBackendController extends JController
{
        /** 
         * @var string лейаут контроллера 
         */
	public $layout='//layouts/admin';

        /** 
         * @var array массив элементов бокового меню 
         */
        public $menu=array();
        
        /** 
         * @var array массив элементов бокового подменю 
         */
        public $submenu=array();
        
        /** 
         * @var array навигация
         */
        public $breadcrumbs=array();
        
        /**
         * В методе описаны фильтры контроллера
         * 
         * @return array массив с описанием фильтров
         */
	public function filters()
	{
		return array(
			'accessControl', 
		);
	}

	/**
	 * Метод определяет правила доступа
         * 
	 * @return array массив правил доступа
	 */
	public function accessRules()
	{
                return array(
                        array('allow',
                                'actions'=>array('captcha', 'error', 'login'),
                                'users'=>array('*'),
                        ),
                        array('allow',
                                'roles'=>array('admin'),
                        ),
                        array('deny',
                                'users'=>array('*'),
                        ),
                );
	}     
}
