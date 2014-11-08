<?php

/**
 * Модель для таблицы "city".
 *
 * @property string $id
 * @property string $id_region
 * @property string $name
 */
class City extends CActiveRecord
{
	/**
	 * Метод возвращает статичную модель определённого AR-класса
         * 
	 * @param string $className имя класса ActiveRecord.
	 * @return City статичная модель класса
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
		return 'city';
	}

        /**
         * Метод, возвращающий массив с описанием правил валидации модели
         * 
	 * @return array массив правил валидации
	 */
	public function rules()
	{
		return array(
                        array('id_region, name', 'required'),
			array('id_region', 'length', 'max'=>10),
			array('name', 'length', 'max'=>128, 'min' => 2),
                        array('name', 'match', 'pattern' => '/^[а-яА-ЯёЁ., -]+$/iu', 'message' => 'Недопустимые символы в {attribute}'),
			array('id, id_region, name', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array правила связывания моделей
	 */
	public function relations()
	{
		return array(
                        'parentRegion' => array(self::BELONGS_TO, 'Region', 'id_region'),
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
			'id_region' => 'Регион',
			'name' => 'Город',
		);
	}

	/**
	 * Возвращает список моделей, соответствующий условиям поиска
         * 
	 * @return CActiveDataProvider поставщик данных, который может возврнатить список моделей, соответствующий условиям поиска
	 */
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('id_region',$this->id_region);
		$criteria->compare('name',$this->name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'pagination' => array(
                                    'pageSize' => 50,
                        ),                    
		));
	}
        
        /**
         * Возвращает массив городов по ID региона
         * 
         * @param string $regionID ID региона
         * @return array массив городов в выбранном регионе, столица первая
         */
        public static function getCitiesListByRegion($regionID)
        {
                // получаем города так, что первой будет столица
                $cities = Yii::app()->db->cache(GLOBAL_CACHE_TIME)->createCommand()
                        ->select('city.id AS id, city.name AS name')
                        ->from('city')
                        ->join('region', 'region.id = city.id_region')
                        ->where('region.id = :regionID', array('regionID' => $regionID))
                        ->order('FIELD(city.id, region.id_capital) DESC, city.name ASC')
                        ->queryAll();
                $result = array();
                
                // подготавливаем массив городов для обработки
                foreach($cities as $item => $value)
                        $result[$value['id']] = $value['name'];
                
                return $result;
        }    
}