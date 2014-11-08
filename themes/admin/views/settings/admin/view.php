<?php
$this->breadcrumbs=array(
        'Настройки' => array('/settings'),
	'Администраторы'=>array('index'),
	'Просмотр',
);

$this->submenu=array(
	array('label'=>'Назад', 'url'=>array('index')),
	array('label'=>'Редактировать', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Удалить', 'url'=>array('delete', 'id'=>$model->id), 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Точно удалить?')),
);
?>

<h2>Просмотр администратора #<?php echo $model->id; ?></h2>

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

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'phone',
                'name',
                'email',
                'date_lastvisit',
	),
)); ?>


