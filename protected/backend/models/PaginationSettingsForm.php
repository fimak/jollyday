<?php

/**
 * Модель формы настроек пагинации на сайте в админке
 */
class PaginationSettingsForm extends CFormModel
{
        // поля для ключей настроек
        public $compactMessages;
        public $profileMessages;
        public $chatMessages;
        public $news;
        public $feedbacks;
        public $searchResults;
        
        /**
         * Метод, возвращающий массив с описанием правил валидации модели
         * 
	 * @return array массив правил валидации
	 */
        public function rules()
        {
                return array(
                        array('compactMessages, profileMessages, chatMessages, news, feedbacks, searchResults', 'numerical', 
                                'allowEmpty' => false, 
                                'integerOnly' => true,
                                'min' => 1,
                                'max' => 100,
                                'message' => 'Нужно ввести целое число'
                        ),
                        array('compactMessages, searchResults', 'JMultiplicityValidator',
                                'factor' => 3
                        ),
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
                        'compactMessages' => 'Сообщения на странице своего профиля',
                        'profileMessages' => 'Сообщения в виде профилей на отдельной странице',
                        'chatMessages' => 'Сообщения в диалоге',
                        'news' => 'Новости',
                        'feedbacks' => 'Сообщения обратной связи',
                        'searchResults' => 'Результаты поиска'
                );
        }

        /**
         * Метод заполняет поля модели данными из таблицы
         */
        public function getSettings()
        {           
                //присваиваем данные полям
                $this->compactMessages = Yii::app()->settings->get('Pagination','compactMessages');
                $this->profileMessages = Yii::app()->settings->get('Pagination','profileMessages');
                $this->chatMessages = Yii::app()->settings->get('Pagination', 'chatMessages');
                $this->news = Yii::app()->settings->get('Pagination','news');
                $this->feedbacks = Yii::app()->settings->get('Pagination', 'feedbacks');
                $this->searchResults = Yii::app()->settings->get('Pagination', 'searchResults');
        }
        
        /**
         * Метод сохраняет настройки в таблицу из модели
         */
        public function saveSettings()
        {       
                // записываем настройки
                Yii::app()->settings->set('Pagination', 'compactMessages', $this->compactMessages);
                Yii::app()->settings->set('Pagination', 'profileMessages', $this->profileMessages);
                Yii::app()->settings->set('Pagination', 'chatMessages', $this->chatMessages);
                Yii::app()->settings->set('Pagination', 'news', $this->news);
                Yii::app()->settings->set('Pagination', 'feedbacks', $this->feedbacks);
                Yii::app()->settings->set('Pagination', 'searchResults', $this->searchResults);       
        }
}

?>
