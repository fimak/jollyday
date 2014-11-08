<?php

/**
 * Контроллер фотографий
 *
 */
class ServiceController extends JAppController
{       
        public $faceribbonEnable = false;
    
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
                        {
                                $this->layout = '//layouts/error404';
				$this->render('error', $error);
                        }
		}
	} 
}
?>
