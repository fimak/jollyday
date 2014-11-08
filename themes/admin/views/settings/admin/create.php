<?php
$this->breadcrumbs=array(
        'Настройки' => array('/audithory'),
	'Администраторы'=>array('index'),
	'Создание',
);

$this->submenu=array(
	array('label'=>'Назад', 'url'=>array('index')),
);
?>

<h2>Создать администратора</h2>

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'user-form',
        'type'=>'horizontal',
        'inlineErrors' => true,
	'enableAjaxValidation'=>true,
        'htmlOptions'=>array('class'=>'well'),
)); ?>	
    <?php echo $form->textFieldRow($model,'phone', array('maxlength'=>32)); ?>   
    <?php echo $form->textFieldRow($model,'password', array('maxlength'=>16)); ?>		
    <?php echo $form->textFieldRow($model,'name'); ?>
    <?php echo $form->textFieldRow($model, 'email'); ?>

    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'label'=>'Создать',
        'type'=>'primary',
    )); ?>

<?php $this->endWidget(); ?>
