<?php

/**
 * Дефолтный контроллер модуля администрирования
 */
class DefaultController extends JBackendController
{   
        public function actions()
        {
                return array(
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
                                'foreColor' => 0x000000,
                                'maxLength'=> 8,
                                'minLength'=> 8,
                                'testLimit'=> 1,
                                'width' => 150,
                                'padding' => 0,
                                'offset' => 5,
                                'backend' => 'gd'
			),
                        'cities'=>array(
                                'class'=>'application.actions.JDropDownCities',
                                'placeHolder' => 'Все города',
                                'fieldName' => 'id_region',
                                'responseName' => 'dropDownCities',
                        ),
                );
        }
    
	/**
	 * Действие вывода ошибки
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Действие входа в админку
	 */
	public function actionLogin()
	{
		$model=new BackendLoginForm;

                $this->layout = '//layouts/white';
                
		if(isset($_POST['BackendLoginForm']))
		{
			$model->attributes=$_POST['BackendLoginForm'];
			// проверка введенных данных на валидноть
			if($model->validate() && $model->login())
                        {
                                $message = '<strong>'.CHtml::encode(Yii::app()->user->getRealName()).'</strong>, ';
                                $message .= 'добро пожаловать в панель управления сайтом JollyDay!';
                                Yii::app()->user->setFlash('info', $message);
				$this->redirect(array('default/index'));                                    
                        }
		}
		$this->render('login',array('model'=>$model));
	}
        
	/**
	 * Действие разлогинивания администратора
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}          

        /**
         * Действие вывода главной страницы админки
         */
        public function actionIndex()
        {
                $this->render('index');
        }
}
