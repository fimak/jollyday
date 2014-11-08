<?php

$this->breadcrumbs=array(
        'Аудитория' => array('/audithory/default'),
	'Самые популярные' => array('/audithory/toprated/index'),
        'Жалобы'
);
?>



<h2>Самые популярные</h2>

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'region-form',
        'type'=>'vertical',
        'htmlOptions'=>array('class'=>'well'),
)); ?>		
    <?php echo $form->dropDownListRow($model,'id_region', Region::getList(), array(
                'prompt' => 'Все регионы'
    ));?>

    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'label'=>'Выбрать регион',
            'type'=>'primary',
        )); ?>
    </div>

<?php $this->endWidget(); ?>

<div id="toprated-container">
    <?php $this->renderPartial('_photos', array(
            'users' => $users,
    ));?>
</div>

<?php Yii::app()->clientScript->registerScript('toprated-scripts', "
$('#toprated-container').on('click', '.toprated-delete-link', function(){
    if(!confirm('Вы уверены, что хотите убрать пользователя из списка?'))
            return false;
    link = $(this);
    $.ajax({
        url: $(this).data('url-delete'),
        dataType: 'json',
        type: 'post',
        data: $('#region-form').serialize(),
        success: function(data){
            if(data.status == true){
                $(link).parent().fadeOut('fast');
                $('#toprated-container').html(data.html);
            }
            else{
                alert('Ошибка');
            }
        }
    });   
});
", CClientScript::POS_READY)?>