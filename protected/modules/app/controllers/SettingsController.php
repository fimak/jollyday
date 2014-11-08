<?php
/**
 * Контроллер настроек пользователя
 */
class SettingsController extends JAppController
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
				'class'=>'JDropDownCities',
                                'fieldName' => 'id_region',
                                'responseName' => 'dropDownCities',
			),
		);
	}
        
        /**
         * В методе описаны фильтры контроллера
         * 
         * @return array массив с описанием фильтров
         */
        public function filters()
        {
                return array(
                    'ajaxOnly 
                        + SetPassword
                        + SetPhone
                        + SetEmail
                        + Delete
                    ',
                );
        }
    
        /*
         * Действие вывода списка настроек
         */
	public function actionIndex()
	{                       
                $model = User::id(Yii::app()->user->id);
                      
                $model->setScenario('settings');
                
                if(isset($_POST['User']))
                {
                        $model->attributes = $_POST['User'];
                                                                                       
                        if($model->save(true, array('name', 'id_region', 'id_city', 'id_gender', 'birthday')))
                        { 
                                Yii::app()->user->setTimezone($model->id_region);
                                Yii::app()->user->setFlash('success', 'Изменения сохранены');
                                $this->refresh();
                        }
                        else
                        {
                                Yii::app()->user->setFlash('error', 'Данные заполнены ошибочно');
                        }
                }
                                              
                $this->render('index', array(
                        'model' => $model,
                ));
	}
        
        /**
         * Действие изменения пароля
         */
        public function actionSetPassword()
        {
                $model = new SetPasswordForm;
                
                if(isset($_POST['SetPasswordForm']))
                {
                        $model->attributes = $_POST['SetPasswordForm'];
                        if($model->validate())
                        {
                                // если форма свалидировалась, то изменяем данные юзера
                                $user = User::model()->findByPk(Yii::app()->user->id);
                                $user->setScenario('set-password');
                                $user->salt = JRandom::salt();
                                $user->password = $model->new_password;
                                
                                // пробуем сохранить данные, выставляем флеш-сообщения
                                if($user->save(true, array('salt', 'password')))
                                {
                                        echo CJSON::encode(array(
                                                'status' => 'success',
                                                'message' => 'Пароль успешно изменён',
                                        ));   
                                }                                                                               
                        }
                        else
                        {
                                // выводим ошибки валидации
                                $errorsArray = array();
                                foreach($model->getErrors() as $attribute => $errors)
                                        $errorsArray[CHtml::activeId($model,$attribute)] = $errors;

                                echo CJSON::encode(array(
                                        'status' => 'error',
                                        'errors' => $errorsArray
                                )); 
                        }
                        Yii::app()->end();
                }
                $this->renderPartial('_set_password', array(
                        'model' => $model,
                ), false, true); 
                Yii::app()->end();
        }
        
        /**
         * Действие изменения почтового адреса
         */
        public function actionSetEmail()
        {
                $model = new SetEmailForm;
                
                // если данные формы получены, то обрабатываем её и шлём клиенту ответ
                // в формате JSON
                if(isset($_POST['SetEmailForm']))
                {
                        $model->attributes = $_POST['SetEmailForm'];
                        if($model->validate())
                        {
                                // отправляем клиенту данные в формате JSON
                                if(JConfirm::newEmail(Yii::app()->user->id, $model->email))
                                        echo CJSON::encode(array(
                                                'status' => 'success',
                                                'message' => 'На указанный адрес электронной почты отправлено письмо с кодом активации',
                                                'email'=>$model->email,
                                        ));                            
                                else
                                        echo CJSON::encode(array(
                                                'status' => 'error',
                                        ));         
                        }
                        else
                        {
                                // выводим ошибки валидации
                                $errorsArray = array();
                                foreach($model->getErrors() as $attribute => $errors)
                                        $errorsArray[CHtml::activeId($model,$attribute)] = $errors;
                            
                                echo CJSON::encode(array(
                                        'status' => 'error',
                                        'errors' => $errorsArray
                                ));   
                        }
                        
                        Yii::app()->end();
                }
                
                // если данные формы не получены, то это указывает, что необходима подгрузка
                // формы в блок
                $this->renderPartial('_set_email', array(
                        'model' => $model,
                ), false, true); 
        }
        
        /**
         * Действие изменения номера телефона
         */
        public function actionSetPhone()
        {
                $model = new SetPhoneForm;
                $model->setScenario('request');
                
                // если получены данные формы, то обрабатываем её и 
                // возвращаем пользователю данные в формате JSON
                if(isset($_POST['SetPhoneForm']))
                {                       
                        $model->attributes = $_POST['SetPhoneForm'];
                                                        
                        // если код не введён, то работаем по сценарию 'request'
                        // запрос на смену номера телефона
                        if($model->validate() && JConfirm::newPhone(Yii::app()->user->id, $model->phone))
                        {
                                // вставляем номер и код в таблицу новых номеров
                                JSMS::newphoneMessage($model->phone, JConfirm::getCodeByNewNumber($model->phone));
                                echo CJSON::encode(array(
                                        'status' => 'success',
                                        'message' => 'На введённый номер выслан код подтверждения',
                                        'stage' => 'request',
                                        'confirmurl' => J::url('settings/setphoneconfirm'),
                                ));
                        }
                        else
                        {
                                // выводим ошибки валидации
                                $errorsArray = array();
                                foreach($model->getErrors() as $attribute => $errors)
                                        $errorsArray[CHtml::activeId($model,$attribute)] = $errors;
                            
                                echo CJSON::encode(array(
                                        'status' => 'error',
                                        'stage' => 'request',
                                        'errors' => $errorsArray
                                ));
                        }                     
                        Yii::app()->end();
                }
                
                // если данные формы не получены, то возвращаем 
                // пользователю форму
                $this->renderPartial('_set_phone', array(
                        'model' => $model,
                ),false, true);
        }
        
        public function actionSetPhoneConfirm()
        {
                $model = new SetPhoneForm;
                $model->setScenario('confirm');
                
                // если получены данные формы, то обрабатываем её и 
                // возвращаем пользователю данные в формате JSON
                if(isset($_POST['SetPhoneForm']))
                {     
                        $model->attributes = $_POST['SetPhoneForm'];

                        if($model->validate())
                        {
                                // Изменяем номер на новый и вставляем его в сессию
                                $newPhone = JConfirm::updatePhone(Yii::app()->user->id);
                                Yii::app()->user->setState('phone', $newPhone);
                                
                                echo CJSON::encode(array(
                                        'status' => 'success',
                                        'message' => 'Номер телефона успешно изменён',
                                        'stage' => 'confirm',
                                        'newphone' => Yii::app()->format->formatPhone($newPhone, true)
                                ));
                        }
                        else
                        {
                                // выводим ошибки валидации
                                $errorsArray = array();
                                foreach($model->getErrors() as $attribute => $errors)
                                        $errorsArray[CHtml::activeId($model,$attribute)] = $errors;

                                echo CJSON::encode(array(
                                        'status' => 'error',
                                        'stage' => 'confirm',
                                        'errors' => $errorsArray
                                ));    
                        }
                        Yii::app()->end();
                        
                }
        }
        
        /**
         * Действие удаления профиля
         */
        public function actionDelete()
        {   /** @FIXME исправить баг с ajax-валидацией */
               $model = new CaptchaForm('profile-delete');
                   
               if(isset($_POST['CaptchaForm']))
               {
                        $model->attributes = $_POST['CaptchaForm'];
                        
                        if($model->validate())
                        {
                                User::markProfileAsDelete(Yii::app()->user->id);
                                Yii::app()->user->logout();
                                self::jsRedirect('/');
                        }
               }
               
               
               $this->renderPartial('_delete_form', array(
                        'model' => $model,
               ),false, true); 
        }
        
        /**
         * Событие выполняемое перед запуском действия
         * 
         * @param CAction $action
         * @return boolean
         */
        public function beforeAction($action)
        {
                // ставим дату последнего посещения у пользователя 
                //if(in_array($action->id, array('index')))
                        //Yii::app()->user->setActionDate();
                                             
                return parent::beforeAction($action);
        }
} 