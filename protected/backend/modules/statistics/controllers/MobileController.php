<?php
/**
 * Контроллер статистики сайта
 */
class MobileController extends JStatisticsController
{
        /**
         * Действие страницы статистики по мобильным телефонам
         */
        public function actionIndex()
        {                
                $model = new RegionForm();
                
                if(isset($_POST['RegionForm']))
                        $model->attributes = $_POST['RegionForm'];
                
                $this->render('index', array(
                        'itemsProvider' => JStat::getLastDigitProvider($model->id_region, $model->id_city),
                        'model' => $model,
                ));
        }  
}