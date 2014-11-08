<?php $this->pageTitle = Yii::app()->name. ' :: ' . 'Ошибка';?>

<div>
<h1><?php echo $code; ?></h1>
<?php echo CHtml::encode($message); ?>
</div>