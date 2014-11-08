<?php
$this->breadcrumbs = array(
        'Аудитория'
);

?>


<h2>Модуль работы с аудиторией</h2>

<h3>Статистика</h3>

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data'=> false,
	'attributes'=>array(
		array(
                    'label' => 'Пользователи',
                    'value' => JStat::getUserCount()
                ),
		array(
                    'label' => 'Предложения',
                    'value' => JStat::getOfferCount()
                ),            
		array(
                    'label' => 'Фотографии',
                    'value' => JStat::getPhotoCount()
                ),  
	),
)); ?>

<h3>Уведомления</h3>

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data'=> false,
	'attributes'=>array(
		array(
                    'label' => 'Спам',
                    'value' => CHtml::link(Spam::countNew(), array('spam/index')),
                    'type' => 'html'
                ),
		array(
                    'label' => 'Служба поддержки',
                    'value' => CHtml::link(Feedback::countNew(), array('feedback/index')),
                    'type' => 'html'
                ),
	),
)); ?>