<?php
/**
 * Контроллер администрирования городов
 */
class CityController extends JGeographyController
{
	/**
	 * Действие обновления модели города
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		

		if(isset($_POST['City']))
		{
			$model->attributes=$_POST['City'];
			if($model->save())
                        {       
                                Yii::app()->user->setFlash('success', 'Изменения сохранены');
                                $this->refresh();	
                        }
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Действие удаляет модель
	 * @param integer $id ID удаляемой модели
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}

	/**
	 * Действие вывода таблицы городов
	 */
	public function actionIndex()
	{
                // создаём модель 
		$model=new City('search');
                
                // создаём модель справочника
                $new = new City;
                
                // если форма отправлена то пробуем создать запись в бд
                if(isset($_POST['City']))
                {
                        $new->attributes = $_POST['City'];                       
                        if ($new->save())
                        {
                                Yii::app()->user->setFlash('success', 'Город создан');
                                $this->refresh();
                        }
                }        
                
                // поиск для CGridView
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['City']))
			$model->attributes=$_GET['City'];

		$this->render('index',array(
			'model'=>$model,
                        'new' => $new,
		));
	}

	/**
	 * Метод загружает модель с указанным 
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=City::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
