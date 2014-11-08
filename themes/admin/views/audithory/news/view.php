<?php
$this->breadcrumbs=array(
        'Аудитория' => array('/audithory'),
	'Новости'=>array('index'),
	'Просмотр',
);

$this->submenu=array(
	array('label'=>'Назад', 'url'=>array('index')),
	array('label'=>'Редактировать', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Удалить', 'url'=>array('delete', 'id'=>$model->id), 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Точно удалить?')),
);
?>

<h2>Просмотр новости #<?php echo $model->id; ?></h2>

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

<hr />

<h4><?php echo CHtml::encode($model->title); ?></h4>

    <?php echo CHtml::image($model->imageURL); ?>

<hr />

<div class="news-text">
    <?php echo $model->text; ?>
</div>

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'delivery-form',
        'type'=>'vertical',
        'inlineErrors' => true,
        'htmlOptions' => array( 
                'class' => 'well'
        ),
        'action' => 'news/delivery'
)); ?>
        <?php echo $form->dropDownListRow($delivery, 'id_region', Region::getList(), array(
                'multiple' => true,
                'size' => 16,
        )); ?>
        <div><p>Если не выбран ни один регион, то рассылка будет осуществлена по всем регионам</p></div>
        
        <div class="form-actions">
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                    'buttonType' => 'ajaxSubmit',
                    'label'=>'Рассылка',
                    'type'=>'primary',
                    'url' => array('news/delivery', 'id' => $model->id),
                    'htmlOptions' => array(
                            'confirm' => 'Вы уверены, что хотите разослать новость?',
                    ),
                    'ajaxOptions' => array(
                        'url' => array('news/delivery'),
                        'dataType' => 'json',
                        'success' => "function(data){
                                $('#json-response').html('Новость получили ' + data.count + ' пользователей').show();
                        }"
                    )     
            )); ?>
        </div>
        <div class="alert in fade alert-success hide" id = "json-response">Изменения сохранены</div>
    </div>

<?php $this->endWidget(); ?>