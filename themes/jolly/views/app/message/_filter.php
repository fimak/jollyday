<div class="message-filter-wrapper">
    <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'message-filter-form',
            'method' => 'POST',
            'htmlOptions' => array(
                    'class' => 'form-light'
            )
    )); ?>
    <div class="row-filter">
        <?php echo $form->dropDownList($filter, 'type', MessageFilterForm::getTypeFilterValues(), array(
            'class' => 'input-medium'
        )); ?>
        <?php echo $form->dropDownList($filter, 'mid', array('' => 'Любой способ') + JMeetmethod::getList(), array(
            'class' => 'input-long message-filter-username'
        )); ?>
        <?php echo $form->textField($filter, 'name', array(
            'placeholder' => 'Введите имя', 
            'class' => 'input-long message-filter-username'
            )); ?>
        <?php echo CHtml::tag('button', array(
            'type' => 'submit', 
            'class' => 'button-square aquamarine'), 
        'Найти'); ?>  
    </div>
    <?php $this->endWidget();?>
</div>

<?php Yii::app()->clientScript->registerScript('message-filter',"
    $(document).ready(function(){
        if($('#MessageFilterForm_type').val() == 'blacklist')
            $('#MessageFilterForm_mid').attr('disabled', 'disabled').trigger('refresh');
    });
    
    $('#MessageFilterForm_type').change(function(){
        if($(this).val() == 'blacklist')
            $('#MessageFilterForm_mid').attr('disabled', 'disabled').trigger('refresh');
        else
            $('#MessageFilterForm_mid').removeAttr('disabled').trigger('refresh');
    });
", CClientScript::POS_READY)?>