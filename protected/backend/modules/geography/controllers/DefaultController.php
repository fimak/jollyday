<?php
/**
 * Дефолтный контроллер для модуля Geography
 */
class DefaultController extends JGeographyController
{
        public function actionIndex()
        {
                $this->render('index');
        }       
}
