<?php
/**
 * Класс виджета формы поиска
 */
class JSearchWidget extends CWidget
{
        /** 
         * @var SearchForm модель формы входа 
         */
        public $formModel;
        
        /** 
         * @var string селектор кнопки обработки формы 
         */
        public $submitButtonSelector;
    
	/**
         * Инициализация виджета
         * 
         * @throws CException
         */
        public function init() 
        {
                // проверка модели формы
                if(!$this->formModel instanceof SearchForm || $this->formModel === null)
                        throw new CException('JSearchWidget: в параметр "formModel" должна быть передана модель SearchForm');
                       
                if(isset($_POST['SearchForm'])){
                        $this->formModel->attributes = $_POST['SearchForm'];
                        
                        // выставляем в куку последний регион для поиска
                        if(isset($_POST['SearchForm']['id_region']))
                                Yii::app()->request->cookies['region_id'] = new CHttpCookie('region_id', $this->formModel->id_region, array(
                                        'expire' => time() + 60 * 60 * 24,
                                ));                   
                        if(isset($_POST['SearchForm']['id_city']))
                                Yii::app()->request->cookies['city_id'] = new CHttpCookie('city_id', $this->formModel->id_city, array(
                                        'expire' => time() + 60 * 60 * 24,
                                ));
                }
                else
                {
                        // поля формы поиска по-умолчанию
                        $this->formModel->minAge = Profile::AGE_MIN;
                        $this->formModel->maxAge = Profile::AGE_MAX;
                                            
                        // получаем город из контроллера
                        if(!isset($this->controller->regionId, $this->controller->cityId))
                        {
                                $this->formModel->id_city = 0;
                                $this->formModel->id_region = 0;
                        }
                        else
                        {
                                $this->formModel->id_city = $this->controller->cityId;
                                $this->formModel->id_region = $this->controller->regionId;   
                        }
                        
                        $this->formModel->gender = JGender::FEMALE;
                }
        }
        
        /**
         * Запуск виджета
         */
        public function run() 
        {
                $this->render('theme.views.widgets.jsearchwidget._searchform', array(
                    'model' => $this->formModel
                ));
        }
    
}

?>
