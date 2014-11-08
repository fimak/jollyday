<?php
$this->breadcrumbs=array(
        'Настройки' => array('/settings/default'),
	'Администраторы',
);

$this->submenu=array(
	array('label'=>'Создать', 'url'=>array('create')),
);

?>

<h2>Администраторы</h2>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
        'type'=>'striped bordered condensed',
	'id'=>'user-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'phone',
                'name',
                'email',
                'date_lastvisit',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
