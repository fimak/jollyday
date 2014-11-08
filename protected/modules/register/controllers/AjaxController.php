<?php

/**
 * Контроллёр регистрации пользователя (всё с помощью ajax)
 */
class AjaxController extends JRegisterController
{
        /**
         * @var string лейаут контроллера
         */
        public $layout = '//layouts/clear';
    
        /**
         * В методе описаны фильтры контроллера
         * 
         * @return array массив с описанием фильтров
         */
        public function filters()
        {
                return array(
                    'ajaxOnly',
                );
        }
  
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
                                'placeHolder' => 'Выберите город',
                                'fieldName' => 'id_region',
                                'responseName' => 'dropDownCities',
			),
		);
	}        
   
        /**
         * Действие вывода формы регистрации
         */
	public function actionForm()
	{                          
                $model = new RegisterForm;
                $confirm = new RegisterConfirmForm;
                
                // для удобства разработки выводим код 
                $phoneCookie = RegisterConfirmForm::checkPhoneCookie();
                $code = null;
                if($phoneCookie)
                {
                        $code = Yii::app()->db->createCommand()
                                ->select('code')
                                ->from('new_user')
                                ->where('phone = :phone', array('phone' => $phoneCookie))
                                ->queryScalar();
                        
                        $model->phone = $phoneCookie;
                        
                }
                
		$this->renderPartial('_form', array(
                        'model' => $model,
                        'confirm' => $confirm,
                        'phoneCookie' => $phoneCookie,
                        'code' => $code,
                ), false, true);
	}
        
        /**
         * Действие валидации данных и запроса смс-кода
         */
        public function actionRequest()
        {
                $model = new RegisterForm;           
               
                if(isset($_POST['RegisterForm']))
                {
                        // при получении формы присваиваем её значенияя полям модели 
                        $model->attributes = $_POST['RegisterForm'];
                        
                        if($model->validate())
                        {
                                // если данные формы верны, то вставляем номер телефона юзера,
                                // подлежащий верификации в отдельную таблицу
                                $code = JConfirm::newUser($model->phone, $model->password);
                                
                                // ставим куку, которая будет указывать в течение суток
                                // с какого на какой номер пытается зарегистрировать аккаунт юзер
                                $cookie = new CHttpCookie('new_userphone', $model->phone);
                                $cookie->expire = time() + 60 * 60 *24;                                
                                Yii::app()->request->cookies['new_userphone'] = $cookie;
                                                                            
                                JSMS::registerMessage($model->phone, $code);
                                
                                // отдаём пользователю всё, что надо
                                echo CJSON::encode(array('status' => 'success', 'errors' => $model->getErrors()));
                                Yii::app()->end();
                        }
                        else
                                echo $this->getValidationErrors($model);
                }
                else
                        echo CJSON::encode(array('status' => 'error', 'errorMessage' => 'Данные формы не получены'));
        }

        /**
         * Действие подтверждения номера телефона при регистрации, используя
         * код, полученный по СМС
         */        
        public function actionConfirm()
        {                          
                $model = new RegisterConfirmForm;
                
                // если выставлена кука с номером телефона, регистрирующегося пользователя, то
                // то заносим её в переменную $cookie_phone, для этображения на втором шаге регистрации
                $cookie_phone = RegisterConfirmForm::checkPhoneCookie();
                
                if(isset($_POST['RegisterConfirmForm']))
                {    
                        $model->attributes = $_POST['RegisterConfirmForm'];
                                      
                        // проверяем смс-код на валидность
                        if($model->validate())
                        {                        
                                // если смс код валиден, то берём пароль и номер из вспомогательной таблицы
                                $newUser = JConfirm::getNewUserByCode($model->sms);
                               
                                // создаём экземпляр пользователя
                                $user = new User('register-two');
                                $user->phone = $newUser['phone'];
                                $user->password = $newUser['password'];
                                                           
                                if($user->save())
                                {                                  
                                        // удаляем запись из вспомогательной таблицы
                                        JConfirm::deleteNewUserByCode($model->sms);
                                        
                                        // убираем куку
                                        unset(Yii::app()->request->cookies['new_userphone']);
                                                                                                                                                             
                                        //залогиниваемся после регистрации
                                        $identity = new JUserIdentity($user->phone, $newUser['password']);
                                        $identity->authenticate();
                                        Yii::app()->user->login($identity, 3600*24*30);
                                        
                                        echo CJSON::encode(array(
                                                'result' => 'success',
                                                'redirect' => J::url('/app/register/personal'),
                                        ));
                                }                                                              
                        }
                        else
                                echo $this->getValidationErrors($model);
                }
        }
 
        /**
         * Дейтвие сброса куки и возврата пользователя на первый шаг регистрации
         */
        public function actionRefresh()
        {
                unset(Yii::app()->request->cookies['new_userphone']);
                
                echo CJSON::encode(array(
                        'status' => 'success',
                )); 
        }     
              
        public function actionIntmdRegister($id)
        {
                $user = User::getBaseInfo($id);
            
                $this->renderPartial('_intmd_register', array(
                        'user' => $user,
                ));
        }
}

?>
