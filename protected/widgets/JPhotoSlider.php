<?php
/**
 * Класс виджета формы поиска
 */
class JPhotoSlider extends CWidget
{
        /** 
         * @var array фотографии пользователя
         */
        public $photos;
        
        /** 
         * @var boolean собственный ли слайдер
         */
        public $isOwn;
          
        /**
         * @var integer ID пользователя 
         */
        public $userID;
             
        /**
         * Запуск виджета
         */
        public function run() 
        {
                $this->render('theme.views.widgets.jphotoslider._photo_slider', array(
                        'photos' => $this->photos,
                        'isOwn' => $this->isOwn,
                        'userID' => $this->userID,
                ));
        }
    
}

?>
