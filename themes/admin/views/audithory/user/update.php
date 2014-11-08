<?php
$this->breadcrumbs=array(
        'Аудитория' => array('/audithory'),
	'Пользователи'=>array('index'),
	'Редактирование',
);

$this->submenu=array(
        array('label'=>'Назад', 'url'=>array('index')),
	array('label'=>'Просмотр', 'url'=>array('view', 'id'=>$model->id)),
);
?>

<h2>Редактирование пользователя #<?php echo $model->id; ?></h2>

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'user-form',
        'type'=>'horizontal',
        'inlineErrors' => true,
	'enableAjaxValidation'=>true,
        'htmlOptions'=>array('class'=>'well'),
)); ?>	
    <?php echo $form->textFieldRow($model,'phone', array('maxlength'=>10)); ?>   	
    <?php echo $form->dropDownListRow($model, 'role', User::getRoleList()); ?>	
    <?php echo $form->textFieldRow($model,'name'); ?>
    <div class="control-group">
            <?php echo $form->labelEx($model,'birthday', array(
                    'class' => 'control-label'
            )); ?>
            <div class="controls">
                <?php $this->widget('JDropDownDate', array(
                            'model' => $model,
                            'attribute' => 'birthday',
                            'inlineControls' => false,
                ));?>
            </div>
            <?php echo $form->error($model,'birthday', array(
                    'class' => 'help-inline error'
            )); ?>
    </div>
    <?php echo $form->dropDownListRow($model, 'id_region', Region::getList(), array(
            'prompt'=>'Выберите регион',
            'ajax' => array(
                    'type'=>'POST',
                    'url'=>CController::createUrl('loadCities'), 
                    'dataType'=>'json',
                    'data'=>array('id_region'=>'js:this.value'),  
                    'success'=>'function(data) {
                        $("#User_id_city").html(data.dropDownCities);
                    }',
            )
    ))?>
    <?php echo $form->dropDownListRow($model,'id_city', isset($model->id_city) ? City::getCitiesListByRegion($model->id_region) : array('' => 'Выберите город')); ?>
    <?php echo $form->dropDownListRow($model, 'id_gender', JGender::getList()); ?>
    <?php echo $form->textFieldRow($model,'email',array('size'=>60,'maxlength'=>64)); ?>
    <?php echo $form->textFieldRow($model,'account'); ?>
    <?php echo $form->textFieldRow($model,'account_bonus'); ?>

    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'label'=>'Сохранить',
        'type'=>'primary',
    )); ?>

    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'link',
        'type' => 'info',
        'label'=> 'Просмотр',
        'url' => array('user/view', 'id'=>$model->id)
    )); ?>  

<?php $this->endWidget(); ?>

