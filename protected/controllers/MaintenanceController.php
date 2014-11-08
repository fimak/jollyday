<?php
/**
 * Контроллер, работающий в режиме техобслуживания
 */
class MaintenanceController extends JController
{
        /** 
         * @var string лейаут контроллера 
         */
        public $layout = '//layouts/clear';
    
        /**
         * Действие вывода страницы с оповещением о работах на сайте
         */
        public function actionIndex()
        {
                $this->render('index');
        }
}
