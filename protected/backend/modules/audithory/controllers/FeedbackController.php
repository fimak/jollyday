<?php

/**
 * Контроллёр обратной связи
 */
class FeedbackController extends JAudithoryController
{  
	/**
	 * Главная страница
	 */
	public function actionIndex()
	{
		$model = new Feedback('search');
                
		$model->unsetAttributes();
		if(isset($_GET['Feedback']))
			$model->attributes=$_GET['Feedback'];
            
                $this->render('index', array(
                        'model' => $model
                ));
	}
        
	/**
	 * Главная страница
	 */
	public function actionView($id)
	{
		$model = $this->loadModel($id);
                $model->setScenario('answer');
                
                if(isset($_POST['Feedback']))
                {
                        $model->attributes = $_POST['Feedback'];
                        
                        if($model->save() && JMail::supportMail($model->email, $model->answer, $model->mailSubject))
                        {
                                Yii::app()->user->setFlash('success','Ответ отправлен пользователю на email');
                                $this->refresh();
                        }
                }
                else
                {
                        $model->mailSubject = "Re: " . Feedback::getShortSubjectDescription($model->subject);
                }
                            
                $this->render('answer', array(
                        'model' => $model
                ));
	}        
        
	/**
	 * Дейтвие удаления сообщения обратной связи.
         * 
	 * @param integer $id ID модели сообщения
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}
        
        /**
         * Массовая обработка сообщений
         * 
         * @param string $action действие
         */
        public function actionBulk($action)
        {
                switch($action)
                {
                        case 'delete':
                                if(isset($_POST['feedback-grid_c0']))
                                        $result = Feedback::model()->deleteByPk($_POST['feedback-grid_c0']);     
                                break;
                        default:
                                break;
                }
        }
        
        /**
         * Действие вывода переписки с пользователем
         * 
         * @param integer $id ID сообщения
         */
        public function actionAnswer($id)
        {
                $model = $this->loadModel($id);
            
                $this->render('answer', array(
                        'model' => $model
                ));
        }
        
        /**
	 * Метод загружает модель обратной связи по её ID
         * 
	 * @param integer ID новости
	 */
	public function loadModel($id)
	{
		$model= Feedback::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'Страница не существует.');
		return $model;
	}
}