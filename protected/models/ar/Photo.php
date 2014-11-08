<?php

/**
 * Модель для таблицы "photo".
 *
 * The followings are the available columns in table 'photo':
 * @property integer $id
 * @property integer $id_album
 * @property integer $id_user
 * @property string $description
 * @property string $date_add
 * @property string $path_big
 * @property string $path_medium
 * @property string $path_small
 */
class Photo extends CActiveRecord
{
        // размеры большой загруженной фотографии
        const SIZE_BIG_X = 800;
        const SIZE_BIG_Y = 600;
        
        // размеры миниатюры загруженной фотографии (большого квадратного юзерпика)
        const SIZE_MEDIUM_X = 240;
        const SIZE_MEDIUM_Y = 240;
        
        // размеры миниатюры для мордоленты
        const SIZE_FACERIBBON_X = 46;
        const SIZE_FACERIBBON_Y = 61;
        
        // размеры маленького квадратного юзерпика
        const SIZE_SMALL_X = 43;
        const SIZE_SMALL_Y = 43;
                
        // имя папки с фотографиями относительно корня сайта
        const UPLOAD_FOLDER = 'photo';
        
        // качество изображения
        const THUMBNAIL_QUALITY = 95;
        const THUMBNAIL_SHARPEN_AMOUNT = 20;
        
        // поля для ссылок на файлы
        public $bigURL;
        public $mediumURL;
        public $faceribbonURL;
        public $smallURL;
        
        // поле для файла загружаемой фотографии
        public $file;
         
	/**
	 * Метод возвращает статичную модель определённого AR-класса
         * 
	 * @param string $className имя класса ActiveRecord.
	 * @return Photo статичная модель класса
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
		return 'photo';
	}

        /**
         * Метод, возвращающий массив с описанием правил валидации модели
         * 
	 * @return array массив правил валидации
	 */
	public function rules()
	{
		return array(
                        array('file', 'file',
                                'allowEmpty' => false,
                                'maxFiles' => 100,
                                'maxSize' => 20 * 1024 * 1024,
                                'types' => array(
                                        'jpeg',
                                        'jpg',
                                        'png',
                                ),
                                /*'mimeTypes' => array(
                                        'image/gif',
                                        'image/jpeg',
                                        'image/pjpeg',
                                        'image/png',
                                ),*/
                        )
                );
	}

        
        /**
         * Метод устанавливающий пользователю фото из модели в качестве аватарки
         */
        public function setUserpic()
        {
                Yii::app()->db->createCommand()
                        ->update('user', array(
                                'id_userpic' => $this->id
                        ),
                        'id = :userID',
                        array(
                                'userID' => $this->id_user
                        )
                );
        }
        
        /**
         * Метод убирающий аватарку пользователя
         * 
         * @return type
         */        
        public static function unsetUserpic()
        {
                return Yii::app()->db->createCommand()
                        ->update('user', array(
                                'id_userpic' => null
                        ),
                        'id = :userID',
                        array(
                                'userID' => Yii::app()->user->id
                        )
                );
        }
        
        /**
         * Метод, получающий все записи оф фотографиях пользователя
         * 
         * @param integer $userID ID пользователя
         * @param boolean $isArrayResult получить результат в виде массива или в виде моделей
         * @return mixed массив моделей Photo или ассоциатиынй массив фотографий
         */
        public static function getUserPhotos($userID, $isArrayResult = true)
        {
                if($isArrayResult)
                        return Yii::app()->db->createCommand()
                                ->select('*')
                                ->from('photo')
                                ->where('id_user = :userID', array('userID' => $userID))
                                ->queryAll();
                else
                        return Photo::model ()->findAll ('id_user = :userID', array(
                                'userID' => $userID
                        ));
        }
        
        /**
         * Событие, происходящее перед сохранением модели
         * 
         * @return boolean результат срабатывания события
         */
        public function beforeSave()
        {
                $this->date = Yii::app()->localtime->getUTCNow();
                
                return parent::beforeSave();
        }
      
        /**
         * Событие, происходящее перед сохранением модели
         * 
         * @return boolean результат срабатывания события
         */
        public function afterSave()
        {
                // удаляем закешированные фото пользователя 
                // редактировать свои фото и добавлять может только сам пользователь
                Yii::app()->cache->delete('photos_'.Yii::app()->user->id);
            
                return parent::afterSave();
        }

                /**
         * Событие, выполняемое после удаления модели
         * 
         * @return type
         */
        protected function afterDelete()
        {                          
                Yii::app()->cache->delete('photos_'.$this->id_user);
                
                if($this->getScenario() == 'backend-delete')
                        return parent::afterDelete ();
                
                // если выбранное фото является аватаркой, то зануляем соответствующее поле у юзера
                if(Yii::app()->user->getUserpicID() == $this->id )
                        self::unsetUserpic();   
                
                // физически файлы не удаляем, т.к. данные из кеша могут ссылаться 
                // на отсутствующие изображения
                
                /* 
                $userID = Yii::app()->user->id;
                
                // получаем пути до файлов картинок с различными размерами
                $big = self::getUploadFolderPath($userID) . $this->filename_big;
                $medium = self::getUploadFolderPath($userID) . $this->filename_medium;
                $faceribbon = self::getUploadFolderPath($userID) . $this->filename_faceribbon;
                $small = self::getUploadFolderPath($userID) . $this->filename_small;
            
                // Удаление файла изображения
                if(is_file($big))
                        unlink($big);
                if(is_file($medium))
                        unlink($medium);
                if(is_file($faceribbon))
                        unlink($faceribbon);
                if(is_file($small))
                        unlink($small);
                */
                return parent::afterDelete();
        }
        
