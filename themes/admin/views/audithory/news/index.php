<?php
$this->breadcrumbs=array(
        'Аудитория' => array('/audithory/default'),
	'Новости',
);

$this->submenu=array(
	array('label'=>'Создать', 'url'=>array('create')),
);

?>

<h2>Новости</h2>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
        'type'=>'striped bordered condensed',
	'id'=>'news-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'title',
                'alias',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
                        'template' => '{view} {update} {delete}'
		),
	),
)); ?>