<?php

/**
 * Description of NotificationForm
 *
 * @author h4sh1sh
 */
class NotificationForm extends CFormModel
{
        public $text;
        
        public $std_image;
        
        public $title;
        
        public $id_user;
        
        public function rules()
        {
                return array(
                        array('title, text, std_image', 'required',
                                'message' => 'Поле не может быть пустым'
                        ),
                        array('title', 'length', 
                                'max' => 255,
                        ),
                        array('text', 'length',
                                'max' => 1024 * 10,
                                'tooLong' => 'Слишком длинный текст новости',
                        ),
                        array('id_user', 'exist',
                                'className' => 'User',
                                'attributeName' => 'id',
                                'allowEmpty' => false,
                        ),
                        array('std_image', 'in',
                                'range' => array_keys(News::getStdImageList()),
                        ),
                );
        }
        
        public function attributeLabels()
        {
                return array(
                        'title' => 'Заголовок',
                        'std_image' => 'Изображение',
                        'text' => 'Текст'
                );
        }
          
        public function send()
        {
                $image = str_replace(News::getStdImageFolderUrl(), '', $this->std_image);
            
                return Yii::app()->db->createCommand()
                        ->insert('im_user_news', array(
                                'date' => new CDbExpression('NOW()'),
                                'id_user' => $this->id_user,
                                'status' => News::STATUS_UNREAD,
                                'type' => News::TYPE_CUSTOM,
                                'text' => $this->text,
                                'std_image' => $image,
                                'title' => $this->title,
                        ));
        }
}

?>
