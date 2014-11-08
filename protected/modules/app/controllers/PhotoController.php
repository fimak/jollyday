<?php

/**
 * Контроллер фотографий
 *
 */
class PhotoController extends JAppController
{
        /**
         * Действие вывода формы для загрузки фотографий
         */
        public function actionUploader()
        {           
                $model=new Photo;
                $photos = User::getPhotos(Yii::app()->user->id);
                
                $this->render('uploader', array(
                        'model' => $model,
                        'photos' => $photos,
                ));  
        }
        
        /**
         * Действие загрузки фотографии
         */
        public function actionUpload()
        {
                $model=new Photo;
                            
                if(isset($_POST['Photo']))
                {       
                        $model->file = CUploadedFile::getInstance($model,'file');
                        $result = array();

                        if($model->validate())
                        {
                                // нарезаем изображения различных размеров
                                if($model->createThumbnails() && $model->save(false))
                                {   
                                        if(Yii::app()->user->getUserpicID() == null)
                                                $model->setUserpic();
                                        $this->renderPartial('theme.views.app.photo._photo', array(
                                                'url' => Photo::getUploadFolderURL() . $model->filename_medium,
                                                'id' => $model->id,
                                        ));
                                }
                        }
                }
        }
        
        /**
         * Действие убирает аватраку текущего пользователя
         */
        public function actionUnset()
        {         
                if(Photo::unsetUserpic())
                        $result = 'success';
                else
                        $result = 'fail';
                    
                echo CJSON::encode(array('result' => $result));
        }
        
        /**
         * Действиеустанавливает в качестве аватарки выбранную фотографию
         * 
         * @param integer $id ID фотографии
         */
        public function actionSet($id)
        {
                $photo = Photo::model()->findByPk($id);
                
                if($photo)
                {
                        $photo->setUserpic();
                        $result = 'success';
                }
                else
                {
                        $result = 'fail';
                }
                
                echo CJSON::encode(array('result' => $result));
        }
        
        /**
         * Действие подгрузки фотоальбома текущего пользователя
         * для редактирования 
         */
        public function actionAlbum()
        {
                $photos = User::getPhotos(Yii::app()->user->id);
                
                $this->renderPartial('_album', array(
                        'photos' => $photos,
                        'photosCount' => count($photos),
                ));
        }
        
        /**
         * Действие удаления фотографии
         * 
         * @param integer $id ID фотографии
         */
        public function actionDelete($id)
        {
                $model = Photo::model()->findByPk($id);
                
                if($model == null)
                        throw new CHttpException('404', 'Страница не существует');

                if($model->id_user == Yii::app()->user->id)
                {
                        $result['success'] = $model->delete();
                        if(Yii::app()->user->getUserpicID() === $model->id)
                        {
                                $result['userpic'] = true;
                                $firstUserpicId = Yii::app()->db->createCommand()
                                        ->select('id')
                                        ->from('photo')
                                        ->where('id_user = :userId', array('userId' => $model->id_user))
                                        ->limit(1)
                                        ->order('id ASC')
                                        ->queryScalar();
                                
                                $result['userpic_id'] = $firstUserpicId;
                                
                                if($firstUserpicId)
                                {
                                        Yii::app()->db->createCommand()
                                                ->update('user', array(
                                                        'id_userpic' => $firstUserpicId
                                                ),
                                                'id = :userID',
                                                array(
                                                        'userID' => $model->id_user
                                                )
                                        );
                                }
                        }
                }
                else
                {    
                        $result['success'] = false;
                }
                echo CJSON::encode($result);            
        }         
}
?>
