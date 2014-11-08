<?php
/**
 * Контроллер анкеты пользователя
 */
class QuestionaryController extends JAppController
{  
        /**
         * Действие редактирования анкеты пользователя
         */
        public function actionUpdate()
        {       
                $criteria = new CDbCriteria;
 
                $user = User::model()->findByPk(Yii::app()->user->id);
                $profile = $user->profile;
                      
                if(isset($_POST['Profile']))
                {
                        $profile->attributes = $_POST['Profile'];
                                          
                        if($profile->save())
                        {                                                   
                                // обновляем модель профиля с учётом сохраненных данных
                                $profile->refresh();
                                $user->profile = $profile;
                                
                                $message = "Изменения сохранены";
                                $status = 'success';
                                $html = $this->renderPartial('theme.views.app.profile._left', array(
                                        'user' => $user,
                                        'methodList' => JMeetmethod::getData(),
                                        'profileType' => 'own'
                                ), true);
                        }
                        else
                        {
                                $message = "Не удалось сохранить изменения";
                                $status = 'error';
                                $html = null;
                        }
                        
                        $errors = $profile->getErrors();                        
                        echo CJSON::encode(array('status' => $status, 'message' => $message, 'html' => $html, 'errors' => $errors));
                        Yii::app()->end();                       
                }                
                
                $this->renderPartial('_form', array(
                        'profile' => $profile,
                        'userID' => $user->id,
                ),false,true);
                
                Yii::app()->end();
        }                          
} 



