<?php

/**
 * Модель формы поиска в модуле зарегистрированного пользователя
 */

class Search extends CFormModel
{
        const RESULT_PROFILE = 0;
        const RESULT_PHOTO = 1;

        /**
         * @var integer ID цели поиска
         */
        public $id_seeking;
        
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
         * @var boolean искать ли пользователей только с фотографиями
         */
        public $withPhoto;
        
        /**
         * @var boolean искать ли пользователей, зарегистрировавшихся только за последние сутки
         */
        public $isNewProfile;
        
        /**
         * @var boolean искать ли только пользователей онлайн
         */
        public $isOnline;
        
        /**
         * @var boolean тип результата поиска (фотографии или минианкеты)
         */
        public $resultType;
        
        /**
         * @var boolean не показывать ли пользователей, с которыми уже установлен контакт
         */
        public $isDontShowOffered;
        
        /**
         * @var boolean вспомогательное поле для сохранения значения расширенного поиска 
         */
        public $oldExtendedSearch;
        
        /**
         * @var boolean метка, используется ли расширенный поиск
         */
        public $isExtendedSearch = 0;
        
        /**
         * @var array массив ID целей знакомства
         */
        public $targetIds = array();
        
        /**
         * @var array массив искомых ID ориентации пользователя
         */
        public $orientationIds = array();
        
        /**
         * @var array массив искомых ID семейного положения пользователя
         */
        public $statusIds = array();
        
        /**
         * @var array массив искомых ID наличия детей у пользователя
         */
        public $childrenIds = array();
        
        /**
         * @var integer минимальный рост
         */
        public $minHeight;
        
        /**
         * @var integer максимальный рост
         */
        public $maxHeight;
        
        /**
         * @var integer минимальный вес
         */
        public $minWeight;
        
        /**
         * @var integer максимальный вес
         */
        public $maxWeight;
        
        /**
         * @var array массив искомых ID материального обеспечения пользовтаеля
         */
        public $welfareIds = array();
        
        /**
         * @var array массив искомых ID жилищных условий пользователя
         */
        public $housingIds = array();
        
