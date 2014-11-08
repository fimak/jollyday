<?php

/**
 * Контроллёр-песочница, где можно делать и тестировать всё, что угодно.
 * Работает только в режиме отладки
 */
class SandboxController extends JAppController
{  
        /**
         * Метод выполняется перед запуском каждого действия
         * 
         * @param CAction $action объект действия
         */
        protected function beforeAction($action)
        {
                // разрешаем запускать действия этого контроллера только в режиме отладки
                if(YII_DEBUG == true)        
                        return parent::beforeAction($action);
                else
                        throw new CHttpException('404', 'Страница не существует'); 
        }
        
        public function actionIndex()
        {             
                $users = User::getTopRated();
                $criteria = new CDbCriteria();
                $pageSize = Yii::app()->settings->get('Pagination', 'searchResults');
                
                $pages = new CPagination();               
                $pages->pageSize = $pageSize ;
                $pages->applyLimit($criteria);
                
                $offset = $pages->currentPage * $pageSize;
                
                //$users = $users[$offset] + $users[$offset + 1] + $users[$offset + 2];
                
                $this->render('index', array(
                        'users' => $users,
                        'pages' => $pages
                ));
        }
        
}

?>
