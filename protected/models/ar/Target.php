<?php

/**
 * This is the model class for table "im_profile_target".
 *
 * The followings are the available columns in table 'im_profile_target':
 * @property integer $id
 * @property integer $id_target
 * @property integer $id_profile
 */
class Target extends CActiveRecord
{
	/**
	 * Метод возвращает статичную модель определённого AR-класса
         * 
	 * @param string $className имя класса ActiveRecord.
	 * @return Target статичная модель класса
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
		return 'im_profile_target';
	}
}