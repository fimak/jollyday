<?php

/**
 * Контроллер работы с таблицей пользователей сайта
 */
class UserController extends JAudithoryController
{
        /**
         * В методе описаны подключаемые типовые действия
         * 
         * @return array массив с настройками действий
         */
        public function actions()
        {
                return array(
                        'loadCities'=>array(
                                'class'=>'application.actions.JDropDownCities',
                                'placeHolder' => 'Выберите город',
                                'fieldName' => 'id_region',
                                'responseName' => 'dropDownCities',
                        ),
                );
        }     

        /**
         * Действие показывает данные модели
         * 
         * @param integer $id ID модели пользователя
         */
        public function actionView($id)
        {
                $model = $this->loadModel($id);
            
                $this->render('view',array(
                        'model' => $model,
                        'isTrBlacklisted' => JTopRatedWidget::isBlacklisted($model->id),
                ));
        }

        /**
         * Действие создания нового пользователя
         */
        public function actionCreate()
        {
                $model=new User('create');

                // отключаем поведение              
                $model->disableBehavior('ActiveRecordStaticInteractionBehavior');

                if(isset($_POST['User']))
                {
                        $model->attributes=$_POST['User'];
                        if($model->save())
                                $this->redirect(array('view','id'=>$model->id));
                }

                $this->render('create',array(
                        'model'=>$model,
                ));
        }

        /**
         * Действие обновления модели
         * 
         * @param integer $id ID модели
         */
        public function actionUpdate($id)
        {
                $model=$this->loadModel($id);
                $model->setScenario('update');

                if(isset($_POST['User']))
                {
                        $model->attributes=$_POST['User'];
                        if($model->save())
                                $this->redirect(array('view','id'=>$model->id));
                }

                $this->render('update',array(
                        'model'=>$model,
                ));
        }

        /**
         * Действие удаления модели пользователя
         * 
         * @param integer $id ID модели
         */
        public function actionDelete($id)
        {
                $this->loadModel($id)->delete();

                if(!isset($_GET['ajax']))
                        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        }

        /**
         * Действие вывода списка пользователей
         */
        public function actionIndex()
        {
                // создаём модель 
                $model=new User('search');

                // поиск для CGridView
                $model->unsetAttributes();
                if(isset($_GET['User']))
                        $model->attributes=$_GET['User'];
                
                $model->id_city = isset($_GET['User']['id_city']) ? $_GET['User']['id_city'] : '';
                           
                $this->render('index',array(
                        'model'=>$model,
                ));
        }

        /**
         * Метод загружает модель пользователя по его ID
         * 
         * @param integer ID пользователя
         */
        public function loadModel($id)
        {
                $model=User::model()->findByPk($id);
                if($model===null)
                        throw new CHttpException(404,'The requested page does not exist.');
                return $model;
        }

        /**
         * Метод загружает таблицу с историей измений данных пользователя
         * 
         * @param type $id ID пользорвателя
         */
        public function actionLoadHistory($id)
        {
                $model=new History('search');
                $model->unsetAttributes();  // clear any default values
                if(isset($_GET['History']))
                        $model->attributes=$_GET['History'];

                $this->renderPartial('_history', array(
                        'model' => $model,
                        'userId' => $id,
                ), false, true);
        }

        /**
         * Метод загружает сообщения пользователя
         * 
         * @param integer $id ID полльзователя
         */
        public function actionLoadMessages($id)
        {
                $model = new Message('search');

                $model->unsetAttributes();
                if(isset($_GET['Message']))
                        $model->attributes=$_GET['Message'];
                
                $this->renderPartial('_messages', array(
                        'model' => $model,
                        'userId' => $id,
                ), false, true);
        }
        
        /**
         * Метод загружает фотоальбом пользователя
         * 
         * @param integer $id ID пользователя
         */
        public function actionLoadAlbum($id)
        {
                $photos = Photo::getUserPhotos($id);
                
                $this->renderPartial('_album', array(
                        'photos' => $photos,
                        'userId' => $id,
                ));
        }
        
        /**
         * Метод удаляет фотографию пользователя
         * 
         * @param integer $id ID фотографии
         */
        public function actionDeletePhoto($id)
        {
                $model = Photo::model()->findByPk($id);
                
                if(empty($model))
                        throw new CHttpException('404', 'Страница не существует');
                
                $model->setScenario('backend-delete');
                
                echo CJSON::encode(array(
                        'status' => $model->delete(),
                ));
        }

        /**
         * Действие изменения статуса бана пользователя
         * 
         * @param integer $id ID пользователя
         */
        public function actionBan($id)
        {
                $model = User::id($id);

                $model->fl_banned = $model->fl_banned == 0 ? 1 : 0;
                $model->fl_deleted = $model->fl_banned;
     
                $criteria = new CDbCriteria;
                $criteria->compare('id', $id);
                $updated = Yii::app()->db->commandBuilder->createUpdateCommand('user', array(
                    'fl_banned' => $model->fl_banned,
                    'fl_deleted' => $model->fl_deleted,
                ), $criteria)->execute();
                        
                if($updated)
                {
                        $message = $model->fl_banned == 1 
                                ? 'Пользователь забанен'
                                : 'С пользователя снят бан';
                        $status = 'success';
                }
                else
                {
                        $message = 'Произошла ошибка';
                        $status = 'error';
                }

                Yii::app()->user->setFlash($status, $message);

                $this->redirect(array('user/view', 'id' => $model->id));
        }
}
