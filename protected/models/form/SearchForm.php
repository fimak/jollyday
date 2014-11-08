<?php

/**
 * Модель формы поиска в модуле зарегистрированного пользователя
 */

class SearchForm extends CFormModel
{
        /**
         * @var string пол
         */
        public $gender;
        
        /**
         * @var integer минимальный возраст
         */
        public $minAge;
        
        /**
         * @var integer максимальный возраст
         */
        public $maxAge;
        
        /**
         * @var integer ID региона
         */
        public $id_region;
        
        /**
         * @var integer ID города
         */
        public $id_city;
        
        /**
         * @var integer ID способа знакомства
         */
        public $id_meetmethod;
        
        
        /**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
                    array('gender, minAge, maxAge, id_region, id_city, id_meetmethod', 'safe'),
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
                        'id_region'=>'Регион:',
                        'id_city'=>'Город:',
                        'gender'=>'Я ищу:'
		);
        }
        
        /**
         * Метод возвращает критерий поиска, исходя из полученных формой данных
         * 
         * @return CDbCriteria критерий поиска
         */
        public function buildSearchCriteria()
        {                   
                $criteria=new CDbCriteria;
                
                $criteria->select = '
                        t.id,
                        t.name,
                        t.id_userpic,
                        t.birthday,
                        t.id_gender
                ';

                $criteria->addCondition('t.register_step = 0 AND t.fl_deleted = 0 AND t.id_userpic IS NOT NULL');
            
                // условия пола юзера
                if($this->gender != null)
                        $criteria->compare('t.id_gender', $this->gender);          
                
                // установка интервалов даты рождения
                // $valueStart зависит от $this->maxAge, и наоборот
                // переменные для дат, задаваемых полями
                
                $valueStart = new DateTime('1900-01-01');
                $valueEnd = new DateTime;
                        
                if(!empty($this->minAge) || !empty($this->maxAge))
                { 
                        // ставим интервалы в зависимости от интервала возрастов
                        if (!empty($this->maxAge))
                        {   
                                $valueStart = new DateTime;
                                $valueStart->sub(new DateInterval("P".($this->maxAge + 1)."Y"));
                        }
                        if (!empty($this->minAge))
                                $valueEnd->sub(new DateInterval('P'.$this->minAge.'Y'));
                }
                
                $valueStart = $valueStart->format('Y-m-d');
                $valueEnd = $valueEnd->format('Y-m-d');
                
                $criteria->addCondition("t.birthday BETWEEN STR_TO_DATE('$valueStart', '%Y-%m-%d') AND STR_TO_DATE('$valueEnd', '%Y-%m-%d')");
                
                // условие по региону
                if($this->id_region != null)
                        $criteria->compare('t.id_region', $this->id_region);
                
                // условие по городу
                if($this->id_city != null)
                        $criteria->compare('t.id_city', $this->id_city);
                
                // условие по способу знакомства
                if($this->id_meetmethod != null)
                {
                        $criteria->join .= ' JOIN im_user_meetmethod meetmethods ON meetmethods.id_user = t.id';
                        $criteria->compare('meetmethods.id_meetmethod', $this->id_meetmethod);
                }        
                
                $criteria->order = 't.date_rating DESC';
                       
                return $criteria;
        }
}