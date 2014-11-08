<?php

/**
 * Модель для таблицы "im_user_meetmethod".
 *
 * @property integer $id ID записи
 * @property integer $id_user ID пользователя
 */
class Meetmethod extends CActiveRecord
{    
	/**
	 * Метод возвращает статичную модель определённого AR-класса
         * 
	 * @param string $className имя класса ActiveRecord.
	 * @return Meetmethod статичная модель класса
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
		return 'im_user_meetmethod';
	}
        
        public function relations()
        {
                return array(
                        array(self::BELONGS_TO, 'User', 'id_user')
                );
        }
}