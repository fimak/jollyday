<?php
/**
 * Контроллер статистики сайта
 */
class GeographyController extends JStatisticsController
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