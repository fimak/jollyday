<?php

$this->breadcrumbs=array(
        'Сущности'=>array('/entity'),
        'Подарки'=>array('/entity/gift'),
	'Редактирование',
);

$this->submenu=array(
	array('label'=>'Назад', 'url'=>array('index'))
);
?>

<h2>Редактировать подарок #<?php echo $model->id; ?></h2>

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
	'id'=>'gift-form',
        'type'=>'horizontal',
        'inlineErrors' => true,
	'enableAjaxValidation'=>false,
        'htmlOptions' => array( 
                'enctype' => 'multipart/form-data',
                'class'=>'well'
        ),         
)); ?>
    <?php echo $form->textFieldRow($model,'title',array('size'=>32,'maxlength'=>32)); ?>
    <?php echo $form->textFieldRow($model,'cost',array('size'=>32,'maxlength'=>16)); ?>
    <?php echo $form->fileFieldRow($model, 'uploadedFile'); ?>
    <?php echo $form->fileFieldRow($model, 'uploadedFileBig'); ?>
    <?php echo $form->textFieldRow($model, 'weight',array('size'=>32,'maxlength'=>4)); ?>

    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'label'=>'Сохранить',
        'type'=>'primary',
    )); ?>

<?php $this->endWidget(); ?>

<b>Изображение:</b><br />
<?php echo CHtml::image($model->imageURL); ?><br /><br />
<b>Большое изображение:</b><br />
<?php echo CHtml::image($model->imageURLBig); ?><br />