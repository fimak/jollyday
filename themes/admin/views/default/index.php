<?php
$this->breadcrumbs = array(
    'Главная'
);
?>

<h1>Администрирование</h1>
<?php $this->widget('bootstrap.widgets.TbAlert', array(
        'block'=>true, 
        'fade'=>true,
        'closeText'=>'&times;',
        'alerts'=>array( 
            'success'=>array(
                'block'=>true,
                'fade'=>true,
                'closeText'=>'&times;'
            ),
            'error'=>array(
                'block'=>true,
                'fade'=>true,
                'closeText'=>'&times;'
            ),
            'info'=>array(
                'block'=>true,
                'fade'=>true,
                'closeText'=>'&times;'
            ),
            'warning'=>array(
                'block'=>true,
                'fade'=>true,
                'closeText'=>'&times;'
            ), 
        ),
)); ?>

<h3>Статистика</h3>

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data'=> false,
	'attributes'=>array(
		array(
                    'label' => 'Пользователей онлайн',
                    'value' => JStat::getOnlineUsers()
                ),
		array(
                    'label' => 'Статус сайта',
                    'value' => !Yii::app()->settings->get('SiteAccess', 'maintenanceMode') ? 'Работает' : 'Закрыт на техническое обслуживание'
                ),            
	),
)); ?>