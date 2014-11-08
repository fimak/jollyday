<?php

/**
 * Модель для таблицы "ihave".
 *
 * The followings are the available columns in table 'ihave':
 * @property integer $id
 * @property string $description
 */
class Ihave extends CActiveRecord
{
	/**
	 * Метод возвращает статичную модель определённого AR-класса
         * 
	 * @param string $className имя класса ActiveRecord.
	 * @return Ihave статичная модель класса
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
		return 'im_profile_ihave';
	}     
}