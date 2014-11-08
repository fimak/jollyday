<?php
/**
 * Дефолтный контроллер для модуля Entity
 */
class DefaultController extends JEntityController
{
        public function actionIndex()
        {
                $this->render('index');
        }       
}
