<?php
$this->breadcrumbs = array(
        'География'
);

?>

<h2>Модуль географии пользователей</h2>

<h3>Статистика</h3>

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data'=> false,
	'attributes'=>array(
		array(
                    'label' => 'Регионы',
                    'value' => JStat::getRegionCount()
                ),
		array(
                    'label' => 'Города',
                    'value' => JStat::getCityCount()
                ),            
	),
)); ?>