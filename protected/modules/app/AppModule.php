<?php
/**
 * Модуль зарагистрированного пользователя
 */
class AppModule extends CWebModule
{
        /**
         * @var string ID контроллера по умолчаню
         */
        public $defaultController = 'profile';
        
        /**
         * Инициализация модуля
         */
	public function init()
	{
		$this->setImport(array(
			'app.models.form.*',
			'app.models.ar.*',                    
			'app.components.*',
                        'app.controllers',
		));
                
                // выставляем тему модуля и алиас пути до неё
                Yii::app()->theme = 'jolly';
                
                Yii::setPathOfAlias('theme', Yii::app()->theme->basePath);
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
                if(!Yii::app()->user->id || Yii::app()->user->role == User::ROLE_GUEST)
                {                  
                        // модуль доступен только авторизованным пользователям
                        Yii::app()->user->logout();
                        Yii::app()->controller->redirect(array('/site/index'));
                        return true;
                }
            
		if(parent::beforeControllerAction($controller, $action))
		{       
                        // действия, разрешённые всем                  
                        switch($action->id)
                        {
                                case 'logout' :
                                case 'loadCities' :
                                        return true;
                        }
                        
                        // не разрешать доступ к пройденным шагам регистрации
                        if(!Yii::app()->user->isGuest && Yii::app()->user->getRegisterStep() == 0)
                        {
                                switch($action->id)
                                {
                                        case 'personal' : 
                                        case 'meetingway' :
                                        case 'questionary' :
                                                Yii::app()->controller->redirect(array('/app/profile/index'));
                                                return true;
                                        default:
                                                break;
                                }
                        }
                        
                        if(!Yii::app()->user->isGuest && Yii::app()->user->isBanned()
                                && $controller->id == 'profile' && $action->id == 'banned')
                        {
                                $controller->faceribbonEnable = false;
                                $controller->bonusWidgetEnable = false;
                                $controller->onlyLogoutMenuItem = true;
                                return true;
                        }
                        
                        // удалённым юзерам редирект на страницу восстановления анкеты
                        if(!Yii::app()->user->isGuest && Yii::app()->user->isDeleted()
                                && $controller->id == 'profile' && $action->id == 'recovery')
                                return true;
                                        
                        if(!Yii::app()->user->isGuest && Yii::app()->user->isDeleted())
                        {
                                $controller->faceribbonEnable = false;
                                $controller->bonusWidgetEnable = false;
                                $controller->onlyLogoutMenuItem = true;
                                Yii::app()->controller->redirect(array('profile/recovery'));
                                return true;
                        }
                        if(Yii::app()->user->getRegisterStep() == 2 && $action->id != 'personal')
                        {
                                Yii::app()->controller->redirect(array('register/personal'));
                                return true;
                        }
                                           
                        if(Yii::app()->user->getRegisterStep() == 3 && $action->id != 'meetingway')
                        {
                                Yii::app()->controller->redirect(array('register/meetingway'));
                                return true;
                        }
                        
                        if(Yii::app()->user->getRegisterStep() == 4 && $action->id != 'questionary')
                        {
                                Yii::app()->controller->redirect(array('register/questionary'));
                                return true;
                        }
                                          
			return true;
		}
		else
			return false;
	}
}
