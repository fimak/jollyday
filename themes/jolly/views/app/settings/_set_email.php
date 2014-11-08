<h2><?php echo CHtml::image("/images/fancybox-icons/settings.png", "icon", array('width'=>28,'height'=>28));?>Изменение адреса электронной почты</h2>

    <div class="fancybox-content">
        <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'set-email-form',
                'htmlOptions' => array(
                        'class' => 'form-light'
                ),
                'enableAjaxValidation' => false,
                'enableClientValidation' => true,
                'clientOptions' => array(
                        'validateOnSubmit' => false,
                        'validateOnChange' => false,
                        'validateOnType' => false,
                )
        )); ?>
        
        <div id="json-response-questionary" class="flash-message"></div>
        
        <div class="row first">
            <div class="left-short">
                <?php echo $form->labelEx($model, 'email'); ?>
            </div>
            <div class="right-medium">
                <?php echo $form->textField($model, 'email', array('class' => 'input-long')); ?>
                <?php echo $form->error($model, 'email'); ?>  
            </div>
        </div>
        
        <div class="fancybox-buttons">
            <?php echo CHtml::ajaxSubmitButton('OK', array('/app/settings/setemail'), 
                                array(
                                        'url' => array('/app/settings/setemail'),
                                        'type' => 'post',
                                        'dataType' => 'json',
                                        'success' => "js:function(data){
                                                if(data.status == 'success'){
                                                    $.fancybox.close(true);
                                                    $('body').quickNotice(data.message, 5000);
                                                    $('#email-activate-alert').removeClass('hide');
                                                    $('#email-activate').html(data.email);
                                                    $('#email-activate-success').addClass('hide');
                                                }
                                                else if(data.status == 'error'){
                                                    var form = $('#set-email-form');
                                                    var settings = form.data('settings');

                                                    $.each(settings.attributes, function () {
                                                        $.fn.yiiactiveform.updateInput(this, data.errors, form);
                                                    });
                                                }
                                                
                                        }"
                                ),
                                array(
                                        'id' => 'set-email-submit-uid-'.uniqid(),
                                        'name' => 'set-email-submit',
                                        'class' => 'button-square orange'
                                )
                        ); ?> 
            <?php echo CHtml::tag('button', array('type' => 'button', 'class' => 'button-square aquamarine' , 'onClick' => '$.fancybox.close()'), 'Отмена');?>
        </div>
    </div>
<?php $this->endWidget(); ?>