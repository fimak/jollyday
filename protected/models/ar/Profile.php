<?php

/**
 * Модель для таблицы "profile".
 * 
 * The followings are the available columns in table 'profile':
 * @property integer $id
 * @property integer $height
 * @property integer $weight
 * @property integer $id_orientation
 * @property integer $id_children
 * @property integer $id_welfare
 * @property integer $id_status
 * @property integer $id_hoursing
 */
class Profile extends CActiveRecord
{
        public $targetIds;
        public $ihaveIds;
        public $seekingIds;    
   
        const HEIGHT_MAX = 250;
        const WEIGHT_MAX = 250;
        
        const AGE_MIN = 18; // мин возраст на сайте
        const AGE_MAX = 80; // макс возраст на сайте
        
        const SEEKING_MALE = 0; // юзер ищет парня
        const SEEKING_FEMALE = 1; // юзер ищет девушку
        const SEEKING_BOTH = 2; // юзер ищет и парня и девушку
        
        const CACHE_PROFILE_DURATION = 3600;
        
	/**
	 * Метод возвращает статичную модель определённого AR-класса
         * 
	 * @param string $className имя класса ActiveRecord.
	 * @return Profile статичная модель класса
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
		return 'profile';
	}

        /**
         * Метод, возвращающий массив с описанием правил валидации модели
         * 
	 * @return array массив правил валидации
	 */
	public function rules()
	{
		return array(
                        array('weight', 'numerical', 'allowEmpty' => true, 'integerOnly' => true , 'min' => 25,'max' => 250, 'tooBig' => 'Вес в интервале 25-250', 'tooSmall' => 'Вес в интервале 25-250'),
                        array('height', 'numerical', 'allowEmpty' => true, 'integerOnly' => true , 'min' => 50,'max' => 250, 'tooBig' => 'Рост в интервале 50-250', 'tooSmall' => 'Вес в интервале 50-250'),
                        array('id_status', 'in', 'range' => JStatus::getIds()),
                        array('id_children', 'in', 'range' => JChildren::getIds()),
                        array('id_housing', 'in', 'range' => JHousing::getIds()),
                        array('id_welfare', 'in', 'range' => JWelfare::getIds()),
                        array('id_orientation', 'in', 'range' => JOrientation::getIds()),
                        array('age_min, age_max', 'numerical', 'allowEmpty' => true, 'integerOnly' => true, 'min' => self::AGE_MIN,'max' => self::AGE_MAX),
                        //array('targetIds', 'in', 'range' => JTarget::getIds(), 'strict' => false),
                        //array('seekingIds', 'in', 'range' => JSeeking::getList(), 'strict' => false),
                        //array('ihaveIds', 'in', 'range' => JIhave::getIds()),
                        array('seekingIds', 'JArrayElementsCountValidator', 'min' => 1),
                        array('targetIds, seekingIds, ihaveIds', 'safe')
                );
	}

	/**
	 * @return array Связывание моделей
	 */
	public function relations()
	{
		return array(
                        'meetTargets'   => array(self::HAS_MANY, 'Target', 'id_profile',
                                'select' => 'id_target'
                        ),
                        'iHave'         => array(self::HAS_MANY, 'Ihave', 'id_profile',
                                'select' => 'id_ihave'
                        ),
		);
	}
 
        /**
         * Метод возвращает массив с описанием подключаемых поведений
         * 
         * @return array массив описания поведений
         */
        public function behaviors()
        {
                return array(
                        'ActiveRecordStaticInteractionBehavior' => array(
                                'class' => 'ActiveRecordStaticInteractionBehavior',
                                'foreignKey' => 'id_profile'
                        )
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
			'height' => 'Рост',
			'weight' => 'Вес',
			'id_children' => 'Есть ли у Вас дети?',
			'id_welfare' => 'Материальное положение',
			'id_status' => 'Состоите ли Вы в отношениях?',
			'id_housing' => 'Наличие жилья',
                        'id_orientation' => 'Ориентация',
                        'age_min' => 'Минимальный возраст поиска',
                        'age_max' => 'Максимальный возраст поиска',
                        'targetIds' => 'Цель знакомства',
                        'ihaveIds' => 'У меня есть',
                        'seekingIds' => 'Хочу найти',                       
		);
	}

