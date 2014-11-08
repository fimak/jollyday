<?php

/**
 * Контроллёр рассылки новостей
 */
class NewsController extends JAudithoryController
{  
	/**
	 * Главная страница
	 */
	public function actionIndex()
	{
		// создаём модель 
		$model=new News('search');
                                                 
                // поиск для CGridView
		$model->unsetAttributes();
		if(isset($_GET['News']))
			$model->attributes=$_GET['News'];

		$this->render('index',array(
			'model'=>$model,
		));
	}
        
	/**
	 * Действие создания новости
	 */
	public function actionCreate()
	{
		$model=new News('create');
                               
		if(isset($_POST['News']))
		{
			$model->attributes=$_POST['News'];
                        
                        $imageInstance = CUploadedFile::getInstance($model, 'uploadedFile');
                        
                        if (is_object($imageInstance) && get_class($imageInstance) === 'CUploadedFile')
                        {    
                                $model->uploadedFile = $imageInstance; // присваиваем данные, если все ОК
                                
                                // имя файла с картинкой
                                $imageName = JRandom::md5() . '.' . $model->uploadedFile->extensionName;
                                           
                                $dir = News::getImagePath();
                                
                                if(!is_dir($dir))
                                        mkdir($dir);
                                
                                // путь, где сохранаяется картинка
                                $path =  $dir . $imageName;
                                                               
                                // сохраняем картинку
                                if($model->validate())
                                {
                                        if(!$model->uploadedFile->saveAs($path, false)) 
                                        {                                          
                                                $model->uploadedFile = null;
                                                $model->image = null;
                                                Yii::app()->user->setFlash('error', 'Не удалось сохранить картинку');
                                        }
                                        else
                                        {
                                                $model->image = $imageName;
                                                $imageInstance = Yii::app()->image->load($path);
                                                $imageInstance->smart_resize(Photo::SIZE_MEDIUM_X, Photo::SIZE_MEDIUM_Y)->quality(100)->sharpen(20);
                                                $imageInstance->save($path);
                                        }
                                }
                        }
                        else
                        {
                                Yii::app()->user->setFlash('error', 'Не удалось сохранить картинку');
                                $model->image = null;
                        }
                            
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
                        else
                                if(isset($path))
                                    if(is_file($path))
                                            unlink($path);
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}
        
        /**
         * Действие обновления данных новости
         * 
         * @param integer $id ID новости
         */
        public function actionUpdate($id)
        {
		$model=$this->loadModel($id);
                $model->setScenario('update');

		if(isset($_POST['News']))
		{
			$model->attributes=$_POST['News'];
                        
                        // получаем экземпляр объекта загруженного файла
                        $imageInstance = CUploadedFile::getInstance($model, 'uploadedFile');
                        
                        if (is_object($imageInstance) && get_class($imageInstance) === 'CUploadedFile' && $imageInstance != null)
                        {    
                                $model->uploadedFile = $imageInstance; // присваиваем данные, если все ОК
                                
                                // имя файла с картинкой
                                if($model->image == null)
                                        $imageName = JRandom::md5() . '.' . $model->uploadedFile->extensionName;
                                else
                                        $imageName = $model->image;
                                           
                                $dir = News::getImagePath();
                                
                                if(!is_dir($dir))
                                        mkdir($dir);
                                
                                // путь, где сохранаяется картинка
                                $path =  $dir . $imageName;
                                                               
                                // сохраняем картинку
                                if($model->validate())
                                {
                                        if(!$model->uploadedFile->saveAs($path, false)) 
                                        {                                          
                                                $model->uploadedFile = null;
                                                $model->image = null;
                                                Yii::app()->user->setFlash('error', 'Не удалось сохранить картинку');
                                        }
                                        else
                                        {
                                                $model->image = $imageName;
                                                $imageInstance = Yii::app()->image->load($path);
                                                $imageInstance->smart_resize(Photo::SIZE_MEDIUM_X, Photo::SIZE_MEDIUM_Y)->quality(100)->sharpen(20);
                                                $imageInstance->save($path);
                                        }
                                }
                        }
                        
                        if($model->save())
				$this->redirect(array('view','id'=>$model->id));
                        
		}

		$this->render('update',array(
			'model'=>$model,
		));
        }
        
        /**
         * Дейстыие просмотра новости
         * 
         * @param integer $id ID новости
         */
        public function actionView($id)
        {
		$this->render('view',array(
			'model'=>$this->loadModel($id),
                        'delivery' => new RegionForm,
		));
        }
        
	/**
	 * Действие удаления новости
	 * 
	 * @param integer $id ID удаляемой новости
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}
        

        /**
         * Действие отправляет уведомление выбранному пользователю
         * 
         * @param type $id
         */
        public function actionNotification($id)
        {
                $user = User::model()->findByPk($id);
                
                if(!$user)
                        throw new CHttpException('404', 'Страница не найдена');
            
                $model = new NotificationForm();     
                
                if(isset($_POST['NotificationForm']))
                {
                        $model->attributes = $_POST['NotificationForm'];
                        $model->id_user = $user->id;
                        
                        if($model->validate() && $model->send())
                        {
                                Yii::app()->user->setFlash('success', 'Уведомление успешно отправлено!');
                                $this->redirect(array('/audithory/user/view', 'id' => $model->id_user));
                        }
                        
                }
            
                $this->render('notification', array(
                        'user' => $user,
                        'model' => $model,
                        'id' => $id,
                ));
        }
        
        /**
         * Метод, осуществляющий расслыку новостей
         * 
         * @param integer $id ID новости
         */
        public function actionDelivery($id)
        {             
                // если выбраны отдельные регионы регионы для рассылки новости
                // то формируем строку из ID регионов
                if(isset($_POST['RegionForm']))
                {
                        $form = new RegionForm;
                        $form->attributes = $_POST['RegionForm'];
                        $regionIds = implode(',', $form->id_region);
                }
                                    
                // получаем список ID пользователей для рассылки
                $command = Yii::app()->db->createCommand()
                        ->select('id')
                        ->from('user');
                
                if(isset($regionIds))
                        $command->where("id_region IN ($regionIds) AND fl_deleted = 0 AND register_step = 0");
                else
                        $command->where('fl_deleted = 0 AND register_step = 0');
                              
                $userIds = $command->queryColumn();
                
                // если нет пользователей, удовлетворяющих критерию
                if(!$userIds)
                {
                        echo CJSON::encode(array('result' => 'success', 'count' => 0));
                        Yii::app()->end();
                }
                
                // формируем строку для вставки
                $rowUserNews = array();
                $now = new CDbExpression('NOW()');
                $status = News::STATUS_UNREAD;
                $newsID = $id;
                $type = News::TYPE_TEMPLATED;
                
                foreach($userIds as $userID)
                        $rowUserNews[] = '('.implode(',', array($newsID, $userID, $now, $status, $type)).')';
                
                $string = implode(',', $rowUserNews);
                
                // вставляем строки
                $sql = "INSERT INTO `im_user_news` (`id_news`, `id_user`, `date`, `status`, `type`) VALUES $string";
                $countInsertedRows = Yii::app()->db->createCommand($sql)->execute();
                         
                echo CJSON::encode(array('result' => 'success', 'count' => $countInsertedRows));        
        }
        
        
	/**
	 * Метод загружает модель новости по её ID
         * 
	 * @param integer ID новости
	 */
	public function loadModel($id)
	{
		$model=News::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'Страница не существует.');
		return $model;
	}
}