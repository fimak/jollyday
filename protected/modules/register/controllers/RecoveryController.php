<?php

/**
 * Контроллер востановления аккаунта пользователя
 */
class RecoveryController extends JRegisterController
{
        /**
         * В методе описаны фильтры контроллера
         * 
         * @return array массив с описанием фильтров
         */
        public function filters()
        {
                return array(
                );
        }
    
        /**
         * Действие вывода и обработки формы восстановления аккаунта
         */
        public function actionIndex()
        {
                $model = new RecoveryForm; 
                
                if(isset($_POST['RecoveryForm']))
                {
                        $model->attributes = $_POST['RecoveryForm'];
                        
                        // если все данные введены верно
                        if($model->validate())
                        {
                                // ищем пользователя
                                $user = User::model()->findByAttributes(array('phone' => $model->phone));
                                
                                // отключаем поведения
                                $model->disableBehavior('ActiveRecordStaticInteractionBehavior');
                                
                                // генерируем соль и пароль (пароль придёт по смс)
                                $user->salt = JRandom::salt();
                                $password = JRandom::password();
                                
                                $user->password = User::hashPassword($user->salt, $password);
                                
                                $result = Yii::app()->db->createCommand()
                                        ->update(
                                                'user', 
                                                array(
                                                        'salt' => $user->salt, 
                                                        'password' => $user->password
                                                ), 
                                                'id = :userID', 
                                                array(
                                                        'userID' => $user->id,
                                                )
                                        );
                                
                                $errorsArray = array();
                                foreach($model->getErrors() as $attribute => $errors)
                                        $errorsArray[CHtml::activeId($model,$attribute)] = $errors;
                                
                                if($result){
                                        JSMS::recoveryPasswordMessage($model->phone, $password);
                                        echo CJSON::encode(array('status' => 'success', 'errors' => $errorsArray, 'message' => 'Новый пароль выслан на номер '.Yii::app()->format->formatPhone($model->phone, true)));
                                        $ip = Yii::app()->request->userHostAddress;
                                        JRecoveryLog::logRecoveryAttempt($ip, $model->phone, 1);  
                                }
                                else
                                        echo CJSON::encode(array('status' => 'error', 'errors' => $errorsArray, 'message' => 'Неизвестная ошибка'));  
                        }
                        else 
                        {
                                $errorsArray = array();
                                foreach($model->getErrors() as $attribute => $errors)
                                        $errorsArray[CHtml::activeId($model,$attribute)] = $errors;
                            
                                $message = $model->hasErrors('error') ? $model->getError('error') : '';
                                
                                echo CJSON::encode(array('status' => 'error', 'errors' => $errorsArray, 'message' => $message));
                        }
                        
                        Yii::app()->end();
                }
            
                $this->renderPartial('index', array(
                        'model' => $model,
                ), false, true);
        }       
}

?>