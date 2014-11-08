<?php

$this->breadcrumbs=array(
        'География'=>array('/geography'),
        'Города'=>array('/geography/city'),
	'Редактирование',
);

$this->submenu=array(
	array('label'=>'Назад', 'url'=>array('index'))
    );
?>

<h2>Редактировать город #<?php echo $model->id; ?></h2>

<?php $this->widget('bootstrap.widgets.TbAlert', array(
        'block'=>true, 
        'fade'=>true,
        'closeText'=>'&times;',
        'alerts'=>array( 
            'success'=>array(
                'block'=>true,
                'fade'=>true,
                'closeText'=>'&times;'
            ),
            'error'=>array(
                'block'=>true,
                'fade'=>true,
                'closeText'=>'&times;'
            ), 
        ),
)); ?>

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'type'=>'horizontal',
        'inlineErrors' => true,
	'id'=>'city-form',
	'enableAjaxValidation'=>false,
        'htmlOptions'=>array('class'=>'well'),
)); ?>
    <?php echo $form->dropDownListRow($model, 'id_region', Region::getList()); ?>
    <?php echo $form->textFieldRow($model,'name',array('size'=>32,'maxlength'=>128)); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'label'=>'Сохранить',
        'type'=>'primary',
    )); ?>

<?php $this->endWidget(); ?>
