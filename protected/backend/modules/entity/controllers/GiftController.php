<?php
/**
 * Контроллер управления подарками
 */
class GiftController extends JEntityController
{
	/**
	 * Действие обновления модели подарка
	 * 
	 * @param integer $id ID модели подарка
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
                $model->setScenario('update');

		if(isset($_POST['Gift']))
		{
			$model->attributes=$_POST['Gift'];
                        
                        // получаем объекты загруженных файлов
                        $imageInstance = CUploadedFile::getInstance($model, 'uploadedFile');
                        $imageInstanceBig = CUploadedFile::getInstance($model, 'uploadedFileBig');
                          
                        if ($imageInstance != null)
                        {
                                $model->uploadedFile = $imageInstance;
                                $model->image = $model->title . '.' . $model->uploadedFile->extensionName;
                                
                        }
                        if ($imageInstanceBig != null)
                        {
                                $model->uploadedFileBig = $imageInstanceBig;
                                $model->image_big = $model->title . Gift::BIG_SUFFIX . '.' . $model->uploadedFileBig->extensionName;
                        }
                                     
			if($model->save())
                        {                               
				Yii::app()->user->setFlash('success', 'Изменения сохранены');
                                Yii::app()->cache->delete(Gift::LIST_CACHE_ID);
                                $this->refresh();
                        }
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Действие удаления подарка
	 * 
	 * @param integer $id ID удаляемого подарка
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

                Yii::app()->cache->delete(Gift::LIST_CACHE_ID);
                
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}

	/**
	 * Действие отображения страницы, где можно просмотреть и добавить
         * подарок
	 */
	public function actionIndex()
	{
                // создаём модель для cgridview
		$model=new Gift('search');
                
                // создаём модель для формы
                $new = new Gift;
                $new->setScenario('create');
                
                // если форма отправлена то пробуем создать запись в бд
                if(isset($_POST['Gift']))
                {
			$new->attributes=$_POST['Gift'];
                        
                        // получаем объекты загруженных файлов
                        $imageInstance = CUploadedFile::getInstance($new, 'uploadedFile');
                        $imageInstanceBig = CUploadedFile::getInstance($new, 'uploadedFileBig');
                          
                        if ($imageInstance != null)
                        {
                                $new->uploadedFile = $imageInstance;
                                $new->image = $new->title . '.' . $new->uploadedFile->extensionName;
                                
                        }
                        if ($imageInstanceBig != null)
                        {
                                $new->uploadedFileBig = $imageInstanceBig;
                                $new->image_big = $new->title . Gift::BIG_SUFFIX . '.' . $new->uploadedFileBig->extensionName;
                        }
                                     
			if($new->save())
                        {                               
				Yii::app()->user->setFlash('success', 'Изменения сохранены');
                                Yii::app()->cache->delete(Gift::LIST_CACHE_ID);
                                $this->refresh();
                        }
                }        
                
                // поиск для CGridView
		$model->unsetAttributes();
		if(isset($_GET['Gift']))
			$model->attributes=$_GET['Gift'];

		$this->render('index',array(
			'model'=>$model,
                        'new' => $new,
		));
	}

	/**
	 * Метод загружает модель подарка по его ID, если подарок не существует, 
         * то кидает исключение
	 * 
	 * @param integer ID подарка
	 */
	public function loadModel($id)
	{
		$model=Gift::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'Странница не существует.');
		return $model;
	}    
}