<?php

$this->breadcrumbs=array(
        'География'=>array('/geography'),
        'Регионы'=>array('/geography/region'),
	'Редактирование',
);

$this->submenu=array(
	array('label'=>'Назад', 'url'=>array('index'))
    );
?>

<h2>Редактировать регион #<?php echo $model->id; ?></h2>

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
	'id'=>'region-form',
        'type'=>'horizontal',
        'inlineErrors' => true,
	'enableAjaxValidation'=>false,
        'htmlOptions'=>array('class'=>'well'),
)); ?>
    <?php echo $form->textFieldRow($model,'name',array('size'=>60,'maxlength'=>64)); ?>
    <?php echo $form->dropDownListRow($model, 'timezone', Region::getAvailableTimezones());?>
    <?php echo $form->dropDownListRow($model, 'id_capital', City::getCitiesListByRegion($model->id));?>

    <?php $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'label' => 'Сохранить',
            'type' => 'primary',
    )); ?>

<?php $this->endWidget(); ?>