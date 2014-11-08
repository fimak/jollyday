<div id="login-form-wrapper" class="header-top-block">
    <?php $form = $this->beginWidget('CActiveForm', array(
            'id' => 'login-form',
            'enableClientValidation' => true,
            'clientOptions' => array(
                    'validateOnSubmit' => true,
                    'validateOnType' => false,
                    'validateOnChange' => false,
                    'errorMessageCssClass' => 'login-form-error'
            ),
    )) ?>
        <?php $this->widget('CMaskedTextField', array(
                'model' => $model,
                'attribute' => 'phone',
                'mask' => '+7 (9?99) 999-99-99',
                'htmlOptions' => array(
                        'class' => 'login-form-phone',
                        'placeholder' => '+7 (XXX) XXX-XX-XX',
                ),
        ));?>
        <?php $this->widget('application.widgets.JPlaceholder.JPlaceholder', array(
                'target' => '#'.CHtml::activeId($model, 'phone'),
        ));?>
        <?php echo $form->passwordField($model, 'password', array(
                'placeholder' => 'Ваш пароль',
                'class' => 'login-form-password',
                'maxlength' => 16,
        ))?>
        <?php $this->widget('application.widgets.JPlaceholder.JPlaceholder', array(
                'target' => '#'.CHtml::activeId($model, 'password'),
        ));?>
        <?php echo $form->error($model, 'password', array(
                'class' => 'login-form-error'
        )); ?>
    
        <p>
                <?php echo CHtml::tag('button', array(
                        'type' => 'button',
                        'class' => 'login-form-button disabled-login-button',
                        'id' => 'trSubmitLoginForm',
                        'disabled' => 'disabled',
                        'data-login-url' => J::url('/site/login'),
                        'data-redirect-url' => J::url('/app/profile/index'),
                        'data-password-field-id' => CHtml::activeId($model, 'password'),
                ), 'Войти');?>
                <?php echo CHtml::link('Я забыл пароль', "javascript:void(0)", array(
                        'id' => 'trLoadRecoveryForm',
                        'data-link' => J::url('/register/recovery/index')
                ))?>
        </p>    
    <?php $this->endWidget();?>
</div>