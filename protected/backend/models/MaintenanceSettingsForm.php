<?php

/**
 * Модель формы настроек пагинации на сайте в админке
 */
class MaintenanceSettingsForm extends CFormModel
{
        // поля для ключей настроек
        public $enable;
        public $text;
      
        /**
         * Метод, возвращающий массив с описанием правил валидации модели
         * 
	 * @return array массив правил валидации
	 */
        public function rules()
        {
                return array(
                        array('enable', 'boolean'),
                        array('text', 'safe'),
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
                        'enable' => 'Включить оповещение о работах',
                        'text' => 'Текст оповещения',
                );
        }

        /**
         * Метод заполняет поля модели данными из таблицы
         */
        public function getSettings()
        {           
                //присваиваем данные полям
                $this->enable = Yii::app()->settings->get('Maintenance','enableNoticeWidget', 0);
                $this->text = Yii::app()->settings->get('Maintenance','text', '');
        }
        
        /**
         * Метод сохраняет настройки в таблицу из модели
         */
        public function saveSettings()
        {       
                // записываем настройки
                Yii::app()->settings->set('Maintenance', 'enableNoticeWidget', $this->enable);
                Yii::app()->settings->set('Maintenance', 'text', $this->text);    
        }
}

?>
