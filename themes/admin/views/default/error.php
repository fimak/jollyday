<?php
$this->pageTitle = 'Ошибка';
$this->breadcrumbs = array(
    'Ошибка'
);

$this->menu=array(
	array('label'=>'На главную', 'url'=>array('/'))
);

?>
<div>
    <h2><?php echo $code; ?></h2>
    <?php echo CHtml::encode($message); ?>
</div>