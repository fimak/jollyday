<?php

/**
 * Дефолтный контроллер сайта, (главная страница, вывод ошибки и т.п.)
 */
class SiteController extends JController
{
        /** 
         * @var string лейаут контроллера 
         */
        public $layout = '//layouts/main';
        
        /**
         * @var boolean Показывать ли на странице виджет самых популярных пользователей 
         */
        public $enableTopRated = false;
        
        /**
         * @var integer поле для хранения региона пользователя, определяемого с помощью geoIP
         */
        public $regionId = 0;

        /**
         * @var integer поле для хранения города пользователя, определяемого с помощью geoIP
         */
        public $cityId = 0;
    
	/**
         * В методе описаны подключаемые типовые действия
         * 
	 * @return array массив с настройками действий
	 */
	public function actions()
	{
		return array(
			// капча
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
                                'foreColor' => 0x147C99,
                                'maxLength'=> 6,
                                'minLength'=> 6,
                                'testLimit'=> 1,
                                'width' => 123,
                                'padding' => 0,
                                'offset' => 5,
                                'backend' => 'gd'
			),
                        'cities'=>array(
                                'class'=>'JDropDownCities',
                                'placeHolder' => 'Любой',
                                'fieldName' => 'id_region',
                                'responseName' => 'dropDownCities',
                        ),
                        'toprated.' => array(
                                'class' => 'JTopRatedWidget',
                                'loadProfiles' => array(
                                    
                                ),
                        ),
                        'page' => array(
                                'class' => 'CViewAction',
                        ),
                        'feedback' => 'JFeedbackAction',
		);
	}
        
        /**
         * Действие подгрузки анкеты пользователя
         * 
         * @param integer $id ID пользователя
         */
        public function actionLoadProfile($id)
        {   
                $user = User::id($id);
                
                if($user === null)
                        throw new CHttpException('404', 'Страница не существует');
                
                $this->renderPartial('theme.views.profile._profile', array(
                        'user' => $user,
                        'methodList' => JMeetmethod::getData(),
                        'gifts' => User::getGifts($user->id),
                ));
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
                            + LoadLoginForm
                            + ProcessLoginForm
                    ',
                );
        }
	/**
	 * Действие по умолчанию
	 */
	public function actionIndex()
	{       
                $this->enableTopRated = true;
                $this->render('index');
	}
       
        /**
	 * Действие вывода ошибки
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
                        {
                                if($error['code'] == 404)
                                        $this->layout = 'webroot.themes.main.views.layouts.error404';
                                else
                                        $this->layout = 'webroot.themes.main.views.layouts.clear';
				$this->render('webroot.themes.main.views.site.error', $error);
                        }
		}
	}
        
        /**
         * Действие вывода формы входа на сайт
         */
        public function actionLoginForm()
        {
                $model = new LoginForm;
                $this->renderPartial('_loginform', array('model' => $model),false,true);
        }
        
        /**
         * Действие авторизации пользователя
         */
        public function actionLogin()
        {      
                  if(isset($_POST['LoginForm']))
                  {
                        $model = new LoginForm;
                        $model->attributes = $_POST['LoginForm'];
                                        
                        $result = array();
                        
                        // пробуем заалогинить пользователя
                        if($model->validate() && $model->login())
                        {
                                // выводим редирект на страницу профиля
                                $result = array(
                                        'redirect' => $this->createUrl('/app/profile/index'), 
                                        'status' => 'success'
                                );
                        }
                        else
                        {
                                // Выводим ошибки валидации
                                foreach($model->getErrors() as $attribute=>$errors)
                                        $result[CHtml::activeId($model,$attribute)]=$errors;
                        }
                                
                        echo CJSON::encode($result);   
                  }
        }
        
        /**
         * Действие вывода страницы об устаревшем браузере
         */
        public function actionBadbrowser()
        {
                $this->layout = 'webroot.themes.main.views.layouts.badbrowser';
                
                $this->render('badbrowser');
        }
        
        /**
         * Событие происходящее перед выполнением действия
         * 
         * @param CAction $action
         * @return boolean результат события
         */
        protected function beforeAction($action)
        {
                if(!Yii::app()->user->isGuest && in_array($action->id, array('loginform', 'form')))
                        JController::jsRedirect(J::url('/app/profile/index'));
                
                if(parent::beforeAction($action))
                {       
                        if($action->id == 'error' || $action->id == 'captcha' || $action->id == 'badbrowser' )
                                return true;         
                    
                        if(!Yii::app()->user->isGuest)
                                $this->redirect(array('/app/profile/index'));
                        
                        
                        if(in_array($action->id, array('index', 'feedback', 'search', 'toprated.loadProfiles')))
                                $this->locateRegion();
                        
                        return true;
                }
                else
                {
                        return false;
                }
        }
        
        /**
         * Действие поиска пользоватлей из формы поиска на главной странице
         */
        public function actionSearch()
        {
                $model = new SearchForm;          
                $criteria = new CDbCriteria;
                $commandBuilder = Yii::app()->getDb()->getCommandBuilder();
                
                if(isset($_POST['SearchForm']))
                {       
                        if(Yii::app()->request->isAjaxRequest)
                                $this->layout = '//layouts/clear'; // выводим только результаты
                        $model->attributes = $_POST['SearchForm'];
                }
                
                // строим критерий поиска на основании данных формы
                $criteria = $model->buildSearchCriteria();
                
                // настраиваем бесконечный пагер
                $count = $commandBuilder->createCountCommand('user', $criteria)->queryScalar();
                $pages = new CPagination($count);               
                $pages->pageSize = Yii::app()->settings->get('Pagination', 'searchResults');
                $pages->applyLimit($criteria);
                
                $users = User::model()
                        ->together()
                        ->with(array(
                                'userpic' => array(
                                        'select' => 'filename_medium'
                                ),
                                'city' => array(
                                        'select' => 'name'
                                ),
                                'lastAction' => array(
                                        'select' => 'date'
                                ),
                        ))
                        ->findAll($criteria);

                
                $this->render('search', array(
                        'users' => $users,
                        'pages' => $pages,
                        'model' => $model,
                        'methodList' => JMeetmethod::getData(),
                        'profileType' => 'search'
                ));    
        }
        
        /**
         * Действие вывода страницы "Как познакомиться"
         */
        public function actionHowtomeet()
        {
                $this->renderPartial('_how_to_meet');
        }
        
        /**
         * Функция определения региона пользователя. Пытается взять значение из куков. 
         * Если не находит, то запускает геолокацию.
         */
        private function locateRegion()
        {
                if(isset(Yii::app()->request->cookies['region_id']))
                {
                        $this->regionId = (string)Yii::app()->request->cookies['region_id'];

                }
                elseif(isset(Yii::app()->request->cookies['city_id']))
                {
                        $this->cityId = (string)Yii::app()->request->cookies['city_id'];      
                }
                else
                {                 
                        $geo = new JGeo;

                        if($geo->isLocated())
                        {                             
                                $this->regionId = $geo->getRegionID();
                                $this->cityId = $geo->getCityID();
                        }
                        
                        Yii::app()->request->cookies['region_id'] = new CHttpCookie('region_id', $this->regionId, array(
                                'expire' => time() + 60 * 60 * 24,
                        ));
                        Yii::app()->request->cookies['city_id'] = new CHttpCookie('city_id', $this->cityId, array(
                                'expire' => time() + 60 * 60 * 24,
                        ));
                }
        }
}
