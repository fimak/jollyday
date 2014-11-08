<?php $this->pageTitle = 'Ошибка' . ' - ' . Yii::app()->name;?>

<div>
<h1><?php echo $code; ?></h1>
<?php echo CHtml::encode($message); ?>
</div>