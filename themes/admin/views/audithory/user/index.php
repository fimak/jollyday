<?php
$this->breadcrumbs=array(
        'Аудитория' => array('/audithory/default'),
	'Пользователи',
);

$this->submenu=array(
	array('label'=>'Создать', 'url'=>array('create')),
);

?>

<h2>Пользователи</h2>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
        'type'=>'striped bordered condensed',
	'id'=>'user-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
                array(
                        'name' => 'online',
                        'value' => '$data->getOnlineStatus()',
                        'type' => 'boolean',
                        'filter' => array('1' => 'Да'),
                ),
                array(
                        'class'=>'bootstrap.widgets.TbImageColumn',
                        'header' => 'Фото',
                        'imagePathExpression' => '$data->getUserpic("small")',
                ),
		'id',
		'phone',
                'name',
                'id_gender'=> array(
                        'name' => 'id_gender',
                        'value' => 'JGender::getDescription($data->id_gender)',
                        'filter' => JGender::getList(),
                ),
                'email',
                array(
                        'name' => 'id_region',
                        'filter' => Region::getList(),
                        'value' => 'isset($data->region->name) ? $data->region->name : ""',
                ),
                'city.name',
                array(
                        'name' => 'account',
                        'filter' => false
                ),
                array(
                        'name' => 'account_bonus',
                        'filter' => false,
                ),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
                        'template' => '{view} {update}',
                        'viewButtonOptions' => array(
                                'target' => '_blank',
                        ),
                        'updateButtonOptions' => array(
                                'target' => '_blank'
                        )
		),
	),
)); ?>
