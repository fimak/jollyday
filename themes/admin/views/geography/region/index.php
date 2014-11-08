<?php
/* @var $this RegionController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'География'=>array('/geography/default'),
	'Регионы',
);

?>

<h2>Регионы</h2>

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
	'id'=>'region-form',
	'enableAjaxValidation'=>false,
        'htmlOptions'=>array('class'=>'well'),        
)); ?>

    <?php echo $form->textFieldRow($new,'name',array('size'=>60,'maxlength'=>64)); ?>
    <?php echo $form->dropDownListRow($new, 'timezone', Region::getAvailableTimezones());?>

    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'label'=>'Создать',
        'type'=>'primary',
    )); ?>

<?php $this->endWidget(); ?>
<p>Столицу можно выбрать при редактировании региона</p>


<?php $this->widget('bootstrap.widgets.TbGridView', array(
        'type'=>'striped bordered condensed',
	'id'=>'region-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'name',
                array(
                        'name' => 'timezone',
                        'filter' => Region::getAvailableTimezones()
                ),
                array(
                        'name' => 'capitalName',
                        'value' => 'isset($data->capital) ? $data->capital->name : null'
                ),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
                        'template' => '{update}{delete}'
		),
	),
)); ?>
