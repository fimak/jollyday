<?php
/**
 * Контроллер поиска
 */
class SearchController extends JAppController
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
                                'placeHolder' => ' ',
                                'fieldName' => 'id_region',
                                'responseName' => 'dropDownCities',
			),
		);
	}
        
        /**
         * Действие поиска
         * 
         * @param type $id ID пользователя
         */
	public function actionIndex()
	{
                $model = new Search;          
                $criteria = new CDbCriteria;
                $oldFriendshipTarget = false;
                
                $commandBuilder = Yii::app()->getDb()->getCommandBuilder();
                
                if(isset($_POST['Search']))
                {       
                        if(Yii::app()->request->isAjaxRequest)
                                $this->layout = '//layouts/clear'; // выводим только результаты
                        $model->attributes = $_POST['Search'];
                        // запоминаем значение цели Дружба и общение
                        // (т.к. если юзер ищет друзей, то эта цель
                        // ставится независимо от формы)
                        $oldFriendshipTarget = isset($model->targetIds[0]) ? $model->targetIds[0] : null;
                        $model->setSearchCookie();
                }
                else
                        $model->defaultFields();

                // строим критерий поиска на основании данных формы
                $criteria = $model->buildSearchCriteria(Yii::app()->user->id);
                
                // настриваем бесконечный пагер
                $count = $commandBuilder->createCountCommand('user', $criteria)->queryScalar();
                $pages = new CPagination($count);               
                $pages->pageSize = $model->resultType == Search::RESULT_PROFILE ? Yii::app()->settings->get('Pagination', 'searchResults') : 12;
                $pages->applyLimit($criteria);
                
                // выбираем только необходимые данные в зависимости, от типа вывода поиска (профили или фото)
                if($model->resultType == Search::RESULT_PROFILE)
                { 
                        $with = array(
                                'userpic' => array(
                                        'select' => 'filename_medium'
                                ),
                                'city' => array(
                                        'select' => 'name'
                                ),
                                'lastAction' => array(
                                        'select' => 'date'
                                )
                        );
                }
                else
                {
                        $with = array(
                                'userpic' => array(
                                        'select' => 'filename_medium'
                                )               
                        );
                }

                $users = User::model()
                        ->with($with)
                        ->findAll($criteria);

                // если выставлена цель знакомства "дружба и общение", то
                // присваиваем ей сохранённое в начале значение, т.к. пользователь
                // ищет "друзей", то эта цель выставляется автоматически. А сохранённое 
                // в начале значение - это значение из формы
                if(isset($model->targetIds[0]))
                        $model->targetIds[0] = $oldFriendshipTarget;
                               
                $this->render('index', array(
                        'users' => $users,
                        'pages' => $pages,
                        'model' => $model,
                        'methodList' => JMeetmethod::getData(),
                        'profileType' => 'search'
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
                //if(in_array($action->id, array('index')))
                        //Yii::app()->user->setActionDate();
                                             
                return parent::beforeAction($action);
        }
}