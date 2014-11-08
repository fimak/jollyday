<h4>Мои интересы</h4>

<div class="backlink-wrapper">
    <?php echo CHtml::link('перейти к моей странице', 'javascript:void(0)', array(
            'class' => 'backlink trLoadCompactMessages',
            'data-link' => J::url('profile/loadrecentmessages')
     ))?>     
</div>



<div id="json-response-questionary" class="flash-message"></div>

<div class="form">   
    <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'settings-form',
            'enableClientValidation'=>false,
            'enableAjaxValidation' => false,
            'clientOptions'=>array(
                    'validateOnSubmit'=>false,
            ),
            'htmlOptions' => array( 
                    'class' => 'form-light meetmethods-profile-form', 
            ),          
    )); ?>
    
        <p>Выберите интересующие вас способы знакомства</p>
        <div class="choose-all">
                <?php echo CHtml::checkBox('trSelectAllMeetmethods', false, array(
                        'id' => 'trSelectAllMeetmethods'
                )); ?>
                <?php echo CHtml::label('Отметить все', 'trSelectAllMeetmethods')?>
        </div>
        <?php echo $form->errorSummary($model); ?>
        <?php echo $form->error($model,'meetmethodIds'); ?> 
        <?php echo JHtml::meetmethodActiveCheckBoxList($model,'meetmethodIds', JMeetmethod::getList(), array(
                'containerOptions' => array(
                        'class' => 'meetmethods-update-table',
                )
        ));?>  
    
    
        <div class="buttons-mm">
                <?php echo CHtml::ajaxSubmitButton('Сохранить', array('updatemethods'), 
                        array(
                                'update' => '#ajax-container',
                                'url' => array('updatesettings'),
                                'type' => 'post',
                                'dataType' => 'json',
                                'success' => 'function(data){
                                        moveToAnchor("#ajax-block",300);
                                        $("#json-response-questionary").notice(data.status, data.message, 3000);
                                        if(data.html != null){
                                                $("#u'.$userID.' .profile-column-right").html(data.html);
                                        }
                                }',                           
                        ),
                        array(
                                'id' => 'settings-submit-uid-'.uniqid(),
                                'class' => 'button-square orange'
                        )
                ); ?>
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