        /**
         * Событие, происходящее после поиска с помощью find-методов модели
         */
        public function afterFind()
        {
                parent::afterFind();
                
                // получаем урлы до фотографий различных размеров в текущей модели
                $this->smallURL = $this->modelPhotoFolderUrl().$this->filename_small;
                $this->bigURL = $this->modelPhotoFolderUrl().$this->filename_big;
                $this->mediumURL = $this->modelPhotoFolderUrl().$this->filename_medium;
                $this->faceribbonURL = $this->modelPhotoFolderUrl().$this->filename_faceribbon;
        }
        
        
        /**
         * Метод получает URL до папки с фотографиями пользователя
         * 
         * @return type
         */
        public static function getUploadFolderURL($userID = null)
        {
                if($userID === null)
                        $userID = Yii::app()->user->id;
            
                return Yii::app()->baseUrl . '/' . self::UPLOAD_FOLDER . '/' . $userID . '/';
        }
                    
        /**
         * Метод получает путь до папки с фотографиями текущего пользователя
         * 
         * @return type
         */
        public static function getUploadFolderPath($userID = null)
        {
                if($userID === null)
                        $userID = Yii::app()->user->id;
            
                return WEBROOT . DS . self::UPLOAD_FOLDER . DS . $userID . DS;
        }
        
        /**
         * Метод получает URL до папки с фотографиями относительно выбранной модели
         */
        public function modelPhotoFolderUrl()
        {
                return Yii::app()->baseUrl . '/' . self::UPLOAD_FOLDER . '/' . $this->id_user . '/';
        }
        
        /**
         * Метод получает URL до папки с фотографиями относительно выбранной модели
         */
        public function modelPhotoFolderUPath()
        {
                return WEBROOT . DS  . self::UPLOAD_FOLDER . '/' . $this->id_user . '/';
        }
        
        /**
         * Метод нарезающий копии изображения различных размеров
         * 
         * @param string $folder папка для изображений
         */
        public function createThumbnails()
        {
                // определяем папку загрузки фото и её права
                $folder = Photo::getUploadFolderPath();                           
                if(!is_dir($folder))
                        mkdir($folder, 0755);
            
                // проверка существования директория и загруженного файла
                if(!is_dir($folder) || empty($this->file))
                        return false;
                
                // статус ошибки
                $errorStatus = false;
            
                // генерация случайного уникального имени файла
                $fileName = uniqid(Yii::app()->user->id).'.'.$this->file->extensionName;
            
                // заполнение поля модели необходимыми данными
                $this->id_user = Yii::app()->user->id;
                $this->date = Yii::app()->localtime->getUTCNow();
                $this->filename_big = 'big_'.$fileName;
                $this->filename_medium = 'medium_'.$fileName;
                $this->filename_faceribbon = 'faceribbon_'.$fileName;
                $this->filename_small = 'small_'.$fileName;

                // создание объектов для обработки сообщений
                $imageBig = Yii::app()->image->load($this->file->tempName);
                
                        
                // получение размера изображения в пикселях
                $size = getimagesize($this->file->tempName);
                               
                // нарезка изображений нужных размеров
                if($size[0] >= self::SIZE_BIG_X || $size[1] >= self::SIZE_BIG_Y)
                        $imageBig->resize(self::SIZE_BIG_X, self::SIZE_BIG_Y)->quality(self::THUMBNAIL_QUALITY)->sharpen(self::THUMBNAIL_SHARPEN_AMOUNT);
                $errorStatus = ($errorStatus >= $imageBig->save($folder.$this->filename_big));
              
                $imageMedium = Yii::app()->image->load($folder.$this->filename_big);
                $imageMedium->smart_resize(self::SIZE_MEDIUM_X, self::SIZE_MEDIUM_Y)->quality(self::THUMBNAIL_QUALITY)->sharpen(self::THUMBNAIL_SHARPEN_AMOUNT);
                $errorStatus = ($errorStatus >= $imageMedium->save($folder.$this->filename_medium));

                $imageFaceribbon = Yii::app()->image->load($folder.$this->filename_medium);
                $imageFaceribbon->smart_resize(self::SIZE_FACERIBBON_X, self::SIZE_FACERIBBON_Y)->quality(self::THUMBNAIL_QUALITY)->sharpen(self::THUMBNAIL_SHARPEN_AMOUNT);
                $errorStatus = ($errorStatus >= $imageFaceribbon->save($folder.$this->filename_faceribbon));

                $imageSmall = Yii::app()->image->load($folder.$this->filename_medium);
                $imageSmall->smart_resize(self::SIZE_SMALL_X, self::SIZE_SMALL_Y)->quality(self::THUMBNAIL_QUALITY)->sharpen(self::THUMBNAIL_SHARPEN_AMOUNT);
                $errorStatus = ($errorStatus >= $imageSmall->save($folder.$this->filename_small));
                      
                // если где-то произошла ошибк, то удаляем нарезанные изображения
                if($errorStatus == true)
                {
                        $big = $folder.$this->filename_big;
                        $medium = $folder.$this->filename_medium;
                        $small = $folder.$this->filename_small;
                        $faceribbon = $folder.$this->filename_faceribbon;
                    
                        if(is_file($big))
                                unlink($big);
                        if(is_file($medium))
                                unlink($medium);
                        if(is_file($faceribbon))
                                unlink($faceribbon);
                        if(is_file($small))
                                unlink($small);
                }
                        
                // возвращаем успешность выполения метода
                return !$errorStatus;
        }
}