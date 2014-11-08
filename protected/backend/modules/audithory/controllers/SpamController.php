<?php

/**
 * Контроллёр работы с сообщениями о спаме
 */
class SpamController extends JAudithoryController
{  
	/**
	 * Дейтвие удаления сообщения о спаме.
         * 
	 * @param integer $id ID модели сообщения о спаме
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
        
        public function actionBulk($action)
        {
                switch($action)
                {
                        case 'delete':
                                if(isset($_POST['spam-grid_c0']))
                                        $result = Spam::model()->deleteByPk($_POST['spam-grid_c0']);                        
                                break;
                        default:
                                break;
                }
        }

	/**
	 * Список сообщений о спаме
	 */
	public function actionIndex()
	{
		$model=new Spam('search');
		$model->unsetAttributes();
		if(isset($_GET['Spam']))
			$model->attributes=$_GET['Spam'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
         * Метод возвращает модель пользователя с заданным первичным ключом
         * 
	 * @param integer ID модели
	 */
	public function loadModel($id)
	{
		$model=Spam::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'Страница не существует.');
		return $model;
	}
}
