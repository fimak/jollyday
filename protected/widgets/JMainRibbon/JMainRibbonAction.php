<?php

/**
 * Действие подгрузки нужной страницы в мордоленту
 *
 * @author gbespyatykh
 */
class JMainRibbonAction extends CAction
{
        /**
         * @var integer количество позиций на странице мордоленты
         */
        public $pageSize;
            
        public function run()
        {
                $page = isset($_GET['page']) ? $_GET['page'] : false;
                $position = isset($_GET['position']) ? $_GET['position'] : false;
                
                if(empty($page) && empty($position))
                        throw new CHttpException('404', 'Страница не найдена');
                
            
                // если передан параметр position, то нужно загрузить страницу
                // с номером этой позиции
                if(!empty($position) && is_numeric($position))
                        $page = ceil($position / $this->pageSize);
                      
                $criteria = new CDbCriteria;
                // с аватаркой, зарегистрированный, не забаненый и не удалёный пользователь
                $criteria->compare('t.register_step', 0);
                $criteria->compare('t.role', User::ROLE_USER);
                $criteria->compare('t.fl_deleted', 0);

                $criteria->select = '   
                        t.id AS id, 
                        t.name AS username,
                        photo.filename_faceribbon AS userpic
                ';
                $criteria->join = 'JOIN photo ON t.id_userpic = photo.id';

                // сортировка по рейтингу
                $criteria->order = 't.date_rating DESC';

                $count = Yii::app()->db->commandBuilder->createCountCommand('user', $criteria)->queryScalar();

                // постраничная разбивка
                $criteria->limit = $this->pageSize;
                $criteria->offset = $this->pageSize * ($page - 1);

                $users = Yii::app()->db->commandBuilder->createFindCommand('user', $criteria)->queryAll();
                
                $pageCount = ceil($count / $this->pageSize);
                $viewPath = 'theme.views.widgets.jmainribbon._faces';
                $html = $this->controller->renderPartial($viewPath, array(
                           'users' => $users,
                           'page' => $page,
                           'pageSize' => $this->pageSize,
                           'pageCount' => $pageCount
                ), true);
                
                $isLastPage = ($page >= $pageCount);
                
                echo CJSON::encode(array(
                        'html' => $html,
                        'isLastPage' => (int)$isLastPage,
                        'page' => $page,
                ));
        }
}

?>

