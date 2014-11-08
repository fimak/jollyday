<?php

/**
 * Контроллёр работы с сообщениями о спаме
 */
class TopratedController extends JAudithoryController
{  
	/**
	 * Список сообщений о спаме
	 */
	public function actionIndex()
	{
		$model = new RegionForm();
		$model->unsetAttributes();
                
		if(isset($_POST['RegionForm']))
			$model->attributes = $_POST['RegionForm'];

                $users = User::getTopRated($model->id_region);
                
		$this->render('index',array(
			'model' => $model,
                        'users' => $users,
		));
	}
        
        /**
         * Действие занесения/извлечения пользователя из чёрного списка
         * самых популярных пользователей
         * 
         * @param integer $uid ID пользователя
         */
        public function actionBan($uid)
        {
                $user = User::model()->findByPk($uid);
            
                if(!$user)
                        throw new CHttpException('404', 'Not Found');
                            
                if(JTopRatedWidget::isBlacklisted($uid))
                {
                        $status = JTopRatedWidget::unban($uid);
                        $message = 'unbanned';
                }
                else
                {
                        $status = JTopRatedWidget::ban($uid);
                        $message = 'banned';
                }
                
                $status = (boolean)$status;
                $html = '';
                           
                if($status)
                {             
                        Yii::app()->cache->delete('top_rated_users');
                        Yii::app()->cache->delete('top_rated_users'.$user->id_region);
                        Yii::app()->cache->delete('toprated_blacklist');
                        
                        $regionId = isset($_POST['RegionForm']['id_region']) ? $_POST['RegionForm']['id_region'] : false;
                        $users = User::getTopRated($regionId);
                        $html = $this->renderPartial('_photos', array(
                                'users' => $users,
                        ), true);
                }
                
                echo CJSON::encode(array(
                        'status' => $status,
                        'message' => $message,
                        'html' => $html,
                ));
        }
}
