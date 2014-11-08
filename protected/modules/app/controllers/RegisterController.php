<?php

/**
 * Контроллер регистрации уже созданного пользователя.
 *
 * @author gbespyatykh
 */
class RegisterController extends JAppController
{
        /** @var boolean показывать ли мордоленту на лейауте */
        public $faceribbonEnable = false;
        
        public $bonusWidgetEnable = false;
              
        /**
         *
         * @var boolean показывать ли только один пункт меню - "Выход"
         */
        public $onlyLogoutMenuItem = true;

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
         * Действие вывода формы для ввода персональных данных пользователя
         */
        public function actionPersonal()
        {
                $model = new RegPersonalForm;
                
                $this->performAjaxValidation($model, 'form-reg-personal');
                                          
                if(isset($_POST['RegPersonalForm']))
                {                    
                        $model->attributes = $_POST['RegPersonalForm'];
                        
                        if($model->validate())
                        {
                                $user = User::current();
                                $user->attributes = $model->attributes;
                                $user->register_step = 3;
                                
                                // обнуляем email, но далее отправляем рег. письмо
                                $user->email = null;
                                
                                if($user->save())
                                {
                                        //если введен емейл то записываем в таблицу новый емейл и шлём письмо
                                        if($model->email != null)   
                                                JConfirm::newEmail($user->id, $model->email);
                                        
                                        // ставим юзеру часовой пояс
                                        Yii::app()->user->setTimezone($user->id_region);
                                        
                                        /*
                                        // отсылаем регистрационную новость                     
                                        $bonusCounter = JPayment::getBonusCounter($user->id_region);                                   
                                        if($bonusCounter > 500) 
                                                News::sendPersonalTemplated('register', $user->id);
                                        else
                                                News::sendPersonalTemplated('register500', $user->id);
                                        */
                                        // выводим форму шага 4
                                        $this->redirect(array('register/meetingway'));
                                        Yii::app()->end();
                                }
                        }                                      
                }
                else
                {
                        // пытаемся определить город юзера с помощью геолокации
                        $geo = new JGeo();
                        if($geo->isLocated())
                        {
                                $model->id_city = $geo->getCityID();
                                $model->id_region = $geo->getRegionID();
                        }                    
                }
               
                
                $this->render('personal', array(
                        'model' => $model,
                ));                
        }
        
        /**
         * Действие вывода формы способов знакомства
         */
        public function actionMeetingway()
        {
                $model = User::current();
                $model->setScenario('register-four');
                                                        
                if(isset($_POST['User']))
                {                                         
                        $model->attributes = $_POST['User'];                                              
                        
                        $model->register_step = 4;
                        
                        if($model->validate(array('meetmethodIds')))
                        {  
                                $model->saveRelatedStaticData('im_user_meetmethod', 'meetmethodIds', 'id_meetmethod', Yii::app()->user->id);
                                $model->save();
                                $this->redirect(array('register/questionary'));
                        }
                        else{
                                Yii::app()->user->setFlash('error', $model->getError('meetmethodIds'));
                        }
                }
                else
                {
                        $model->meetmethodIds = JMeetmethod::getIds();
                }
                $this->render('meetingway', array(
                        'model' => $model,
                ));
        }
        
        /**
         * Действие вывода анкеты пользователя для редактирования
         */
        public function actionQuestionary()
        {
                $user = User::current();
                $profile = $user->profile;
 
                if(isset($_POST['Profile']))
                {
                        $profile->attributes = $_POST['Profile'];
                                                                                    
                        if($profile->save())
                        {
                                $user->register_step = 0;
                                if($user->save(false, array('register_step')))   
                                        $this->redirect(array('photo/uploader'));
                        }
                }
                else
                {
                        // присваиваем значение по-умолчанию
                        if($user->id_gender == JGender::MALE)
                                $profile->seekingIds = array(JGender::FEMALE);
                        else
                                $profile->seekingIds = array(JGender::MALE);
                }
                
                $this->render('questionary', array(
                        'profile' => $profile,
                ));
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
                //if(in_array($action->id, array('meetingway', 'personal', 'questionary')))
                        //Yii::app()->user->setActionDate();
                                             
                return parent::beforeAction($action);
        }
}

?>
