<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of JCharCounter
 *
 * @author gbespyatykh
 */
class JCharCounter extends CWidget
{
        /**
         * @var string ID поля ввода, где надо считать символы.
         * Количество символов следует указать в атрибуте maxlength
         */
        public $inputID;
        
        /**
         * @var string тег контейнера с сообщением счётчика 
         */
        public $container = 'div';
        
        /**
         * @var string ID тега контейнера счётчика 
         */
        public $containerID;
        
        /**
         * @var array HTML-опции контейнера 
         */
        public $containerOptions = array();
        
        /**
         * @var string строка, отображаемая в счётчике по-умолчанию
         */
        public $defaultString = '';
               
        /**
         * Инициализация виджета
         */
        public function init()
        {
                if(empty($this->containerID))
                        throw new Exception('Нужно задать ID контейнеру сообщения');
                
                if(empty($this->inputID))
                        throw new Exception('Нужно задать ID поля для ввода текста');
            
                $this->containerOptions['id'] = $this->containerID;       
        }
        
        /**
         * Запуск виджета
         */
        public function run()
        {
                $this->registerScript();
                echo CHtml::tag($this->container, $this->containerOptions, $this->defaultString);            
        }
        
        /**
         * Регистрация необходимых скриптов
         */
        private function registerScript()
        {
                Yii::app()->clientScript->registerCoreScript('jquery');
                Yii::app()->clientScript->registerScript('char-counter',"
                    $(document).ready(function(){
                        $(function(){
                            $(\"#$this->inputID\").keyup(function(){
                                var maxchars = $(\"#$this->inputID\").attr('maxlength');
                                var number = $(\"#$this->inputID\").val().length;

                                if(number <= maxchars){
                                    var counter = (maxchars - number);
                                    var stringLeft = verb(counter, ['Остался', 'Осталось']);
                                    var stringSymbols = plurals(counter, ['символ', 'символа', 'символов']);
                                    $(\"#$this->containerID\").html(stringLeft + ' ' + counter + ' ' + stringSymbols);
                                }
                            });
                        });
                    })
                ",  CClientScript::POS_READY);
        }
}

?>
