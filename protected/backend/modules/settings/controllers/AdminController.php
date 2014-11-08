<?php
/**
 * Контроллер управления администраторами сайта
 */
class AdminController extends JSettingsController
{
        /**
         * Действие просмотра таблицы администраторов
         */
	public function actionIndex()
	{
		// создаём модель 
		$model=new Admin('search');
                                            
                // поиск для CGridView
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Admin']))
			$model->attributes=$_GET['Admin'];

		$this->render('index',array(
			'model'=>$model,
		));
	}
        
	/**
	 * Действие просмотра данных администратора.
         * 
	 * @param integer $id ID модели
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}
        
	/**
	 * Действие создания нового администратора
	 */
	public function actionCreate()
	{
		$model=new Admin('create');
                              
		if(isset($_POST['Admin']))
		{ 
			$model->attributes=$_POST['Admin'];
       
			if($model->save())
                        {                            
                                Yii::app()->user->setFlash('success', 'Администратор создан');
				$this->redirect(array('view','id'=>$model->id));
                        }
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}
        
	/**
	 * Действие обновления модели
	 * 
	 * @param integer $id ID модели
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
                
                $currentPassword = $model->password;
                
                $model->setScenario('update');

		if(isset($_POST['Admin']))
		{
			$model->attributes=$_POST['Admin'];
                        
                        if($model->password != '')
                                $model->isNewPassword = true;
                        else
                                $model->password = $currentPassword;
                        
			if($model->save())
                        {
                                Yii::app()->user->setFlash('success', 'Изменения сохранены');
				$this->redirect(array('view','id'=>$model->id));
                        }
		}

                // не выводим пользователю хеш пароля
                $model->password = null;
                
		$this->render('update',array(
			'model'=>$model,
		));
	}

        /**
         * Действие удаления модели
         * 
         * @param integer $id ID модели
         */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}
        
        /**
         * Метод загружает модель администратора по его ID
         * 
         * @param integer $id ID Администратора
         * @return type Admin модель администратора
         */
	public function loadModel($id)
	{
		$model=Admin::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'Страница не существует.');
		return $model;
	}        
        
        
}