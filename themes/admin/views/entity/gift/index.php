<?php

$this->breadcrumbs=array(
	'Сущности'=>array('/entity/default'),
	'Подарки',
);

?>

<h2>Подарки</h2>
 
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
	'id'=>'gift-form',
	'enableAjaxValidation'=>false,
        'htmlOptions' => array( 
                'enctype' => 'multipart/form-data',
                'class' => 'well'
        ),         
)); ?>
    <?php echo $form->textFieldRow($new, 'title', array('size'=>32,'maxlength'=>32)); ?>
    <?php echo $form->textFieldRow($new,'cost',array('size'=>32,'maxlength'=>16)); ?>
    <?php echo $form->fileFieldRow($new, 'uploadedFile'); ?>
    <?php echo $form->fileFieldRow($new, 'uploadedFileBig'); ?>
    <?php echo $form->textFieldRow($new, 'weight',array('size'=>32,'maxlength'=>4)); ?>
 
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'label'=>'Создать',
        'type'=>'primary',
    )); ?>

<?php $this->endWidget(); ?>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
        'type'=>'striped bordered condensed',
	'id'=>'gift-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
                        'name' => 'id',
                        'htmlOptions' => array(
                                'style' => 'width:20px;text-align:center'
                        ),                        
                ),
                array(
                        'name' => 'image',
                        'value' => 'CHtml::image($data->imageURL)',
                        'type' => 'html',
                        'htmlOptions' => array(
                                'style' => 'width:50px;text-align:center'
                        ),
                ),            
                'title',
                'cost',
                'weight',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
                        'template' => '{update}{delete}'
		),
	),
)); ?>