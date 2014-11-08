<?php

/**
 * Класс модели для таблицы "news".
 *
 * The followings are the available columns in table 'news':
 * @property integer $id
 * @property string $title
 * @property string $text
 * @property string $image
 */
class News extends CActiveRecord
{
        const STATUS_READ = 1;
        const STATUS_UNREAD = 0;
    
        const IMAGE_FOLDER = 'news';
        const STD_IMAGE_FOLDER = 'news_std';
    
        const TYPE_TEMPLATED = 0;
        const TYPE_CUSTOM = 1;
        
        /** 
         * @var $uploadedFile CUploadedFile хвы 
         */
        public $uploadedFile;
        
        public $imageURL;
        
	/**
	 * Метод возвращает статичную модель определённого AR-класса
         * 
	 * @param string $className имя класса ActiveRecord.
	 * @return News статичная модель класса
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
		return 'news_template';
	}

        /**
         * Метод, возвращающий массив с описанием правил валидации модели
         * 
	 * @return array массив правил валидации
	 */
	public function rules()
	{
		return array(
                        array('title, text, alias', 'required'),
                        array('title', 'length', 
                                'max' => 255,
                        ),
                        array('text', 'length',
                                'max' => 1024 * 10,
                                'tooLong' => 'Слишком длинный текст новости',
                        ),
                        array('uploadedFile', 'file',
                                'allowEmpty' => true,
                                'maxFiles' => 1,
                                'maxSize' => 1024 * 1024 * 10,
                                'types' => 'gif, png, jpg, jpeg',
                                'tooMany' => 'Можно загрузить только один файл',
                                'tooLarge' => 'Максимальный размер файла 10 МБ',
                                'wrongType' => 'Можно загружать только изображения',
                                
                        ),
                        array('alias', 'length',
                                'min' => 3,
                                'max' => 32,
                        ),
                        array('alias', 'match',
                                'pattern' => '/^[0-9a-zA-Z\s]+$/ui'
                        ),
                        array('alias', 'unique',
                                'className' => 'News',
                                'attributeName' => 'alias'
                        ),
                        array('uploadedFile', 'required', 'on' => 'create'), 
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
			'title' => 'Заголовок',
			'text' => 'Текст',
			'image' => 'Изображение',
                        'uploadedFile' => 'Изображение',
                        'alias' => 'Алиас',
		);
	}

	/**
	 * Метод возращает список моделей, исходя из критерия поиска
         * 
	 * @return CActiveDataProvider 
         */
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('title',$this->title, true);
                $criteria->compare('alias', $this->alias, true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
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
         * Метод возвращает ссылку до папки с изображениями
         * 
         * @return type
         */
        public static function getImageFolderUrl()
        {
                return Yii::app()->baseUrl . '/' . 'images' . '/' . self::IMAGE_FOLDER . '/';
        }
        
        /**
         * Метод возвращает ссылку до папки со стандартными изоюражениями, для персональной отправки уведомления
         * 
         * @return type
         */
        public static function getStdImageFolderUrl()
        {
                return Yii::app()->baseUrl . '/' . 'images' . '/' . self::STD_IMAGE_FOLDER . '/';
        }
        
        /**
         * Метод возвращает список стандартных изображений для подстановки
         * в персональное уведомление
         * 
         * @return array
         */
        public static function getStdImageList()
        {
                $baseUrl = self::getStdImageFolderUrl();
                
                return array(
                        $baseUrl . 'attention.jpg' => 'Предупреждение',
                        $baseUrl . 'message.jpg' => 'Сообщение',
                        $baseUrl . 'logo.jpg' => 'Логотип',
                );
        }
        
        /**
         * Событие, происходящее после поиска с помощью find-методов модели
         */
        protected function afterFind()
        {
                parent::afterFind();
                
                $this->imageURL = self::getImageFolderUrl() . $this->image;
        }
        
        /**
         * Событие выполняемое после удаления записи из таблицы
         * 
         * @return type
         */
        protected function afterDelete()
        {
                $path =  self::getImagePath() . $this->image;
            
                // Удаление изображения новости
                if(is_file($path) && $this->image != null)
                        unlink($path);
                
                return parent::afterDelete();
        }
              
        /**
         * Метод отправляет шаблонное сообщение указанному пользователю
         * 
         * @param string $alias алиас новости
         * @param integer $userId ID пользователя
         */
        public static function sendPersonalTemplated($alias, $userId)
        {          
                if(!$userId || !in_array($alias, self::getAliases()))
                        return false;
                
                // получаем ID новости по её алиасу
                $newsId = Yii::app()->db->createCommand()
                        ->select('id')
                        ->from('news_template')
                        ->where('alias = :alias', array('alias' => $alias))
                        ->queryScalar();
                
                // если новость сущетсвуе, то отправляем её пользователю,
                // иначе не отправляем
                if($newsId)
                        $result = Yii::app()->db->createCommand()
                                ->insert('im_user_news', array(
                                        'id_news' => $newsId,
                                        'id_user' => $userId,
                                        'date' => new CDbExpression('NOW()'),
                                        'status' => self::STATUS_UNREAD,
                                        'type' => self::TYPE_TEMPLATED,
                                ));
                else
                        $result = false;
                
                return $result;
        }
        
        /**
         * Метод помечает новости текущего пользователя
         * как прочитанные
         * 
         * @return boolean
         */
        public static function markAsRead()
        {
                return Yii::app()->db->createCommand()
                        ->update(
                                'im_user_news',
                                array(
                                        'status' => self::STATUS_READ
                                ),
                                'id_user = :userID AND status = :parStatus',
                                array(
                                        'userID' => Yii::app()->user->id,
                                        'parStatus' => self::STATUS_UNREAD,
                                )
                        );
        }
        
        /**
         * Метод получает существующие алиасы шаблонов новостей
         * 
         * @return array
         */
        public static function getAliases()
        {
                return Yii::app()->db->createCommand()
                        ->select('alias')
                        ->from('news_template')
                        ->queryColumn();
        }
        
        /**
         * Метод обрабатавыет уведомления для вывода пользователю, соединяет
         * шаблонные и персональные уведомления
         * 
         * @param array $items
         */
        public static function process(&$items)
        {
                foreach($items as $key => $value)
                {         
                        switch($value['type'])
                        {
                                case self::TYPE_TEMPLATED:
                                        $value['image'] = self::getImageFolderUrl() . $value['t_image'];
                                        $value['title'] = $value['t_title'];
                                        $value['text'] = $value['t_text'];
                                        $value['title'] = $value['t_title'];
                                        unset($value['t_title'], $value['t_text'], $value['t_image']);
                                        break;
                                case self::TYPE_CUSTOM:
                                        $value['image'] = self::getStdImageFolderUrl() . $value['image'];
                                        unset($value['t_title'], $value['t_text'], $value['t_image']);
                                        break;
                                default:
                                        break;
                        }             
                        $items[$key] = $value;                   
                }          
        }
}