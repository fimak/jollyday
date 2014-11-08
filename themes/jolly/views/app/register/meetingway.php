<?php $this->pageTitle = 'Регистрация :: Способы знакомства' . ' - ' . Yii::app()->name;?>

<h1 id="page-header">Ваши способы знакомства:</h1>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'stepfour-form',
        'htmlOptions'=>array(
                'class' => 'form-light'
        )
)); ?>
        <?php $this->widget('application.extensions.yii-flash.EFlash', array(
                'keys' => array('success','error','notice'),
                'htmlOptions'=>array(
                        'class'=>'flash-message'
                ),
        )); ?>
    
        <div class="row">
        Выберите интересующие Вас предложения (способы знакомства). 
        Другие пользователи будут знать Ваши предпочтения и смогут предложить 
        Вам только те варианты знакомства, которые Вы выбрали.
        </div>
        <div class="row">
                <?php echo JHtml::meetmethodActiveCheckBoxList($model,'meetmethodIds', JMeetmethod::getList(), array(
                        'containerOptions' => array(
                                'class' => 'meetmethods-update-table',
                        )
                ));?>  
        </div>

        <div class="buttons-mm">
                <?php echo CHtml::checkBox('trSelectAllMeetmethods'); ?>
                <?php echo CHtml::label('Отметить все', 'trSelectAllMeetmethods')?>
                <?php echo CHtml::tag('button', array(
                        'type' => 'submit',
                        'class' => 'button-square orange'
                ),'Далее')?>	
        </div>
<?php $this->endWidget(); ?>       
</div>

<?php
// регистрируем скрипт для выбора одним чекбоксом всех способов знакомства
Yii::app()->clientScript->registerScript('select-all-meetmethods',"
    
    $(document).ready(function(){
        if($('.meetmethods-update-table :checkbox:checked').length == 15)
            $('#trSelectAllMeetmethods').attr('checked','checked').trigger('refresh');    

        $('#trSelectAllMeetmethods').change(function(){
            var checked = $(this).prop('checked');

            if(checked){
                $('.meetmethods-update-table :checkbox').attr('checked','checked').trigger('refresh');
                $('.mm_chkbx_wrapper').removeClass('mm-checked mm-unchecked').addClass('mm-checked');
            }
            else{
                $('.meetmethods-update-table :checkbox').removeAttr('checked').trigger('refresh');
                $('.mm_chkbx_wrapper').removeClass('mm-checked mm-unchecked').addClass('mm-unchecked');
            }

            $(this).trigger('refresh');
        });
        $('.meetmethods-update-table :checkbox').change(function() {
            if($('.meetmethods-update-table :checkbox:checked').length == 15)
                $('#trSelectAllMeetmethods').attr('checked','checked').trigger('refresh');
            else
                $('#trSelectAllMeetmethods').removeAttr('checked').trigger('refresh');
        });
    });
", CClientScript::POS_READY)
?>
