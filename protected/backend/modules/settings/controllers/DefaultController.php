<?php
/**
 * Контроллер настроек сайта
 */
class DefaultController extends JSettingsController
{
        /**
         * Действие вывода и сохранения основных настроек сайта
         */
	public function actionIndex()
	{
                $model = new MainSettingsForm();
                $maintenanceForm = new MaintenanceSettingsForm();
                $maintenanceForm->getSettings();
                
                if(isset($_POST['MainSettingsForm']))
                {
                        $model->attributes = $_POST['MainSettingsForm'];
                        
                        if($model->validate())
                        {
                                $model->saveSettings();
                                Yii::app()->user->setFlash('info', 'Настройки сохранены');
                                $this->refresh(); 
                        }
                }
                else
                {
                        $model->getSettings();
                }
            
                $moscowTimeZone = new DateTimeZone('Europe/Moscow');
                $moscowTime = new DateTime();
                $moscowTime->setTimezone($moscowTimeZone);
                
                
		$this->render('index', array(
                        'model' => $model,
                        'maintenanceForm' => $maintenanceForm,
                        'jsNowDate' => $moscowTime->format('Y, n-1, j, G, i+1'),
                ));
	}
        
        /**
         * Действие включает/выключает сайт для пользователей
         */
        public function actionMaintenance()
        {
                // получаем значение настройки о режиме техобслуживания
                $status = Yii::app()->settings->get('SiteAccess', 'maintenanceMode');
                          
                // установка метки о техобслуживании
                Yii::app()->settings->set('SiteAccess','maintenanceMode', !$status);
                
                // формирование сообщения
                $message = !$status ? 'Сайт выключен' : 'Сайт включен';
                $result = !$status ? 'warning' : 'success';           
                Yii::app()->user->setFlash($result, $message);
                
                // редирект на страницу настроек
                $this->redirect(array('index'));
        }
        
        public function actionMaintenanceAlert()
        {
                if(isset($_POST['MaintenanceSettingsForm']))
                {
                        $model = new MaintenanceSettingsForm();
                        $model->attributes = $_POST['MaintenanceSettingsForm'];
                        $model->saveSettings();
                        
                        echo CJSON::encode(array(
                                'status' => 'success',
                                'message' => 'Уведомление о скором техобслуживании ' . ($model->enable == 1 ? 'включено' : 'выключено'),
                                'value' => $model->enable,
                        ));
                }
                else
                        echo CJSON::encode(array(
                                'status' => 'error',
                                'message' => 'Ошибка',
                        ));
        }
        
        /**
         * Действие вывода и сохранения настроек пагинации
         */
        public function actionPagination()
        {
                $model = new PaginationSettingsForm;
            
                if(isset($_POST['PaginationSettingsForm']))
                {
                        $model->attributes = $_POST['PaginationSettingsForm'];
                        
                        if($model->validate())
                        {
                                $model->saveSettings();
                                Yii::app()->user->setFlash('info', 'Настройки сохранены');
                                $this->refresh(); 
                        }
                }
                else
                {
                        $model->getSettings();
                }
                
                $this->render('pagination', array(
                        'model' => $model,
                ));
        }
}