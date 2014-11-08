<?php
/**
 * Класс модуля географии пользователей
 */
class GeographyModule extends CWebModule
{
        /**
         * @var string ID контроллёра по умолчанию
         */
        public $defaultController = 'default';
    
        /**
         * Инициализация модуля
         */
	public function init()
	{
		$this->setImport(array(
			'geography.models.*',
			'geography.components.*',
                        'geography.controllers.*'
		));
	}

        /**
         * Событие происходящее перед запуском действия в контроллере
         * 
         * @param CController $controller запускаемый контроллер
         * @param CAction $action запускаемое действие
         * @return boolean результат выполнеия события
         */
	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			return true;
		}
		else
			return false;
	}
}
