<?php
/**
 * Дефолтный контроллер для модуля audithory
 */
class DefaultController extends JAudithoryController
{
        /**
         * Действие отображения главной страницы модуля
         */
        public function actionIndex()
        {
                $this->render('index');
        }       
}
