<?php

/**
 * Модель для таблицы "history".
 *
 */
class History extends CActiveRecord
{
        // типы событий
        const EVENT_NEWMAIL = 0;
        const EVENT_NEWPHONE = 1;
    
    
	/**
	 * Метод возвращает статичную модель определённого AR-класса
         * 
	 * @param string $className имя класса ActiveRecord.
	 * @return History статичная модель класса
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
		return 'history';
	}

        /**
         * Метод, возвращающий массив с описанием правил валидации модели
         * 
	 * @return array массив правил валидации
	 */
	public function rules()
	{
		return array(
			array('id_event, date, message', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
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
			'id_user' => 'ID Пользователя',
			'id_event' => 'Тип события',
			'date' => 'Дата',
			'message' => 'Сообщение',
		);
	}

	/**
	 * Возвращает список моделей, соответствующий условиям поиска
	 * @return CActiveDataProvider поставщик данных, который может возврнатить список моделей, соответствующий условиям поиска
	 */
	public function search($idUser = null)
	{

		$criteria=new CDbCriteria;

		$criteria->compare('id_event',$this->id_event);
                $criteria->compare('date', $this->date, true);
                $criteria->compare('message', $this->message, true);
                $criteria->compare('id_user', $idUser);
                
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'pagination' => array(
                                'pageSize' => 100,
                        ),
		));
	}
        
        /**
         * @return array Список логируемых событий
         */
        public static function getEventsList()
        {
                return array(
                        self::EVENT_NEWMAIL => 'Смена электронной почты',
                        self::EVENT_NEWPHONE => 'Смена номера телефона',
                );
        }
        
        /**
         * Метод возвращает описание события по его ID
         * 
         * @param integer $eventId ID события
         * @return string Описание собтыия
         */
        public static function getEventDescription($eventId)
        {
                $data = self::getEventsList();
                
                return isset($data[$eventId]) ? $data[$eventId] : 'Неизвестно';
        }
        
        /**
         * Метод создаёт запись в таблице истории
         * 
         * @param type $id_user ID пользователя
         * @param type $id_event ID события
         * @param type $message Сообщение под запись
         */
        public static function log($id_user, $id_event, $message)
        {
                $date = date('Y-m-d H:i:s');
                
                $sql = "INSERT INTO `history` (`id_user`, `id_event`, `date`, `message`)
                            VALUES ('".$id_user."', '".$id_event."', '".$date."', '".$message."')
                ";
                
                Yii::app()->db->createCommand($sql)->execute();
        }       
}