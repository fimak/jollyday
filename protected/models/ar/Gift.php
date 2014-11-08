<?php

/**
 * Модель подарка. 
 * 
 * Изображение подарка будет храниться в папке images/gift (можно изменять)
 * При этом в таблицу не прописывается путь до изображения, к изображению подарка можно будет 
 * обратиться по его id подарка. Например, для подарка с Id = 12, будет изображение
 * images/gift/12.png
 * 
 * Тут важно, чтобы изображения подарков имели одинаковое расширение (рекомендуется png), хотя при
 * необходимости можно сделать поддержку любых форматов
 * 
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property double $cost
 */
class Gift extends CActiveRecord
{      
        /** @var CUploadedFile поле для загруженного файла с маленьким изображением подарка */
        public $uploadedFile;
        
        /** @var CUploadedFile поле для загруженного файла с крупным изображением подарка */
        public $uploadedFileBig;
        
        /** @var string ссылка на маленькое изображение подарка */
        public $imageURL;
        
        /** @var string ссылка на крупное изображение подарка */
        public $imageURLBig;
       
        const IMAGE_FOLDER = 'gifts';
        
        const WIDTH = 82;
        const HEIGHT = 82;
        
        const WIDTH_BIG = 240;
        const HEIGHT_BIG = 240;

        const COUNT_ON_PROFILE = 9;
        
        const BIG_SUFFIX = '_big';
        
        const LIST_CACHE_ID = 'giftlist';
        
	/**
	 * Метод возвращает статичную модель определённого AR-класса
         * 
	 * @param string $className имя класса ActiveRecord.
	 * @return Gift статичная модель класса
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
		return 'gift';
	}

        /**
         * Метод, возвращающий массив с описанием правил валидации модели
         * 
	 * @return array массив правил валидации
	 */
	public function rules()
	{
		return array(
                        array('title, cost', 'required', 
                                'message' => 'Поле обязательно для заполнения'
                        ),
			array('cost', 'numerical', 
                                'message' => 'Цена должна быть или целым числом или с точкой'
                        ),
			array('title', 'length', 
                                'max'=>32, 
                                'allowEmpty' => true, 
                                'tooLong' => 'Слишком длинное название подарка'
                        ),
                        array('title', 'unique',
                                'message' => 'Название подарка должно быть уникальным',
                        ),
                        array('title', 'match',
                                'pattern' => '/^[0-9a-zA-Z_-]+$/iu',
                                'message' => 'Недопустимые символы в имени (только a-Z, дефис и подчёркивание)'
                        ),
			array('id, title, cost, weight', 'safe', 
                                'on'=>'search'
                        ),
                        array('weight', 'numerical',
                                'integerOnly' => true,
                                'message' => 'Вес для сортировки должен быть целым числом',
                        ),
                        array('uploadedFile, uploadedFileBig', 'file',
                                'allowEmpty' => true,
                                'maxFiles' => 1,
                                'maxSize' => 1024 * 1024,
                                'types' => 'gif, png, jpg',
                                'tooMany' => 'Можно загрузить только один файл',
                                'tooLarge' => 'Максимальный размер файла 256 КБ',
                        ),
                        array('uploadedFile, uploadedFileBig', 'required', 
                                'on' => 'create',
                                'message' => 'Необходимо загрузить файл'
                            
                        ),        
                        array('uploadedFile', 'application.validators.JImageDimensionsValidator',
                                'maxWidth'  => self::WIDTH, 
                                'maxHeight' => self::HEIGHT, 
                                'minHeight' => self::WIDTH, 
                                'minWidth'  => self::HEIGHT,
                                'errorIfNotImage' => false                                                          
                        ),
                        array('uploadedFileBig', 'application.validators.JImageDimensionsValidator',
                                'maxWidth'  => self::WIDTH_BIG, 
                                'maxHeight' => self::HEIGHT_BIG, 
                                'minHeight' => self::WIDTH_BIG, 
                                'minWidth'  => self::HEIGHT_BIG,
                                'errorIfNotImage' => false                                                          
                        ),
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
			'title' => 'Название',
			'cost' => 'Цена',
                        'uploadedFile' => 'Изображение',
                        'image' => 'Изображение',
                        'uploadedFileBig' => 'Большое изображение',
                        'imageBig' => 'Большое изображение',
                        'weight' => 'Вес',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('cost',$this->cost);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'pagination' => array(
                                'pageSize' => 50,
                        ),
		));
	}
        
        /**
         * Метод возвращает путь до папки с изображениями
         * 
         * @return type
         */
        public static function getImagePath()
        {
                return WEBROOT . DS . 'images' . DS . self::IMAGE_FOLDER . DS;
        }
        
        /**
         * Метод возвращает путь до папки с изображениями
         * 
         * @return type
         */
        public static function getImageBaseUrl()
        {
                return '/images/' . self::IMAGE_FOLDER . '/';
        }
        
        /**
         * Событие выполняемое после удаления записи из таблицы
         * 
         * @return type
         */
        protected function afterDelete()
        {
                $path = self::getImagePath() . $this->image;
                if(is_file($path))
                        unlink($path);
                
                $pathBig = self::getImagePath() . $this->image_big;
                if(is_file($pathBig))
                        unlink($pathBig);                
                
                return parent::afterDelete();
        }
        
        /**
         * Событие, происходящее после поиска с помощью find-методов модели
         */
        protected function afterFind()
        {
                parent::afterFind();
                
                $this->imageURL = Yii::app()->baseUrl . '/' . 'images' . '/' . self::IMAGE_FOLDER . '/' . $this->image;
                $this->imageURLBig = Yii::app()->baseUrl . '/' . 'images' . '/' . self::IMAGE_FOLDER . '/' . $this->image_big;
        }
        
        /**
         * Событие, происходящее после сохранения модели
         * 
         * @return boolean результат срабатывания события
         */
        protected function afterSave()
        {
                // определяем директорию сохранения подарков
                $dir = Gift::getImagePath();
                if(!is_dir($dir))
                        mkdir($dir);
                           
                if($this->uploadedFile != null)
                        $this->uploadedFile->saveAs($dir . $this->image);
                
                if($this->uploadedFileBig != null)          
                        $this->uploadedFileBig->saveAs($dir . $this->image_big);
                
                return parent::afterSave();
        }


        /** 
         * Метод получает массив подарков, сгруппированных по цене
         * 
         * @return array
         */ 
        public static function getList()
        {
                $giftList = Yii::app()->cache->get(self::LIST_CACHE_ID);
            
                if($giftList === false)
                {
                        $critetia = new CDbCriteria();
                        $critetia->order = 'cost ASC, weight ASC';

                        $models = self::model()->findAll($critetia);

                        $giftList = array();

                        foreach($models as $item)
                                $giftList[floor($item->cost)][] = $item;
                        
                        Yii::app()->cache->set(self::LIST_CACHE_ID, $giftList);
                }
                
                return $giftList;
        }
   
        /**
         * Метод очищает открытку
         * 
         * @param type $giftID
         * @param type $userID
         * @return type
         */
        public static function deletePostcard($giftID, $userID)
        {            
                return Yii::app()->db->createCommand()
                        ->update('im_user_gift', 
                                array(
                                        'postcard' => null,
                                ), 
                                'id_reciever = :userID AND id = :giftID', 
                                array(
                                        'userID' => $userID,
                                        'giftID' => $giftID
                                )
                );
        }
}