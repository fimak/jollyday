<?php

/**
 * Виджет выбора СМС-оператора
 */
class JSmsOperatorPicker extends CWidget
{
        public $tariffList = array();
        
        public $amountElementId;
        
        public $name;
        
        public $operator;
        
        public function init()
        {
                parent::init();
                $this->registerScript();
        }
        
        public function run()
        {
                echo CHtml::dropDownList($this->name, false, $this->tariffList, array(
                        'onChange' => "$('#$this->amountElementId').html($(this).val());",
                        'id' => $this->name
                )); 
        }
        
        private function registerScript()
        {
                $cs = Yii::app()->getClientScript();
                
                $script = <<<EOD
$('#$this->name option').each(function(){
    var operator = $(this).attr('value');
    $(this).attr('value', $(this).html());
    $(this).html(operator);
});

$('#$this->amountElementId').html($('#$this->name').val());
    
$('#$this->name').trigger('refresh');
EOD;
                $cs->registerScript($this->name, $script, CClientScript::POS_READY);
                
                if($this->operator){
                        $script2 = <<<EOD
    

$("#$this->name").find("option:contains('$this->operator')").attr("selected", "selected");
EOD;
                        $cs->registerScript('select_operator', $script2, CClientScript::POS_READY);
                }
        }
}

?>