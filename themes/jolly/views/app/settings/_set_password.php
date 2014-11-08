<h2><?php echo CHtml::image("/images/fancybox-icons/settings.png", "icon", array('width'=>28,'height'=>28));?>Изменение пароля</h2>
  
<div class="fancybox-content">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'set-password-form',
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
        

        
        <div class="row first">
            <div class="left-short">
                <?php echo $form->labelEx($model, 'current_password'); ?>
            </div>
            <div class="right-medium">
                <?php echo $form->passwordField($model, 'current_password', array('maxlength' => 16, 'class' => 'input-long')); ?>
                <?php echo $form->error($model, 'current_password'); ?>   
            </div>
        </div>

        <div class="row">
            <div class="left-short">
                <?php echo $form->labelEx($model, 'new_password'); ?>
            </div>
            <div class="right-medium">
                <?php echo $form->passwordField($model, 'new_password', array('maxlength' => 16, 'class' => 'input-long')); ?>
                <?php echo $form->error($model, 'new_password'); ?>
            </div>
        </div>    

        <div class="row">
            <div class="left-short">
                <?php echo $form->labelEx($model, 'new_password_confirm'); ?>
            </div>
            <div class="right-medium">
                <?php echo $form->passwordField($model, 'new_password_confirm', array('maxlength' => 16, 'class' => 'input-long')); ?>
                <?php echo $form->error($model, 'new_password_confirm'); ?>
            </div>
        </div>

        <div class="fancybox-buttons">
            <?php echo CHtml::ajaxSubmitButton('Сохранить', array('/app/settings/setpassword'), 
                    array(
                            'url' => array('/app/settings/setpassword'),
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
                                        var form = $('#set-password-form');
                                        var settings = form.data('settings');

                                        $.each(settings.attributes, function () {
                                            $.fn.yiiactiveform.updateInput(this, data.errors, form);
                                        });
                                    }

                            }"
                    ),
                    array(
                            'id' => 'set-passsword-submit-uid-'.uniqid(),
                            'name' => 'settings-submit',
                            'class' => 'button-square orange'
                    )
            ); ?>
            <?php echo CHtml::tag('button', array('type' => 'button', 'class' => 'button-square aquamarine' , 'onClick' => '$.fancybox.close()'), 'Отмена');?>
        </div>
    <?php $this->endWidget(); ?>
</div>        

