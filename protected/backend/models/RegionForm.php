<?php

/**
 * Модель формы основных настроек сайта
 */
class RegionForm extends CFormModel
{
        public $id_region;
        public $id_city;
        
        /**
         * Метод, возвращающий массив с описанием правил валидации модели
         * 
	 * @return array массив правил валидации
	 */
        public function rules()
        {
                return array(
                        array('id_region, id_city', 'safe'),
                );
        }
        
	/**
         * Метод возвращает массив настраиваемых названий
         * атрибутов модели (имя атрибута => название)
         * 
	 * @return array массив названий атрибутов
	 */
        public function attributeLabels()
        {
                return array(
                        'id_region' => 'Регион',
                        'id_city' => 'Город'
                );
        }
}

?>
