<h2><?php echo CHtml::image("/images/fancybox-icons/login.png", "icon", array('width'=>28,'height'=>28));?>Авторизация</h2>

<div class="fancybox-content">

    <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'login-form-modal',
            'enableAjaxValidation' => true,
            'enableClientValidation' => false,
            'action'=>array('site/login'),
            'htmlOptions' => array(
                    'class' => 'form-light',
            ),
            'clientOptions' => array(
                    'validateOnSubmit'=>false,
                    'validateOnChange'=>false,
                    'validateOnType'=>false,
            )
    )); ?>
    
        <div class="row first">
            <div class="left-short">
                Номер телефона:
            </div>
            <div class="right-medium">
                <?php $this->widget('CMaskedTextField', array(
                        'model' => $model,
                        'attribute' => 'phone',
                        'mask' => '+7 (9?99) 999-99-99',
                        'htmlOptions' => array(
                                'placeholder' => '+7 (XXX) XXX-XX-XX',
                                'class' => 'input-medium',
                                'id' => 'modal-input-phone'
                        ),
                ));?>
            </div>
        </div>
    
        <div class="row">
            <div class="left-short">
                Пароль
            </div>
            <div class="right-medium">
                <?php echo $form->passwordField($model,'password', array(
                        'maxlength'=>16, 
                        'placeholder' => 'Ваш пароль', 
                        'class' => 'input-medium',
                        'id' => 'modal-input-password'
                )); ?>
                <?php echo $form->error($model, 'password', array(
                        'id' => 'error-modal-login'
                ))?>
            </div>
        </div>

        <div class="fancybox-buttons">
            <?php echo CHtml::tag('button', array(
                        'type' => 'button',
                        'class' => 'button-square orange',
                        'id' => 'trSubmitModalLoginForm',
                        'disabled' => 'disabled',
                        'data-login-url' => J::url('site/login'),
                        'data-redirect-url' => J::url('/app/profile/index'),
                        'data-password-field-id' => CHtml::activeId($model, 'password'),
                ), 'Войти');?> 
            <?php echo CHtml::tag('button', array(
                    'type' => 'button', 
                    'class' => 'button-square aquamarine' , 
                    'onClick' => '$.fancybox.close()'
                ), 'Отмена');?>
        </div>
        <?php $this->endWidget(); ?>
</div>
<?php
    Yii::app()->getClientScript()->registerScript('login-form-init', "
        $(document).ready(function(){
            $(document).on('keyup keypress blur', '#modal-input-phone, #modal-input-password', function(){
                var button = $('#trSubmitModalLoginForm');
                var digitCount = $('#modal-input-phone').val().replace(/\D+/g,'').length;
                
                console.log(digitCount == 11 && $('#modal-input-password').val().length >= 6);

                if(digitCount == 11 && $('#modal-input-password').val().length >= 6)
                    $(button).removeClass('disabled-login-button').removeAttr('disabled');
                else
                    $(button).addClass('disabled-login-button').attr('disabled','disabled');

                return true;
            });
        });
    ", CClientScript::POS_READY);
?>