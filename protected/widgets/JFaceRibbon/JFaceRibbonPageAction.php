<?php

/**
 * Действие подгрузки нужной страницы в мордоленту
 *
 * @author gbespyatykh
 */
class JFaceRibbonPageAction extends CAction
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
     
                $regionID = Yii::app()->user->getRegionID();
                // критерий по региону
                if(!empty($regionID))
                        $criteria->compare('t.id_region', $regionID);

                // с аватаркой, зарегистрированный, не забаненый и не удалёный пользователь
                $criteria->compare('t.register_step', 0);
                $criteria->compare('t.role', User::ROLE_USER);
                $criteria->compare('t.fl_deleted', 0);
                
                $criteria->select = '   
                        t.id AS id, 
                        t.name AS username,
                        t.birthday AS birthday,
                        photo.filename_faceribbon AS userpic,
                        city.name AS city,
                        profile.age_min AS agemin,
                        profile.age_max AS agemax,
                        profile.id_seeking AS seeking
                ';
                $criteria->join = 'JOIN photo ON t.id_userpic = photo.id';
                $criteria->join .= ' JOIN city ON t.id_city = city.id';
                $criteria->join .= ' JOIN profile ON profile.id_user = t.id';

                // сортировка по рейтингу
                $criteria->order = 't.date_rating DESC';

                $count = Yii::app()->db->commandBuilder->createCountCommand('user', $criteria)->queryScalar();

                // постраничная разбивка
                $criteria->limit = $this->pageSize;
                $criteria->offset = $this->pageSize * ($page - 1);

                $users = Yii::app()->db->commandBuilder->createFindCommand('user', $criteria)->queryAll();
 
                $pageCount = ceil($count / $this->pageSize);
                
                $html = $this->controller->renderPartial('theme.views.widgets.jfaceribbon._faces', array(
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
