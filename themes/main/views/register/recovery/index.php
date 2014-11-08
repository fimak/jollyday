<?php $this->pageTitle = Yii::app()->name. ' :: ' . 'Восстановление пароля';?>

<h2><?php echo CHtml::image("/images/fancybox-icons/settings.png", "icon", array('width'=>28,'height'=>28));?>Восстановление пароля</h2>

<?php $this->widget('application.widgets.JFormStyler.JFormStyler', array(
        'target' => '#recovery-form select',
        'skin' => 'jolly'
));?>

<div class="fancybox-content">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'recovery-form',
	'enableClientValidation'=>false,
        'enableAjaxValidation' => true,
        'htmlOptions' => array(
                'class' => 'form-light',
        ),
        'clientOptions' => array(
                'validateOnSubmit'=>false,
                'validateOnChange'=>false,
                'validateOnType'=>false,
        )
)); ?>
    <div id="recovery-form-wrapper">
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
                                'class' => 'input-long',
                        ),
                ));?>
                <?php echo $form->error($model,'phone'); ?>
            </div>
	</div>

	<div class="row">
            <div class="left-short">
		<?php echo $form->labelEx($model,'birthday'); ?>
            </div>
            <div class="right-medium">
                <?php $this->widget('JDropDownDate', array(
                            'model' => $model,
                            'attribute' => 'birthday',
                            'dOptions' => array('class' => 'input-day'),
                            'mOptions' => array('class' => 'input-month'),
                            'yOptions' => array('class' => 'input-year')
                ));?>
		<?php echo $form->error($model,'birthday'); ?>
            </div>
	</div>

	<?php if(CCaptcha::checkRequirements()): ?>
        <div class="row">
            <div class="left-short">
                <?php echo $form->label($model,'verifyCode'); ?>
            </div>
            <div class="right-medium">
                <?php echo $form->textField($model,'verifyCode', array('class' => 'input-long')); ?>
                <?php echo $form->error($model, 'verifyCode'); ?>
            </div>
            <div id="register-captcha-wrapper">
                <?php $this->widget('CCaptcha', array(
                        'clickableImage' => true,
                        'captchaAction' => '/site/captcha',
                        'buttonOptions' => array(
                                'id' => 'recoverypass-capthcha-button',
                        ),
                        'imageOptions' => array(
                                'id' => 'recoverypass-captcha-image',
                        ),
                )); ?>
            </div>
        </div>
        <div id="recovery-form-message"></div>
        
	<?php endif; ?>
    </div>
	<div class="fancybox-buttons">
                <?php echo CHtml::tag('button', array(
                        'type' => 'button',
                        'id' => 'trRecoveryPassword',
                        'class' => 'button-square orange',
                        'data-link' => J::url('/register/recovery/index'),
                ),'Восстановить');?>
                <?php echo CHtml::tag('button', array(
                        'type' => 'button',
                        'class' => 'button-square aquamarine',
                        'onClick' => '$.fancybox.close()'
                ),'Отмена');?>
	</div>

<?php $this->endWidget(); ?>

</div>