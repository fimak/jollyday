<?php

/**
 * Модель таблицы "_action", нужен только для создания реляционных связей
 */
class Action extends CActiveRecord
{
	/**
	 * Метод возвращает статичную модель определённого AR-класса
         * 
	 * @param string $className имя класса ActiveRecord.
	 * @return Action статичная модель класса
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
		return '_action';
	}

}