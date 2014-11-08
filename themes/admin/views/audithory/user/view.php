<?php
$this->breadcrumbs=array(
        'Аудитория' => array('/audithory'),
	'Пользователи'=>array('index'),
	'Просмотр',
);

$this->submenu=array(
	array('label'=>'Назад', 'url'=>array('index')),
	array('label'=>'Редактировать', 'url'=>array('update', 'id'=>$model->id)),
);
?>

<h2>Просмотр пользователя #<?php echo $model->id; ?></h2>

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
        ),
)); ?>

<div class="row">
    <div class="span6">
    <?php $this->widget('bootstrap.widgets.TbDetailView', array(
            'data'=>$model,
            'attributes'=>array(
                    'id',
                    'phone',
                    'role',
                    'name',
                    'birthday',
                    array(
                        'name' => 'id_region',
                        'value' => isset($model->region->name) ? $model->region->name : 'Не указано'
                    ),
                    array(
                        'name' => 'id_city',
                        'value' => isset($model->city->name) ? $model->city->name : 'Не указано'
                    ),            
                    array(
                        'name' => 'id_gender',
                        'value' => JGender::getDescription($model->id_gender)
                    ),  
                    'email',
                    'date_lastvisit',
                    'date_register',
                    array(
                        'name' => 'fl_banned',
                        'value' => Yii::app()->format->formatBoolean($model->fl_banned)
                    ),
                    array(
                        'name' => 'fl_deleted',
                        'value' => Yii::app()->format->formatBoolean($model->fl_deleted)
                    ),
                    'account',
                    'account_bonus',
            ),
    )); ?>
    </div>
    <div class="span4">
        <?php echo CHtml::image($model->getUserpic('medium'), $model->name, array(
                'class' => 'img-polaroid'
        )); ?>
    </div>
</div>
<div class="btn-toolbar">
    <?php $this->widget('bootstrap.widgets.TbButtonGroup', array(
        'buttons'=>array(
            array(
                'buttonType' => 'ajaxLink',
                'label'=>'История',
                'url' => array('loadhistory', 'id' => $model->id),
                'ajaxOptions' => array(
                        'update' => '#admin-user-ajax-container',
                ),
            ),
            array(
                'buttonType' => 'ajaxLink',
                'label'=>'Сообщения',
                'url' => array('loadmessages', 'id' => $model->id),
                'ajaxOptions' => array(
                        'update' => '#admin-user-ajax-container'
                ),
            ),
            array(
                'buttonType' => 'ajaxLink',
                'label'=>'Фотографии',
                'url' => array('loadalbum', 'id' => $model->id),
                'ajaxOptions' => array(
                        'update' => '#admin-user-ajax-container',
                ),
            ),
        ),
    )); ?>
   
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'link',
        'type' => 'info',
        'label'=> 'Редактировать',
        'url' => array('user/update', 'id'=>$model->id)
    )); ?>  
    
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'link',
        'type' => 'danger',
        'label'=> $model->fl_banned == 0 ? 'Забанить' : 'Снять бан',
        'url' => array('user/ban', 'id' => $model->id),
        'htmlOptions' => array(
                'confirm' => 'Вы уверены, что хотите совершить данную операцию?',
        )    
    )); ?>
    
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'ajaxLink',
        'id' => 'button-toprated-ban',
        'type' => 'danger',
        'label'=> $isTrBlacklisted == 0 ? 'Убрать из СП' : 'Вернуть в СП',
        'url' => array('/audithory/toprated/ban', 'uid' => $model->id),
        'ajaxOptions' => array(
                'dataType' => 'json',
                'success' => 'function(data){
                        if(data.message == "unbanned")
                            $("#button-toprated-ban").html("Убрать из СП");
                        else if(data.message == "banned")
                            $("#button-toprated-ban").html("Вернуть в СП");
                }'
        ),
        'htmlOptions' => array(
                'confirm' => 'Вы уверены, что хотите совершить данную операцию?',
        )
    )); ?>
    
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'link',
        'type' => 'success',
        'label'=> 'Отправить уведомление',
        'url' => array('/audithory/news/notification', 'id' => $model->id),
    )); ?>
    
</div>

<div id="admin-user-ajax-container"></div>


