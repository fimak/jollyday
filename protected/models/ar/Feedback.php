<?php

/**
 * Класс модели для таблицы "feedback" - обратная связь
 *
 * @property integer $id ID сообщения
 * @property integer $id_user ID пользователя
 * @property string $title Заголовок
 * @property string $text Текст
 * @property string $date Дата
 * @property integer $status статус сообщения
 * @property integer $direction направление сообщения (А -> Ю или Ю -> A)
 */
class Feedback extends CActiveRecord
{
        // статус сообщения (прочитано/непрочитано)
        const STATUS_NEW = 0;
        const STATUS_PROCESSED = 1;
               
        /** 
         * @var string поле для проверочного кода с капчи 
         */
        public $captcha;
        
        /**
         * @var поле для сообщения после успешной отправки пользователем формы
         */
        public $successMessage;
              
        /**
         * @var string тема ответного письма
         */
        public $mailSubject;
             
	/**
	 * Метод возвращает статичную модель определённого AR-класса
         * 
	 * @param string $className имя класса ActiveRecord.
	 * @return Feedback статичная модель класса
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
         * Метод возвращает имя связанной с данной моделью таблицы базы данных
         * 
	 * @return string имя таблицы
	 */
	public function tableName()
	{
		return 'feedback';
	}

        /**
         * Метод, возвращающий массив с описанием правил валидации модели
         * 
	 * @return array массив правил валидации
	 */
	public function rules()
	{
		return array(
                        array('email', 'email',
                                'allowEmpty' => false,
                                'message' => 'Электронный адрес введён некорректно',
                                'on' => 'send',
                        ),
                        array('name', 'match', 
                                'allowEmpty' => false, 
                                'pattern' => '/^[\Sa-zA-Zа-яА-ЯёЁ -]+$/iu', 
                                'message' => 'Введите, пожалуйста, имя',
                                'on' => 'send'
                        ),
                        array('is_phone_contact', 'boolean',
                                'allowEmpty' => true,
                                'on' => 'send',
                        ),
                        array('subject', 'in',
                                'allowEmpty' => false,
                                'range' => array_keys(Feedback::getSubjects()),
                                'on' => 'send',
                        ),
                        array('text', 'length',
                                'max' => 4000,
                                'min' => 1,
                                'allowEmpty' => false,
                                'tooLong' => 'Сообщение должно быть длиной менее 4000 символов',
                                'tooShort' => 'Введите, пожалуйста, текст',
                                'message' => 'Введите, пожалуйста, текст',
                                'on' => 'send',
                        ),
                        array('captcha', 'captcha', 
                                'captchaAction' => 'site/captcha', 
                                'message' => 'Проверочный код введён неверно', 
                                'on' => 'send'
                        ),
                        array('answer', 'length',
                                'allowEmpty' => false,
                                'message' => 'Введите, пожалуйста, текст',
                                'on' => 'answer',
                                'min' => 10,
                                'tooShort' => 'Введите, пожалуйста, текст',
                        ),
                        array('mailSubject', 'match',
                                'allowEmpty' => false, 
                                'pattern' => '/^[\Sa-zA-Zа-яА-ЯёЁ -]+$/iu', 
                                'message' => 'Введите, пожалуйста, тему письма',
                                'on' => 'answer',
                        ),
                        array('id, email, subject, name, date, status', 'safe',
                                'on' => 'search'
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
			'id' => 'ID',
			'id_user' => 'ID пользователя',
			'name' => 'Ваше имя',
			'email' => 'Ваш электронный адрес',
                        'phone' => 'Телефон пользователя',
                        'is_phone_contact' => 'Можем ли мы связаться с Вами по телефону?',
                        'subject' => 'Тема Вашего обращения',
                        'text' => 'Текст',
                        'answer' => 'Ответ администратора',
                        'status' => 'Статус обращения',
                        'captcha' => 'Проверочный код',
                        'date' => 'Дата обращения',
                        'mailSubject' => 'Тема письма'
		);
	}

	/**
	 * Возвращает список моделей, соответствующий условиям поиска
         * 
	 * @return CActiveDataProvider поставщик данных, который возвращает
         * список моделей, соответствующий условиям поиска
	 */
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
                $criteria->compare('email',$this->email, true);
		$criteria->compare('subject',$this->subject);
                $criteria->compare('name',$this->name,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'sort'=>array(
                                'defaultOrder' => 'status ASC',
                        ),
                        'pagination' => array(
                                'pageSize' => 50
                        ),
		));
	}
        
        /**
         * Событие, происходящее перед сохранением модели
         * 
         * @return boolean результат срабатывания события
         */
        public function beforeSave()
        {
                if($this->isNewRecord && $this->getScenario() == 'send')
                {
                        $this->status = self::STATUS_NEW;
                        $this->phone = Yii::app()->user->getPhone();
                        $this->id_user = Yii::app()->user->id;
                }
                if($this->getScenario() == 'answer')
                        $this->status = self::STATUS_PROCESSED;
                
                return parent::beforeSave();
        }
                
        /**
         * Возвращает количество новых сообщений в обратной связи
         * 
         * @return integer
         */
        public static function countNew()
        {
                return self::model()->count('status = :statusID', array('statusID' => self::STATUS_NEW));
        }
        
        /**
         * Метод получает соответствующее сообщение после отправки формы
         * 
         * @return type
         */
        public function getSuccessMessage()
        {
                $subjects = self::getMessageTemplates();
                
                switch($this->subject)
                {
                        case 4:
                                $subject = 'gratitude';
                                break;
                        case 5: 
                                $subject = 'history';
                                break;
                        case 0:
                        case 1:
                        case 2:
                        case 3:
                        default:
                                $subject = 'usual';
                                break;
                        
                }
                
                return str_replace('{id}', $this->id, $subjects[$subject]);
        }
        
        public static function getMessageTemplates()
        {
                return array(
                        'usual' => "<b>Ваше обращение принято. Номер обращения: {id}</b>.<br />Ваш персональный менеджер ответит Вам в самое ближайшее время.<br />Спасибо за обращение в службу поддержки!",
                        'gratitude' => "Спасибо Вам за благодарность в наш адрес! :))<br />Мы обязательно отправим ее каждому сотруднику сайта jollyday.ru",
                        'history' => "Спасибо за Вашу историю знакомства! Она очень важна для нас.<br />При возможности мы обязательно поделимся Вашей историей с другими пользователями. Конечно же мы заранее с Вами свяжемся и узнаем, согласны ли Вы на публикацию Вашей истории."
                );
        }
        
        public static function getSubjects()
        {
                return array(
                        0 => 'Обращение на свободную тему',
                        1 => 'Возникла проблема с работой сайта',
                        2 => 'Возникла проблема с оплатой услуг или пополнением счета',
                        3 => 'Хочу вернуть средства на счет мобильного телефона по гарантии',
                        4 => 'Хочу выразить благодарность разработчикам сайта',
                        5 => 'Хочу поделиться своей историей знакомства на сайте',
                );
        }
        
        public static function getShortSubjects()
        {
                return array(
                        0 => 'Разное',
                        1 => 'Баг на сайте',
                        2 => 'Баг с оплатой',
                        3 => 'Вывод средств',
                        4 => 'Благодарность',
                        5 => 'История',
                );
        }
        
        public static function getSubjectDescription($subjectID)
        {
                $data = self::getSubjects();     
                return isset($data[$subjectID]) ? $data[$subjectID] : false;        
        }
        
        public static function getShortSubjectDescription($subjectID)
        {
                $data = self::getShortSubjects();     
                return isset($data[$subjectID]) ? $data[$subjectID] : false;   
        }
          
        public static function getStatusTypes()
        {
                return array(
                        self::STATUS_NEW => 'Не обработано',
                        self::STATUS_PROCESSED => 'Обработано',
                );
        }
          
        public static function getStatusDescription($status)
        {
                $data = self::getStatusTypes();
            
                return isset($data[$status]) ? $data[$status] : 'Неизвестно';
        }   
}