<h2><?php echo CHtml::image("/images/fancybox-icons/registration.png", "icon", array('width'=>28,'height'=>28));?>Бесплатная регистрация</h2>

    <div id="register-text">
        <div id="register-text-title">
            При регистрации укажите действующий номер мобильного телефона.<br />
            На него будет отправлено СМС-сообщение с проверочным кодом.
        </div> 
    </div>

<div class="fancybox-content">

    <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'register-request-form',
            'enableAjaxValidation' => true,
            'enableClientValidation' => false,
            'action'=>array('ajax/request'),
            'htmlOptions' => array(
                    'class' => 'form-light',
            ),
            'clientOptions' => array(
                    'validateOnSubmit'=>false,
                    'validateOnChange'=>false,
                    'validateOnType'=>false,
            ),
            'action' => J::url('/register/ajax/request')
    )); ?>

        <div class="row first">
            <div class="left-short">
                <?php echo $form->label($model,'phone'); ?>
            </div>
            <div class="right-medium">
                <?php $this->widget('CMaskedTextField', array(
                        'model' => $model,
                        'attribute' => 'phone',
                        'mask' => '+7 (9?99) 999-99-99',
                        'htmlOptions' => array(
                                'placeholder' => '+7 (XXX) XXX-XX-XX',
                                'class' => 'input-medium',
                        ),
                ));?>
                <?php echo $form->error($model, 'phone')?>
            </div>
        </div>
        <div class="comment">
            Он будет являться логином для входа на сайт и подтвердит, что Вы - реальный человек.<br />
            Номер мобильного телефона не будет доступен пользователям сайта или третьим лицам.<br />
            Регистрация абсолютно бесплатная. С вашего номера не будут списаны денежные средства.<br />
        </div>

        <div class="row">
            <div class="left-short">
                <?php echo $form->label($model,'password'); ?>
            </div>
            <div class="right-medium">
                <?php echo $form->passwordField($model,'password', array(
                        'maxlength'=>16, 
                        'placeholder' => 'Ваш пароль', 
                        'class' => 'input-medium trRegisterFormTooltip'
                )); ?>
            <div id="registration-password-tooltip">
                Пароль должен содержать минимум 6 максимум 16 символов
            </div>
                <?php echo $form->error($model, 'password')?>
            </div>
        </div>

        <div class="row">
            <div class="left-short">
                <?php echo $form->label($model,'passwordConfirm'); ?>
            </div>
            <div class="right-medium">
                <?php echo $form->passwordField($model,'passwordConfirm', array(
                        'maxlength'=>16, 
                        'placeholder' => 'Ваш пароль', 
                        'class' => 'input-medium'
                )); ?>
                <?php echo $form->error($model, 'passwordConfirm')?>
            </div>
        </div>

        <?php if(CCaptcha::checkRequirements() && Yii::app()->settings->get('SiteAccess', 'regCaptcha')): ?>
            <div class="row">
                <div class="left-short">
                    <?php echo $form->label($model,'verifyCode'); ?>
                </div>
                <div class="right-medium-captcha">
                    <?php echo $form->textField($model,'verifyCode', array('class' => 'input-medium-captcha')); ?>
                    <?php echo $form->error($model, 'verifyCode'); ?>
                </div>
                <div id="register-captcha-wrapper">
                    <?php $this->widget('CCaptcha', array(
                            'clickableImage' => true,
                            'captchaAction' => '/site/captcha',
                            'buttonOptions' => array(
                                    'id' => 'register-captcha-refresh-link',
                            ),
                            'imageOptions' => array(
                                    'id' => 'register-captcha-image',
                            ),
                    )); ?>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if($phoneCookie) : ?><script>disableRegisterForm();</script><?php endif;?>
    
        <div id="register-request-buttons"<?php if($phoneCookie) : ?> class="hide"<?php endif;?>>
            <div class="fancybox-buttons">
                <?php echo CHtml::tag('button', array(
                        'type' => 'button',
                        'id' => 'trRegisterRequest',
                        'class' => 'button-square orange',
                        'data-link' => J::url('ajax/request'),
                ),'Зарегистрироваться');?>
                <?php echo CHtml::tag('button', array(
                        'type' => 'button',
                        'class' => 'button-square aquamarine',
                        'onClick' => '$.fancybox.close()'
                ),'Отмена');?>
            </div>
        </div>
    <?php $this->endWidget(); ?>

    <div id="register-confirm-form-wrapper"<?php if(!$phoneCookie) : ?> class="hide"<?php endif;?>>
        <?php $form =$this->beginWidget('CActiveForm', array(
                'id'=>'register-confirm-form',
                'enableClientValidation'=>false,
                'enableAjaxValidation' => true,
                'clientOptions'=>array(
                        'validateOnSubmit'=>false,
                        'validateOnChange'=>false,
                        'validateOnType'=>false,
                ),
                'htmlOptions' => array(
                        'class' => 'form-light',
                        'onSubmit' => 'return false;'
                ),
                'action' => J::url('/register/ajax/confirm')
        )); ?>  
            
            На указанный Вами номер телефона в течение нескольких минут придет СМС с кодом активации. 

            <div class="row">
                <div class="left-short">
                    <?php echo $form->label($confirm,'sms'); ?>
                </div>
                <div class="right-medium">
                    <?php echo $form->textField($confirm,'sms', array('class' => 'input-medium')); ?>

                    <?php echo $form->error($confirm,'sms'); ?>
                </div>
            </div>
            <div id="register-confirm-buttons" class="<?php if(!$phoneCookie) : ?> hide<?php endif;?>">
                <div class="fancybox-buttons">
                    <?php echo CHtml::tag('button', array(
                            'type' => 'button',
                            'id' => 'trRegisterConfirm',
                            'class' => 'button-square orange',
                            'data-link' => J::url('/register/ajax/confirm'),
                    ),'Подтвердить');?>
                    <?php echo CHtml::tag('button', array(
                            'type' => 'button',
                            'id' => 'trRegisterRefresh',
                            'class' => 'button-square aquamarine',
                            'data-link' => J::url('/register/ajax/refresh'),
                    ),'Другой номер');?>
                </div>
            </div>
        <?php $this->endWidget(); ?>
    </div>
</div>

<?php Yii::app()->clientScript->registerScript('register-form-init', "
$('#register-request-form input').keypress(function(e){
    if(e.keyCode==13){
        $('#trRegisterRequest').trigger('click');
    }
});

$('#register-confirm-form input').keypress(function(e){
    if(e.keyCode==13){
        $('#trRegisterConfirm').trigger('click');
    }
});
", CClientScript::POS_READY);?>