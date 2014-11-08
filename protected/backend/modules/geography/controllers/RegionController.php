<?php
/**
 * Контроллер для администрирования регионов
 */
class RegionController extends JGeographyController
{
	/**
	 * Действие обновления данных модели
         * 
	 * @param integer $id ID обновляемой модели региона
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		if(isset($_POST['Region']))
		{
			$model->attributes=$_POST['Region'];
			if($model->save())
                        {       
                                Yii::app ()->user->setFlash('success', 'Изменения сохранены');
                                $this->refresh();	
                        }
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Действие удаления модели
         * 
	 * @param integer $id ID удаляемой модели
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}

	/**
	 * Действие отображения списка регионов
	 */
	public function actionIndex()
	{
                // создаём модель 
		$model=new Region('search');
                
                // создаём модель справочника
                $new = new Region;
                
                // если форма отправлена то пробуем создать запись в бд
                if(isset($_POST['Region']))
                {
                        $new->attributes = $_POST['Region'];                       
                        if ($new->save())
                        {       Yii::app()->user->setFlash('success', 'Регион создан');
                                $this->refresh();
                        }
                }        
                
                // поиск для CGridView
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Region']))
			$model->attributes=$_GET['Region'];

		$this->render('index',array(
			'model'=>$model,
                        'new' => $new,
		));
	}

	/**
	 * Загружает модель по его ID
         * 
	 * @param integer ID модели
	 */
	public function loadModel($id)
	{
		$model=Region::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
