<?php

/**
 * Класс виджета для выбора даты с помощью выпадающих списков
 */
class JDropDownDate extends CInputWidget
{
        /**
         * @var string имя поля формы для ввода дня
         */
        public $dFieldName = 'ddd-day';
        
        /**
         * @var string имя поля формы для ввода месяца
         */
        public $mFieldName = 'ddd-month';
        
        /**
         * @var string имя поля формы для ввода года
         */
        public $yFieldName = 'ddd-year';
        
        /**
         * @var array HTML-опции селекта с днями
         */
        public $dOptions = array();
        
        /**
         * @var array HTML-опции селекта с месяцами
         */
        public $mOptions = array();
        
        /**
         * @var array HTML-опции селекта с годами
         */
        public $yOptions = array();
        
        /**
         * @var string шаблон вывода виджета
         */
        public $template = '{d}{m}{y}';
        
        /**
         * @var boolean не заворачивать ли каждый селект в контейнер
         */
        public $inlineControls = true;
        
        /**
         * @var array HTML-опции контенера виджета
         */
        public $rowOptions = array();
        
        /**
         * @var integer поле дня дня
         */
        private $d;
        
        /**
         * @var integer поле дня месяца
         */
        private $m;
        
        /**
         * @var integer поле дня года
         */
        private $y;
        
        /**
         * @var string имя скрытого поля с отформатированной датой
         */
        private $fieldName;
                  
        /**
         * Запуск виджета
         */
        public function run()
        {
                // Получаем имя и id поля виджета
		list($name,$id)=$this->resolveNameID();
                
		if(isset($this->htmlOptions['id']))
			$id = $this->htmlOptions['id'];
		else
			$this->htmlOptions['id'] = $id;
                
		if(isset($this->htmlOptions['name']))
			$name = $this->htmlOptions['name'];
                
                // парсинг даты 
                if($this->model->{$this->attribute} != null)
                {
                        $date = new DateTime($this->model->{$this->attribute}); 

                        $this->d = $date->format('j');
                        $this->m = $date->format('n');
                        $this->y = $date->format('Y');

                        $this->value = $this->model->{$this->attribute};
                }               
                if($this->value == null)
                {
                        $this->dOptions = array_merge(array('prompt' => 'День'), $this->dOptions);
                        $this->mOptions = array_merge(array('prompt' => 'Месяц'), $this->mOptions);
                        $this->yOptions = array_merge(array('prompt' => 'Год'), $this->yOptions);

                        $this->d = $this->m = $this->y = null;              
                }

                
                if($this->inlineControls)
                {
                        echo CHtml::dropDownList($this->dFieldName, $this->d, $this->getDayList(), $this->dOptions);
                        echo CHtml::dropDownList($this->mFieldName, $this->m, $this->getMonthList(), $this->mOptions);
                        echo CHtml::dropDownList($this->yFieldName, $this->y, $this->getYearList(), $this->yOptions);
                }
                else
                {
                        echo CHtml::tag('div', $this->rowOptions, CHtml::dropDownList($this->dFieldName, $this->d, $this->getDayList(), $this->dOptions));
                        echo CHtml::tag('div', $this->rowOptions, CHtml::dropDownList($this->mFieldName, $this->m, $this->getMonthList(), $this->mOptions));
                        echo CHtml::tag('div', $this->rowOptions, CHtml::dropDownList($this->yFieldName, $this->y, $this->getYearList(), $this->yOptions));
                }
                
                // вывод скрытого поля, содержащим значение атрибута
		if($this->hasModel())
			echo CHtml::activeHiddenField($this->model, $this->attribute);
		else
			echo CHtml::hiddenField($name, $this->value);
                
                // подключение скриптов
                $this->registerClientScript($id);
        }
        
        /**
         * @return array список дней
         */
        private function getDayList()
        {                
                for($i = 1; $i <= 31; $i++)
                        $data[$i] = $i;
                
                return $data;
        }

        /**
         * @return array список месяцев
         */        
        private function getMonthList()
        {            
                $data[1] = 'Январь';
                $data[2] = 'Февраль';
                $data[3] = 'Март';
                $data[4] = 'Апрель';
                $data[5] = 'Май';
                $data[6] = 'Июнь';
                $data[7] = 'Июль';
                $data[8] = 'Август';
                $data[9] = 'Сентябрь';
                $data[10] = 'Октябрь';
                $data[11] = 'Ноябрь';
                $data[12] = 'Декабрь';
                               
                return $data;
        }
        
        /**
         * @return array список годов
         */        
        private function getYearList()
        {          
                for($i = date('Y') - Profile::AGE_MIN; $i >= date('Y') - Profile::AGE_MAX; $i--)
                        $data[$i] = $i;
                
                return $data;                
        }
        
        /**
         * Подключение скриптов на страницу
         */
        private function registerClientScript($inputId)
        {
                Yii::app()->clientScript->registerCoreScript('jquery');
            
                $script = "
                        function zeroFill(value){
                                 return( value < 10 ? '0' + value : value);
                        }                

                        function implodeDate(){
                                var d = $('#{$this->dFieldName}').val();
                                var m = $('#{$this->mFieldName}').val();
                                var y = $('#{$this->yFieldName}').val();
                             
                                if(d == '' || m == '' || y == '')
                                        return '';
 
                                d = zeroFill(d);
                                m = zeroFill(m);

                                return (y.toString() + '-' + m.toString() + '-' + d.toString());
                        }

                        function resetDays(){
                                var old_d = parseInt($('#{$this->dFieldName}').val());
                                var old_m = parseInt($('#{$this->mFieldName}').val());
                                var old_y = parseInt($('#{$this->yFieldName}').val());

                                if(isNaN(old_m) || isNaN(old_y))
                                       return;

                                $('#ddd-day').empty();
                                var j = new Date(parseInt($('#{$this->yFieldName}').val()), parseInt($('#{$this->mFieldName}').val()), 0);

                                for (var i = 1; i <= j.getDate(); i++) 
                                    $('#{$this->dFieldName}').append($('<option></option>').attr('value', i).text(i));

                                if(old_d <= 28)
                                     $('#{$this->dFieldName}').val(old_d);
                                else   
                                     $('#{$this->dFieldName}').val(j.getDate());
                        }

                        $('#{$this->dFieldName}').change(function(){
                                $('#{$inputId}').val(implodeDate());
                                $('#$this->dFieldName, #$this->mFieldName, #$this->yFieldName').trigger('refresh');    
                        });                     

                        $('#{$this->mFieldName}, #{$this->yFieldName}').change(function(){
                                resetDays();
                                $('#{$inputId}').val(implodeDate());
                                $('#$this->dFieldName, #$this->mFieldName, #$this->yFieldName').trigger('refresh');
                        });
                ";
                
                Yii::app()->getClientScript()->registerScript('ddd-datepicker',$script, CClientScript::POS_LOAD);            
        }  
}

?>