	/**
	 * Возвращает список моделей, соответствующий условиям поиска
	 * @return CActiveDataProvider поставщик данных, который может возврнатить список моделей, соответствующий условиям поиска
	 */
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('height',$this->height);
		$criteria->compare('weight',$this->weight);
		$criteria->compare('id_orientation',$this->id_orientation);
		$criteria->compare('id_children',$this->id_children);
		$criteria->compare('id_welfare',$this->id_welfare);
		$criteria->compare('id_status',$this->id_status);
		$criteria->compare('id_housing',$this->id_hoursing);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'pagination' => array(
                                    'pageSize' => 25,
                        ),
		));
	}
        
        /**
         * Метод возвращает модель анкеты пользователя по id пользователя
         * 
         * @param type $userID Id пользователя
         * @return type Модель юзера
         */
        public static function loadProfileByUser($userID)
        {
                $profile = Profile::model()->findByAttributes(array('id_user' => $userID));
                if($profile == null)
                        throw new CHttpException('404', 'Профиль не существует');
                return $profile;          
        }
               
        /**
         * Событие, происходящее после поиска с помощью find-методов модели
         */
        public function afterFind()
        {
                $this->relateData(); 
                
                // из ID цели знакомства делаем массив
                switch($this->id_seeking)
                {
                        case self::SEEKING_MALE :
                                $this->seekingIds = array(0 => 0);
                                break;
                        case self::SEEKING_FEMALE :
                                $this->seekingIds = array(1 => 1);
                                break;
                        case self::SEEKING_BOTH :
                                $this->seekingIds = array(0 => 0, 1 => 1);
                                break;
                }
                
                parent::afterFind();
        }
                
        /**
         * Метод получает цели знакомства в виде массива
         */
        public function targetList()
        {
                $data = array();
                               
                if(!isset($this->meetTargets))
                        return $data;
                
                foreach($this->meetTargets as $target)
                        $data[] = JTarget::getDescription($target->id_target);
                
                return $data;
        }
       
        /**
         * Метод получает список чем владеет юзер в виде массива
         */
        public function ihaveList()
        {
                $data = array();
                
                if(!isset($this->iHave))
                        return $data;
                
                foreach($this->iHave as $having)
                        $data[] = JIhave::getDescription($having->id_ihave);
                
                return $data;
        }   
          
        /**
         * Метод для связывания таблиц
         */
        private function relateData()
        {
                // выбираем id из связующих таблиц
                $this->getRelatedStaticData('meetTargets', 'targetIds', 'id_target');
                $this->getRelatedStaticData('iHave', 'ihaveIds', 'id_ihave');        
        }
               
        /**
         * Метод возвращает массив данных для списка допустимых лесов
         * 
         * @return array массив
         */
        public static function getWeightList()
        {
                $data = array('' => '');
                
                for($i = 40; $i <= self::WEIGHT_MAX; $i++)
                        $data[$i] = $i;
                
                return $data;
        }
        
        /**
         * Метод возвращает массив данных для списка допустимых ростов
         * 
         * @return array массив
         */
        public static function getHeightList()
        {
                $data = array('' => '');
                
                for($i = 120; $i <= self::HEIGHT_MAX; $i++)
                        $data[$i] = $i;
                
                return $data;
        }
        
        /**
         * Метод возвращает список доступных возрастов пользователя
         * 
         * @return array массив допустимых возрастов
         */
        public static function getAgeList()
        {  
                $result = array();
                
                for($i = self::AGE_MIN; $i <= self::AGE_MAX; $i++)
                        $result[$i] = $i;
                
                return $result;
        }
        
        /**
         * Метод возфращает отформатированное значение интервала
         * предпочитаемых возрастов пользователя
         * 
         * @param integer $min Минимальный возраст
         * @param integer $max Максимальный возраст
         * @return string результат
         */
        public static function formatAgeInterval($min, $max, $separator=' - ')
        {
                if($min == $max && !empty($min) && !empty($max))
                {
                        $string = "$min лет";    
                }
                elseif(!empty($min) && !empty($max))
                {
                        $string = "$min".$separator."$max лет";
                }
                elseif(empty($min) && empty($max))
                {
                        $string = '';
                }
                elseif(empty($min) || empty($max))
                {
                        if(empty($min))
                                $string = "до $max " . (($max % 10 == 1) ? 'года' : 'лет');
                        if(empty($max))
                                $string = "от $min " . (($min % 10 == 1) ? 'года' : 'лет');
                }
                else
                {
                        $string = '';
                }
                
                return $string;
        }
        
        public static function formatSearchTitleAge($min, $max)
        {
                if($min == $max && !empty($min) && !empty($max))
                {
                    $string = ' в возрасте ' . self::formatAgeInterval($min, $max);
                }
                elseif(!empty($min) && !empty($max))
                {
                    $string = ' в возрасте от ' . self::formatAgeInterval($min, $max, " до ");
                }
                elseif(empty($min) && empty($max))
                {
                        $string = '';
                }
                elseif(empty($min) || empty($max))
                {
                        if(empty($min))
                                $string = ' в возрасте ' . self::formatAgeInterval($min, $max, " до ");
                        if(empty($max))
                                $string = ' в возрасте от ' . self::formatAgeInterval($min, $max, " до ");
                }
                return $string;
        }
        
        /**
         * Метод получает отформатированное значение цели поиска пользователя
         * 
         * @param integer $seekingID ID цели поиска
         * @param string $case падеж : nominative - именительный, genitive - родительный, ablative - творительный
         * @return string текстовое описание пола
         */
        public static function formatSeeking($seekingID, $case = 'nominative')
        {
                if(!in_array($case, array('nominative','genitive','ablative')))
                        throw new Exception('Падеж должен принимать значения : nominative, genitive, ablative');
                
                $string = '';
                
                if($seekingID == self::SEEKING_MALE || $seekingID == self::SEEKING_FEMALE)
                        $string = JGender::formatGender($seekingID, $case);
                if($seekingID == self::SEEKING_BOTH)
                {
                        switch($case)
                        {
                                case 'nominative' :
                                        $string = 'парень или девушка';
                                        break;
                                case 'genitive' :
                                        $string = 'парня или девушку';
                                        break;
                                case 'ablative' :
                                        $string = 'парнем или девушкой';
                                        break;
                        }
                }
                        
                return $string;           
        }
                
        /**
         * Событие, происходящее перед сохранением модели
         * 
         * @return boolean результат срабатывания события
         */
        public function beforeSave()
        {       
                // сохраняем связанные данные
                $this->saveRelatedStaticData('im_profile_target', 'targetIds', 'id_target', $this->id); 
                $this->saveRelatedStaticData('im_profile_ihave', 'ihaveIds', 'id_ihave', $this->id); 
      
                // минимальный возраст не должен быть больше максимального
                if($this->age_min > $this->age_max && !empty($this->age_min) && !empty($this->age_max))
                        $this->age_min = $this->age_max;
                
                // формируем значение цели поиска
                $values = array_values($this->seekingIds);
                if(count($values) == 2 && in_array(self::SEEKING_MALE, $values) && in_array(self::SEEKING_FEMALE, $values))
                        $this->id_seeking = self::SEEKING_BOTH;
                elseif(in_array(self::SEEKING_MALE, $values))
                         $this->id_seeking = self::SEEKING_MALE;
                elseif(in_array(self::SEEKING_FEMALE, $values))
                         $this->id_seeking = self::SEEKING_FEMALE;
                             
                return parent::beforeSave();          
        }
}