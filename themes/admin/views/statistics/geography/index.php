<?php
$this->breadcrumbs=array(
	'Статистика',
        'Регионы'
);

$this->submenu=array(

);
?>

<h2>Регионы</h2>

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data'=> false,
	'attributes'=> JStat::userByRegions(),
)); ?>