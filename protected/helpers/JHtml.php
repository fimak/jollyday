<?php
/**
 * Расширения класса для работы с HTML-разметкой
 *
 * @author hash
 */
class JHtml extends CHtml
{
        /**
         * Метод получения массива с описанием логических значений
         * 
         * @return array Массив описания логических значений
         */
        public static function listBoolean()
        {
                return array('0' => 'Нет', '1' => 'Да');
        }
                
        /**
         * Метод рендерит одномерный маркированный список ul
         * 
         * @param array $data Массив с данными
         * @param array $htmlOptions html свойства списка
         * @param array $itemOptions html свойства элемента списка
         * @throws Exception Данные должны быть получены в виде массива
         */
        public static function unorderedList($data, $htmlOptions = array(), $itemOptions = array())
        {
                if(!is_array($data))
                        throw new Exception('Данные должны быть получены в виде массива');
         
                $string = '';
                
                $string .= self::openTag('ul',$htmlOptions)."\n";
                
                foreach($data as $item => $value)
                {
                        $string .= self::openTag('li', $itemOptions);
                        $string .=  $value;
                        $string .=  self::closeTag('li');
                }
                
                $string .=  self::closeTag('ul');
                
                return $string;
        }
              
        /**
         * Метод рендерит особенно хитрожопый активный чекбокслист 
         * для формы редактирования интересов
         * 
         * @param string $name
         * @param array $select
         * @param array $data
         * @param array $htmlOptions
         * @return type
         */
	public static function meetmethodActiveCheckBoxList($model,$attribute,$data,$htmlOptions=array())
	{
		self::resolveNameID($model,$attribute,$htmlOptions);
		$selection=self::resolveValue($model,$attribute);
		if($model->hasErrors($attribute))
			self::addErrorCss($htmlOptions);
		$name=$htmlOptions['name'];
		unset($htmlOptions['name']);

		if(array_key_exists('uncheckValue',$htmlOptions))
		{
			$uncheck=$htmlOptions['uncheckValue'];
			unset($htmlOptions['uncheckValue']);
		}
		else
			$uncheck='';

		$hiddenOptions=isset($htmlOptions['id']) ? array('id'=>self::ID_PREFIX.$htmlOptions['id']) : array('id'=>false);
		$hidden=$uncheck!==null ? self::hiddenField($name,$uncheck,$hiddenOptions) : '';

		return $hidden . self::meetmethodCheckBoxList($name,$selection,$data,$htmlOptions);
	}
        
        /**
         * Метод рендерит чекбокслист для интересов
         * 
         * @param type $name
         * @param type $select
         * @param type $data
         * @param type $htmlOptions
         * @return type
         */
	public static function meetmethodCheckBoxList($name,$select,$data,$htmlOptions=array())
	{
		$template  = '
                    <td>
                        <div class="mm_chkbx_wrapper {checked}">
                            {button}
                        </div>
                        <div class="mm_chkbx_arrow">
                            {input}
                            {label}
                        </div>
                    </td>';
		$separator = "\n";
		$container = 'table';
                $containerOptions = isset($htmlOptions['containerOptions'])?$htmlOptions['containerOptions']:array();
                              
		unset($htmlOptions['template'],$htmlOptions['separator'],$htmlOptions['container']);

		if(substr($name,-2)!=='[]')
			$name.='[]';

		$labelOptions=isset($htmlOptions['labelOptions'])?$htmlOptions['labelOptions']:array();
		unset($htmlOptions['labelOptions']);

		$items=array();
                $baseID=isset($htmlOptions['baseID']) ? $htmlOptions['baseID'] : self::getIdByName($name);
		$containerOptions['id']=isset($htmlOptions['baseID']) ? $htmlOptions['baseID'] : self::getIdByName($name);
		unset($htmlOptions['baseID']);
                unset($htmlOptions['containerOptions']);
		$id=0;

                // тут используем интересы
                $methodData = JMeetmethod::getData();
                       
		foreach($data as $value=>$label)
		{                         
			$checked=!is_array($select) && !strcmp($value,$select) || is_array($select) && in_array($value,$select);
			$htmlOptions['value']=$value;
			$htmlOptions['id']=$baseID.'_'.$id++;
                                           
			$option=self::checkBox($name,$checked,$htmlOptions);
			$label=self::label($label,$htmlOptions['id'],$labelOptions);
                        
                        $itemString = '';
                        
                        // формируем кнопку со способом знакомства
                        $button = CHtml::tag(
                                'div', 
                                array(
                                        'class' => 'mm-icon-small active mm-own ' . $methodData[$value]['htmlClass'],
                                ), 
                                ''
                        );
                        
                        $checkedClass = $checked ? 'mm-checked' : 'mm-unchecked'; 
                        
                        // формируем ячейку таблицы - внутри чекбокс с кнопкой и лейблом
                        // для контейнера чекбокса и кнопки - подставляется класс в заивсимости и значения чекбокса
                        if(in_array($value , array(1,4,7,10,13,16)))
                                $itemString .= '<tr>';
                        
                        $itemString .= strtr($template,array('{input}'=>$option,'{label}'=>$label, '{button}'=>$button, '{checked}' => $checkedClass));
                        
                        if(in_array($value, array(3,6,9,12,15))) 
                                $itemString .= '</tr>';
                        
                        $items[] = $itemString;
		}

                // скрипт, чтобы контейнер чекбокса, работал также как и чекбокс
                Yii::app()->clientScript->registerScript('mm-wrappers',"
                    $(document).ready(function(){
                        $('.mm_chkbx_wrapper').click(function(){
                            var checked = $(this).hasClass('mm-checked'); 
                            if(checked){
                                $(this).removeClass('mm-checked mm-unchecked').addClass('mm-unchecked');
                                $(this).parent().find(':checkbox').removeAttr('checked').trigger('refresh');
                            }
                            else{
                                $(this).removeClass('mm-checked mm-unchecked').addClass('mm-checked');
                                $(this).parent().find(':checkbox').attr('checked','checked').trigger('refresh');
                            }  
                            
                            if($('.meetmethods-update-table :checkbox:checked').length == 15)
                                $('#trSelectAllMeetmethods').attr('checked','checked').trigger('refresh');
                            else
                                 $('#trSelectAllMeetmethods').removeAttr('checked').trigger('refresh');
                        });
                        
                        $('.mm_chkbx_arrow :checkbox').change(function(){
                            var checked = $(this).prop('checked');
                            var wrapper = $(this).parent().prev();

                            if(checked){
                                $(wrapper).removeClass('mm-checked mm-unchecked');
                                $(wrapper).addClass('mm-checked');
                            }
                            else{
                                $(wrapper).removeClass('mm-checked mm-unchecked');
                                $(wrapper).addClass('mm-unchecked');
                            }
                            
                            $(this).trigger('refresh');
                        });
                    });
                ", CClientScript::POS_READY);
                
		if(empty($container))
			return implode($separator,$items);
		else
			return self::tag($container, $containerOptions ,implode($separator,$items));
	}
}
?>


