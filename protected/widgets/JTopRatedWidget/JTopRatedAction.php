<?php

/**
 * Действие подгрузки нужной страницы в мордоленту
 *
 * @author gbespyatykh
 */
class JTopRatedAction extends CAction
{           
        public function run()
        {
                $users = User::getTopRated($this->controller->regionId);
                
                if(empty($users))
                        $users = User::getTopRated();
                
                if(!$users)
                        return;
                
                $criteria = new CDbCriteria();
                $pageSize = Yii::app()->settings->get('Pagination', 'searchResults');
                             
                $pages = new CPagination();               
                $pages->pageSize = $pageSize ;
                $pages->applyLimit($criteria);
                
                $currentPage = isset($_GET['page']) ? $_GET['page'] : 0;
                                                      
                $offset = ($currentPage - 1) * $pageSize;
                
                if($currentPage > 6 || !isset($users[$offset]))
                        throw new CHttpException('404', 'Страница не существует');
                
                $result = array();
                
                if($users[$offset])
                        $result[] = $users[$offset];
                if($users[$offset + 1])
                        $result[] = $users[$offset + 1];          
                if($users[$offset + 2])
                        $result[] = $users[$offset + 2];
                $this->controller->layout = '//layouts/clear';
                
                $this->controller->renderPartial('theme.views.widgets.jtopratedwidget._jtoprated', array(
                        'users' => $result,
                        'pages' => $pages,
                        'methodList' => JMeetmethod::getList(),
                        'controller' => $this->controller
                ));
        }
}

?>

