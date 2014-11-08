<?php

class RegisterModule extends CWebModule
{
        /**
         * @var string ID контроллера по-умолчанию
         */
        public $defaultController = 'step';
    
        /**
         * Инициализация модуля
         */
	public function init()
	{
		$this->setImport(array(
			'register.components.*',
                        'register.models.*',
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
                        if($controller->id != 'ajax')
                                return true;

                        if($action->id == 'loadCities')
                                return true;

                        if(!Yii::app()->user->isGuest)
                                JController::jsRedirect(J::url('/app/profile/index'));
                        
                        return true;
                }
                else
                {
                        return false;
                }
        }
}
