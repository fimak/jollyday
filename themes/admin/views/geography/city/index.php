<?php

$this->breadcrumbs=array(
	'География'=>array('/geography/default'),
	'Города',
);

?>

<h2>Города</h2>

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
    <?php echo $form->dropDownListRow($new, 'id_region', Region::getList()); ?>
    <?php echo $form->textFieldRow($new,'name',array('size'=>32,'maxlength'=>128)); ?>

    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'label'=>'Создать',
        'type'=>'primary',
    )); ?>

<?php $this->endWidget(); ?>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
        'type'=>'striped bordered condensed',
	'id'=>'city-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		array(
                        'name' => 'id_region',
                        'value' => '"[" . $data->parentRegion->id . "] " . $data->parentRegion->name',
                        'filter' => Region::getList(),
                ),
		'name',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
                        'template' => '{update}{delete}'
		),
	),
)); ?>

