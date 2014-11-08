<?php
$this->pageTitle=Yii::app()->name;
$this->breadcrumbs=array(
	'Вход',
);
?>

<h2>Вход</h2>

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'login-form',
	'enableAjaxValidation'=>false,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
        'action' => $this->createUrl('default/login')
)); ?>
    <?php echo $form->textFieldRow($model,'username', array('maxlength' => 10)); ?>
    <?php echo $form->passwordFieldRow($model,'password', array('maxlength' => 16)); ?>

    <?php echo $form->label($model, 'captcha')?>
    <?php echo $form->textField($model,'captcha'); ?>
    <?php echo $form->error($model,'captcha'); ?>

    <div>
        <?php $this->widget('CCaptcha', array(
                'clickableImage' => true,
                'captchaAction' => '/default/captcha',
                'buttonLabel' => false,
                'imageOptions' => array(
                        'style' => 'cursor:pointer;border:1px solid black;margin-bottom:10px'
                )
        )); ?>
    </div>
    <div>
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'label'=>'Войти',
        )); ?>
    </div>
<?php $this->endWidget(); ?>

