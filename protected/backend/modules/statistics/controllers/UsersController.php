<?php
/**
 * Контроллер статистики сайта
 */
class UsersController extends JStatisticsController
{
        /**
         * Действие вывода и сохранения основных настроек сайта
         */
	public function actionIndex()
	{                                            
		$this->render('index', array(

                ));
	}
}