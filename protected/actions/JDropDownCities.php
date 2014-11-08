<?php

/**
 * Класс действия подгрузки городов в выпдающий список
 */
class JDropDownCities extends CAction
{
        /** 
         * @var $fieldName string имя поля в запросе, по которому будет производиться выборка
         */
        public $fieldName;
        
        /** 
         * @var string текст заменитель для пустого элемента списка 
         */
        public $placeHolder;
        
        /** 
         * @var string имя переменной в JSON-ответе, содержащее оциии для тега "select" 
         */
        public $responseName;
        
        /**
         * Запуск действия
         */
        public function run()
        {
                // только AJAX-запросы
                if(!Yii::app()->request->isAjaxRequest)
                        Yii::app()->end();
                              
                // инициализация промежуточных данных и 
                // строки, содержащей опциия для селекта
                $data = array();
                $dropDownCities = '';
                $regionID = (int)$_POST[$this->fieldName];
                
                $data = City::getCitiesListByRegion($regionID);
                
                // если указан плейсхолдер- добавляем в список
                if($this->placeHolder)
                        $dropDownCities = "<option value=''>".$this->placeHolder."</option>"; 
                
                // генерация опций для селекта
                foreach($data as $value=>$name)
                        $dropDownCities .= CHtml::tag('option', array('value'=>$value),CHtml::encode($name),true);

                // возвращаем данные в формате JSON
                echo CJSON::encode(array(
                        $this->responseName => $dropDownCities
                )); 
        } 
}

?>