        /**
         * @var array массив искомых ID точго, что есть у пользователя
         */
        public $iHaveIds = array();
        
        
        /**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
                    array('id_seeking, minAge, maxAge, id_region, id_city, id_meetmethod, 
                            withPhoto, isNewProfile, isOnline, isDontShowOffered, isExtendedSearch,
                            targetIds, statusIds, childrenIds, minHeight, maxHeight, 
                            minWeight, maxWeight, welfareIds, housingIds, iHaveIds, orientationIds, resultType', 'safe'),
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
                        'id_region'=>'Регион',
                        'id_city'=>'Город',
		);
        }
        
        /**
         * Метод возвращает критерий поиска, исходя из полученных формой данных
         * 
         * @return CDbCriteria критерий поиска
         */
        public function buildSearchCriteria($userId)
        {       
                // если пусто, то ставим 0, так надо
                $this->isExtendedSearch = empty($this->isExtendedSearch) ? 0 : $this->isExtendedSearch;
                $this->oldExtendedSearch = $this->isExtendedSearch;
                            
                $criteria=new CDbCriteria;
                
                $criteria->select = '
                        t.id,
                        t.name,
                        t.id_userpic,
                        t.birthday,
                        t.id_gender
                ';

                $criteria->join = 'JOIN profile profile ON profile.id_user = t.id';
                $criteria->addCondition('t.register_step = 0 AND t.fl_deleted = 0');
                
                
                $criteria->group = 't.id';
                
                // условие в зависимости от пола юзера, от пола того, кого ищет
                // и от ориентации того, кого ищут
                if($this->id_seeking != null)
                {
                        if($this->id_seeking == 2)
                        {                                             
                                $this->isExtendedSearch = 1;
                                $this->targetIds = $this->targetIds != null ? $this->targetIds + array((string)JTarget::FRIENDSHIP) : array((string)JTarget::FRIENDSHIP);
                        }
                        elseif(Yii::app()->user->getGender() == JGender::MALE &&  $this->id_seeking == JGender::MALE)
                        {
                                $criteria->addCondition('profile.id_orientation IN (2,3) OR profile.id_orientation IS NULL');
                                $criteria->compare('t.id_gender', JGender::MALE);
                        }
                        elseif(Yii::app()->user->getGender() == JGender::MALE &&  $this->id_seeking == JGender::FEMALE)
                        {
                                $criteria->addCondition('profile.id_orientation IN (1,2) OR profile.id_orientation IS NULL');
                                $criteria->compare('t.id_gender', JGender::FEMALE);
                        }
                        elseif(Yii::app()->user->getGender() == JGender::FEMALE &&  $this->id_seeking == JGender::MALE)
                        {
                                $criteria->addCondition('profile.id_orientation IN (1,2) OR profile.id_orientation IS NULL');
                                $criteria->compare('t.id_gender', JGender::MALE);
                        }
                        elseif(Yii::app()->user->getGender() == JGender::FEMALE &&  $this->id_seeking == JGender::FEMALE)
                        {
                                $criteria->addCondition('profile.id_orientation IN (2,3) OR profile.id_orientation IS NULL');
                                $criteria->compare('t.id_gender', JGender::FEMALE);                             
                        }                        
                }
                
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
                {
                        $criteria->compare('t.id_region', $this->id_region);
                }
                // условие по городу
                if($this->id_city != null)
                        $criteria->compare('t.id_city', $this->id_city);
                
                // условие по способу знакомства
                if($this->id_meetmethod != null)
                {
                        $criteria->join .= ' JOIN im_user_meetmethod meetmethods ON meetmethods.id_user = t.id';
                        $criteria->compare('meetmethods.id_meetmethod', $this->id_meetmethod);
                }        
                
                // условие проверки на новый профиль
                if($this->isNewProfile != 0)
                {
                        $now = Yii::app()->localtime->getUTCNow();
                        $expireTime = new DateTime;    
                        $expireTime = $expireTime->sub(new DateInterval("P1D"))->format('Y-m-d H:i:s');
                        
                        $criteria->addCondition("t.date_register > STR_TO_DATE('$expireTime', '%Y-%m-%d %H:%i:%s')");
                }
                // проверка на фото
                if($this->withPhoto == 1)
                        $criteria->addCondition ('t.id_userpic IS NOT NULL');
                
                // проверка на онлайн статус
                if($this->isOnline == 1)
                {
                        $now = Yii::app()->localtime->getUTCNow();
                        $expireTime = new DateTime;    
                        $expireTime = $expireTime->sub(new DateInterval("PT15M"))->format('Y-m-d H:i:s');
                        
                        $criteria->join .= ' JOIN _action ON _action.id_user = t.id';
                        $criteria->addCondition("_action.date > STR_TO_DATE('$expireTime', '%Y-%m-%d %H:%i:%s')");
                }
                

                // <РАСШИРЕННЫЙ ПОИСК>
                
                if(!empty($this->isExtendedSearch))
                {
                        // условие по целям знакомства
                        if(!empty($this->targetIds))
                        {                             
                                $criteria->join .= ' LEFT JOIN im_profile_target meetTargets ON meetTargets.id_profile = profile.id';
                                $criteria->addInCondition('meetTargets.id_target', $this->targetIds);                                                  
                        }
                        if(!empty($this->orientationIds))
                                $criteria->addInCondition('id_orientation', $this->orientationIds);

                        if(!empty($this->statusIds))
                                $criteria->addInCondition('id_status', $this->statusIds);

                        if(!empty($this->childrenIds))
                                $criteria->addInCondition('id_children', $this->childrenIds);

                        if(!empty($this->welfareIds))
                                $criteria->addInCondition('id_welfare', $this->welfareIds);

                        if(!empty($this->housingIds))
                                $criteria->addInCondition('id_housing', $this->housingIds);

                        // условие по росту
                        if(!empty($this->minHeight) || !empty($this->maxHeight))
                        {
                                $valueStart = $this->minHeight != null ? $this->minHeight : 0;
                                $valueEnd = $this->maxHeight != null ? $this->maxHeight : Profile::HEIGHT_MAX;

                                $criteria->addBetweenCondition('height', $valueStart, $valueEnd);    
                        }

                        // условие по весу
                        if(!empty($this->minWeight) || !empty($this->maxWeight))
                        {
                                $valueStart = $this->minWeight != null ? $this->minWeight : 0;
                                $valueEnd = $this->maxWeight != null ? $this->maxWeight : Profile::WEIGHT_MAX;

                                $criteria->addBetweenCondition('weight', $valueStart, $valueEnd);  
                        }

                        // условия по обладанию чем-либо
                        if(!empty($this->iHaveIds))
                        {
                                $criteria->join .= ' JOIN im_profile_ihave  iHave ON iHave.id_profile = profile.id';
                                $criteria->addInCondition('id_ihave', $this->iHaveIds);
                        }
                }
                
                // присваиваем полученное из формы значение расширенного поиска
                $this->isExtendedSearch = $this->oldExtendedSearch;
                /* </РАСШИРЕННЫЙ ПОИСК> */
                                 
                // исключение id пользователей из чёрного списка, самого себя и
                // пользователей, с которыми налажен контакт
                $excluded = $this->excludeUsers($this->isDontShowOffered);
                             
                $criteria->addNotInCondition('t.id', $excluded);
                
                $criteria->order = 't.date_rating DESC';
                       
                return $criteria;
        }
        
