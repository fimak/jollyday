<?php

/**
 * Контроллер профиля пользователя
 */
class ProfileController extends JAppController
{
	/**
         * В методе описаны подключаемые типовые действия
         * 
	 * @return array массив с настройками действий
	 */
	public function actions()
	{
		return array(
			'loadCities' => array(
				'class'=>'JDropDownCities',
                                'fieldName' => 'id_region',
                                'responseName' => 'dropDownCities',
			),
                        'fr.' => array(
                                'class' => 'JFaceRibbon',
                                'loadFaces' => array(
                                        'pageSize' => 12,
                                ),
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
                            + Albums 
                            + Offer 
                            + LoadAlbums 
                            + LoadQuestionaryForm
                            + QuestionaryUpdate 
                            + SettingsUpdate 
                    ',
                );
        }
        
	/**
	 * Действие выхода с сайта
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}        
 
        /**
         * Действие показывает профиль пользователя
         */
	public function actionIndex()
	{             
                $user = User::current();
                
                if(!isset($user->profile))
                        $user->createProfile();
                
                $limit = Yii::app()->settings->get('Pagination','compactMessages');
                                                         
                $this->render('index', array(
                    'user'=> $user,
                    'profileType' => 'own',
                    'metaMessages' => Metamessage::getLastMetaMessages($user->id, $limit),
                ));                                                                               
	}
                       
        /**
         * Подгружает форму для редактирования настроек в профиле
         * @param int $id ID юзера
         */
        public function actionUpdateMethods()
        {           
                $model = User::current();
                $model->setScenario('methods-update');
            
                if(isset($_POST['User']))
                {
                        $model->attributes = $_POST['User'];                                          
                        
                        if($model->validate(array('meetmethodIds')))
                        {
                                $model->saveRelatedStaticData('im_user_meetmethod', 'meetmethodIds', 'id_meetmethod', Yii::app()->user->id); 
                                
                                // сбрасывание кеша
                                Yii::app()->cache->delete('meetmethods'.$model->id);
                                
                                $message = "Изменения сохранены";
                                $status = 'success';
                                $html = $this->renderPartial('_right_own', array(
                                       'userMethods' => $model->meetmethodIds,
                                       'listMethods' => JMeetmethod::getData(),
                                ), true);
                        }
                        else
                        {                         
                                $message = $model->getError('meetmethodIds');
                                $status = 'error';
                                $html = null;
                        }
                        echo CJSON::encode(array('status' => $status, 'message' => $message, 'html' => $html));
                        Yii::app()->end();  
                }
                                           
                $this->renderPartial('_meetmethods_update', array(
                        'model' => $model,
                        'userID' => Yii::app()->user->id
                ),false,true);           
        }
                             
        /**
         * Действие восстановления удалённой анкеты
         */
        public function actionRecovery()
        {
                $model = new CaptchaForm('profile-recovery');
                
                if(isset($_POST['CaptchaForm']))
                {
                        $model->attributes = $_POST['CaptchaForm'];
                        
                        if($model->validate() && User::markProfileAsActive(Yii::app()->user->id))
                                        $this->redirect(array('profile/index'));
                }
                        
                $this->render('recovery', array(
                        'model' => $model,
                ));
            
        }
        
        /**
         * Действие подгрузки блока с последними сообщениями на 
         * странице профиля
         */
        public function actionLoadRecentMessages()
        {
                $offers = Offer::lastByMessages(Yii::app()->user->id, Yii::app()->settings->get('Pagination','compactMessages'));
                
                $this->renderPartial('theme.views.app.message._own_profile_offers', array(
                        'offers' => $offers
                ));
        }
        
        /**
         * Действие подгрузки компактного профиля (по клику на фото при соответствующем поиске)
         * 
         * @throws CHttpException
         */
        public function actionLoadProfile()
        {
                if(!isset($_POST['id']))
                        throw new CHttpException('404', 'Профиль не существует');
            
                $user = User::id($_POST['id']);
                $methodList = JMeetmethod::getData();
                
                $this->renderPartial('theme.views.app.profile._profile', array(
                        'user' => $user,
                        'methodList' => $methodList,
                        'profileType' => 'search',
                        'gifts' => User::getGifts($user->id),
                        'photos' => User::getPhotos($user->id, $user->id_userpic),
                ));
        }
        
        public function actionRateform()
        {
                $userpicId = Yii::app()->user->getUserpicID();
                if(!empty($userpicId))
                {
                        $model = Photo::model()->findByPk($userpicId);
                        $imageUrl = $model->mediumURL;
                }
                else
                {
                        $imageUrl = User::getNoPic('medium');
                }
            
                $this->renderPartial('_rating_form', array(
                        'photo' => $imageUrl,
                ), false, true);
        }
        
        public function actionRateup()
        {
                $result = array();
            
                if(Yii::app()->user->getAccount() >= JPayment::COST_RATING)
                {
                        $userID = Yii::app()->user->id;
                        if(User::setMaxRating($userID) && JPayment::subMoney($userID, JPayment::COST_RATING)){
                            
                                $result['status'] = 'success';
                                $account = Yii::app()->user->getAccount() - JPayment::OPERATION_RATING;
                                if(fmod($account , 1)==0)
                                        Yii::app()->format->numberFormat['decimals']= 0;
                                $result['account'] = Yii::app()->format->formatNumber($account);

                                $result['html'] = $this->renderPartial('_rating_form_success', array(
                                        'user' => User::model()->findByPk($userID)
                                ), true);

                                echo CJSON::encode($result);
                        }
                        else
                                echo CJSON::encode(array('status' => 'error'));
                }
                else
                        echo CJSON::encode(array('status' => 'error'));
        }
        
        public function actionBanned()
        {
                $this->render('banned');
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
                if(in_array($action->id, array('index')))
                        Yii::app()->user->setActionDate();
                                             
                return parent::beforeAction($action);
        }
}