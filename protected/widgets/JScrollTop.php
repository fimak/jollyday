<?php

/**
 * Виджет плагина кнопки скролла сайта на верз страницы
 */
class JScrollTop extends JWidget
{   
        /**
         * @var array HTML-опции контейнера
         */
        public $containerOptions = array();
        
        /**
         * @var string ID контейнера ссылки
         */
        public $containerID;
        
        /**
         * @var array HTML-опции ссылки
         */
        public $linkOptions = array();
        
        /**
         * @var string текст ссылки
         */
        public $linkText;
        
        /**
         * @var integer скорость прокрутки (0 - мгновенно)
         */
        public $scrollSpeed = 800;
        
        /**
         * @var integer расстояние прокрутки от верха сайта при котором появляется кнопка
         */
        public $scrollDistance = 50;
            
	/**
         * Инициализация виджета
         */
        public function init()
        {       
                // Проверка данных на корректность               
                if(!is_int($this->scrollSpeed))
                        throw new Exception('Параметр "scrollSpeed" должен быть целым числом');
                
                if(!is_int($this->scrollDistance))
                        throw new Exception('Параметр "scrollDistance" должен быть целым числом');
                
                if(empty($this->containerID))
                        throw new Exception('Параметр "containerID" является обязательным');
                
                // подставляем якорь ссылки, если не задан
                if(empty($this->linkOptions['href']))
                        $this->linkOptions['href'] = '#';
                
                // присваиваем ID контейнеру
                $this->containerOptions['id'] = $this->containerID;     
        }
        
        /**
         * Запуск виджета
         */
        public function run()
        {
                $link = CHtml::link($this->linkText, $this->linkOptions['href'], $this->linkOptions);
                
                echo CHtml::tag('div', $this->containerOptions, $link);
                
                $this->registerScript();
        }
        
        /**
         * Подключение скрипта
         */
        private function registerScript()
        {
                Yii::app()->clientScript->registerCoreScript('jquery');
            
                $script = "
                    $(document).ready(function(){
                        $('#$this->containerID').hide();
                        $(function () {
                            $(window).scroll(function () {
                                if ($(this).scrollTop() > $this->scrollDistance) {
                                    $('#$this->containerID').show();
                                } else {
                                    $('#$this->containerID').hide();
                                }
                            });
                            $('#$this->containerID a').click(function () {
                                $('body,html').animate({
                                    scrollTop: 0
                                }, $this->scrollSpeed);
                                return false;
                            });
                        });
                    });
                ";
   
                Yii::app()->clientScript->registerScript('scrolltop',$script, CClientScript::POS_READY);
        }
}

?>
