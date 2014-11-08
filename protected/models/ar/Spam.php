<?php

/**
 * This is the model class for table "spam".
 *
 * The followings are the available columns in table 'spam':
 * @property integer $id
 * @property integer $id_sender
 * @property integer $id_subject
 * @property integer $date
 */
class Spam extends CActiveRecord
{
        const STATUS_NEW = 0;
        const STATUS_READ = 1;
    
        // поля для поиска по связанным таблицам
        public $senderName;
        public $subjectName;
        public $subjectPhone;
    
	/**
	 * Метод возвращает статичную модель определённого AR-класса
         * 
	 * @param string $className имя класса ActiveRecord.
	 * @return Spam статичная модель класса
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
		return 'spam';
	}

        /**
         * Метод, возвращающий массив с описанием правил валидации модели
         * 
	 * @return array массив правил валидации
	 */
	public function rules()
	{
		return array(
			array('id, senderName, subjectName, subjectPhone, date', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array реляционные связи
	 */
	public function relations()
	{
		return array(
                        'sender' => array(self::BELONGS_TO, 'User', 'id_sender'),
                        'subject' => array(self::BELONGS_TO, 'User', 'id_subject'),
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
			'id_sender' => 'Отправитель',
			'id_subject' => 'Субъект',
			'date' => 'Дата',
                        'subject_phone' => 'Телефон субъекта'
		);
	}

	/**
	 * Метод возвращает списко моделей, удовлетворяющих поиску
         * 
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;
                $criteria->with = array('sender', 'subject');
		$criteria->compare('id',$this->id);
		$criteria->compare('sender.name',$this->senderName, true);
		$criteria->compare('subject.name',$this->subjectName, true);
		$criteria->compare('subject.phone',$this->subjectPhone, true);
		$criteria->compare('date',$this->date);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'sort'=>array(
                                'attributes'=>array(
                                        'sender.name'=>array(
                                                'asc'=>'sender.name',
                                                'desc'=>'sender.name DESC',
                                        ),
                                        'subject.name'=>array(
                                                'asc'=>'subject.name',
                                                'desc'=>'subject.name DESC',
                                        ),
                                        'subject.phone'=>array(
                                                'asc'=>'subject.phone',
                                                'desc'=>'subject.phone DESC',
                                        ),
                                        '*',
                                ),
                        ),
		));
	}
        
        /**
         * Возвращает количество непрочитанных сообщений о спаме
         * 
         * @return integer
         */
        public static function countNew()
        {
                return self::model()->count('status = :status', array('status' => self::STATUS_NEW));
        }
        
        /**
         * Отправляет жалобу на спам
         * 
         * @param integer $senderID ID отправителя жалобы
         * @param integer $subjectID ID субъекта жалобы
         */
        public static function complaint($senderID, $subjectID)
        {
                Yii::app()->db->createCommand()->insert('spam', array(
                        'id_sender' => $senderID,
                        'id_subject' => $subjectID,
                        'date' => date('Y-m-d H:i:s'),
                        'status' => 0
                ));
        }   
}  