        /**
         * Метод заполняет поля формы по-умолчанию, если не выставлена кука
         * со значениями полей
         */
        public function defaultFields()
        {
                if(Yii::app()->user->isGuest)
                        return;
                
                if(!$this->setFieldsFromCookie())
                {                      
                        $user = User::model()->findByPk(Yii::app()->user->id);
                        
                        if($user->id_gender == JGender::MALE )
                                $this->id_seeking = JGender::FEMALE;
                        else
                                $this->id_seeking = JGender::MALE;
                        
                        $this->minAge = $user->profile->age_min;
                        $this->maxAge = $user->profile->age_max;                      
                        $this->id_region = $user->id_region;
                        $this->id_city = $user->id_city;
                        $this->withPhoto = 1;
                        $this->isNewProfile = 0;
                        $this->isOnline = 0;                    
                        $this->resultType = self::RESULT_PROFILE;
                        $this->isDontShowOffered = 1;
                }
        }
        
        /**
         * Метод заполняет поля формы значениями из соответсвующей куки
         */
        public function setFieldsFromCookie()
        {
                if(Yii::app()->user->isGuest)
                        return; 
            
                if(isset(Yii::app()->request->cookies['search']->value))
                {
                        $cookieString = Yii::app()->request->cookies['search']->value;
                        if($this->validateSearchCookie($cookieString))
                        {
                                list(
                                        $this->id_seeking,
                                        $this->minAge,
                                        $this->maxAge,
                                        $this->id_region,
                                        $this->id_city,
                                        $this->withPhoto,
                                        $this->isNewProfile,
                                        $this->isOnline,
                                        $this->resultType,
                                        $this->isDontShowOffered,
                                ) = explode('s', Yii::app()->request->cookies['search']->value);

                                return true;
                        }
                        else
                        {
                                unset(Yii::app()->request->cookies['search']);
                                return false;
                        }
                }
        }
        
        /**
         * Метод устанавливает куку со значениями формы при поиске для
         * их сохранения
         */
        public function setSearchCookie()
        {
                if(Yii::app()->user->isGuest)
                        return; 
            
                $string = implode('s', array(
                        $this->id_seeking,
                        $this->minAge,
                        $this->maxAge,
                        $this->id_region,
                        $this->id_city,
                        $this->withPhoto,
                        $this->isNewProfile,
                        $this->isOnline,
                        $this->resultType,
                        $this->isDontShowOffered,
                ));
                
                $cookie = new CHttpCookie('search', $string);
                $cookie->expire = time() + 60 * 60 * 24 * 30;                                
                Yii::app()->request->cookies['search'] = $cookie;
        }
        
        /**
         * Получаем все ID пользователей которых нужно исключить из поиска
         * 
         * @return array массив ID исключённых из поиска пользователей
         */
        public function excludeUsers()
        {       
                $currentUserID = Yii::app()->user->id;
            
                $sql = "
                    SELECT blacklist.id_blacklisted AS id FROM blacklist WHERE blacklist.id_user = ".$currentUserID." UNION 
                            SELECT blacklist.id_user AS id FROM blacklist WHERE blacklist.id_blacklisted = ".$currentUserID."
                ";
                
                if($this->isDontShowOffered)
                        $sql .=  "UNION SELECT offer.id_reciever AS id FROM offer WHERE offer.id_sender = ".$currentUserID." UNION
                                            SELECT offer.id_sender AS id FROM offer WHERE offer.id_reciever = ".$currentUserID."
                        ";
                
                $idS = Yii::app()->db->createCommand($sql)->queryColumn();                     
                $idS[] = $currentUserID;
                                                                
                return $idS;              
        }
        
        /**
         * Метод возвращает список типов результата поиска
         * 
         * @return array список типов результатов поиска
         */
        public static function getResultTypeList()
        {
                return array(
                        self::RESULT_PHOTO => 'фотографии',
                        self::RESULT_PROFILE => 'анкеты'
                );
        }
        
        /**
         * Метод проверяет корректность куки для поиска
         * 
         * @param string $cookieString значение cookie
         * @return boolean результат валидации
         */
        private function validateSearchCookie($cookieString)
        {
                $pattern = '/\d{0,11}s\d{0,11}s\d{0,11}s\d{0,11}s\d{0,11}s\d{0,11}s\d{0,11}s\d{0,11}s\d{0,11}/';
                return preg_match($pattern, $cookieString);           
        }
}