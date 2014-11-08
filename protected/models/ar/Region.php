<?php

/**
 * Модель для таблицы "region".
 *
 * The followings are the available columns in table 'region':
 * @property string $id
 * @property string $name
 */
class Region extends CActiveRecord
{
        public $capitalName;
    
	/**
	 * Метод возвращает статичную модель определённого AR-класса
         * 
	 * @param string $className имя класса ActiveRecord.
	 * @return Regoin статичная модель класса
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
		return 'region';
	}

        /**
         * Метод, возвращающий массив с описанием правил валидации модели
         * 
	 * @return array массив правил валидации
	 */
	public function rules()
	{
		return array(
			array('name', 'length', 'max'=>64),
                        array('name, timezone', 'required'),
                        array('id_capital', 'exist', 'className' => 'City', 'attributeName' => 'id'),
                        array('name', 'match', 'pattern' => '/^[()а-яА-ЯёЁ., -]+$/iu', 'message' => 'Недопустимые символы в {attribute}'),
                        array('timezone', 'in', 'range' => self::getAvailableTimezones()),
			array('id, name, capitalName, timezone', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
                        'capital' => array(self::BELONGS_TO, 'City', 'id_capital')
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
			'name' => 'Регион',
                        'capitalName' => 'Столица',
                        'id_capital' => 'Столица',
                        'timezone' => 'Временная зона'
		);
	}

	/**
	 * Возвращает список моделей, соответствующий условиям поиска
	 * @return CActiveDataProvider поставщик данных, который может возврнатить список моделей, соответствующий условиям поиска
	 */
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.name',$this->name,true);
                $criteria->compare('t.timezone',$this->timezone);
                $criteria->with = array('capital');
                $criteria->compare('capital.name', $this->capitalName, true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'pagination' => array(
                                    'pageSize' => 100,
                        ),                        
		));
	}
        
        /**
         * @return array массив всех регионов
         */
        public static function getList()
        {
                // получаем города так, что первой будет столица
                $regions = Yii::app()->db->cache(GLOBAL_CACHE_TIME)->createCommand()
                        ->select('id, name')
                        ->from('region')
                        ->order('name ASC')
                        ->queryAll();
                $result = array();
                
                // подготавливаем массив городов для обработки
                foreach($regions as $item => $value)
                        $result[$value['id']] = $value['name'];
                
                return $result;
        }
        
        /**
         * Метод возвращает массив временных зон PHP
         * 
         * @return array массив временных зон PHP
         */
        public static function getAvailableTimezones()
        {
                return array(
                        'Asia/Anadyr' => 'Asia/Anadyr',
                        'Asia/Irkutsk' => 'Asia/Irkutsk',
                        'Asia/Kamchatka' => 'Asia/Kamchatka',
                        'Asia/Krasnoyarsk' => 'Asia/Krasnoyarsk',
                        'Asia/Magadan' => 'Asia/Magadan',
                        'Asia/Novokuznetsk' => 'Asia/Novokuznetsk',
                        'Asia/Novosibirsk' => 'Asia/Novosibirsk',
                        'Asia/Omsk' => 'Asia/Omsk',
                        'Asia/Sakhalin' => 'Asia/Sakhalin',
                        'Asia/Vladivostok' => 'Asia/Vladivostok',
                        'Asia/Yakutsk' => 'Asia/Yakutsk',
                        'Asia/Yekaterinburg' => 'Asia/Yekaterinburg',
                        'Europe/Kaliningrad' => 'Europe/Kaliningrad',
                        'Europe/Moscow' => 'Europe/Moscow',
                        'Europe/Samara' => 'Europe/Samara',
                        'Europe/Volgograd' => 'Europe/Volgograd',
                        'Europe/Yekaterinburg' => 'Europe/Yekaterinburg',  
                );
        }
}