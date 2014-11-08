<?php
$this->breadcrumbs=array(
        'Настройки' => array('/audithory'),
	'Администраторы'=>array('index'),
	'Редактирование',
);

$this->submenu=array(
	array('label'=>'Назад', 'url'=>array('index')),
);
?>

<h2>Редактировать администратора #<?php echo $model->id; ?></h2>
<div class="container">
<p>
        <?php $this->widget('bootstrap.widgets.TbBadge', array(
            'type'=>'info', // 'success', 'warning', 'important', 'info' or 'inverse'
            'label'=>'!',
        )); ?> 
        Если вы хотите изменить пароль, то заполните соответствующее поле, и пароль изменится
</p>
</div>



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
        'label'=>'Сохранить',
        'type'=>'primary',
    )); ?>

<?php $this->endWidget(); ?>