<?php
$this->breadcrumbs = array(
        'Сущности'
);

?>


<h2>Модуль управления сущностями сайта</h2>

<h3>Статистика</h3>

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data'=> false,
	'attributes'=>array(
		array(
                    'label' => 'Подарки',
                    'value' => JStat::getGiftCount()
                ),       
	),
)); ?>