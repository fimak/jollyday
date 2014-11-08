<?php
$this->breadcrumbs = array(
    'Настройки',
    'Основные'
);
?>

<h2>Основные настройки</h2>

<?php $this->widget('bootstrap.widgets.TbAlert', array(
        'id' => 'settings-flash',
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

<div class="well">  
    <h4>Статус сайта</h4>
        <div class="controls">
            <?php $this->widget('bootstrap.widgets.TbLabel', array(
                'type'=>Yii::app()->settings->get('SiteAccess','maintenanceMode') ? 'important' : 'success',
                'label'=>Yii::app()->settings->get('SiteAccess','maintenanceMode') ? 'Offline' : 'Online',
            )); ?>

            <?php if(Yii::app()->settings->get('SiteAccess','maintenanceMode')) : ?>
                Сайт закрыт на техническое обслуживание
            <?php else: ?>
                Сайт окрыт для посетителей
            <?php endif; ?>
            <br /><br />
        </div>
        <div class="controls">
            <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                    'type'=>'vertical',
                    'inlineErrors' => true,
                    'id'=>'maintenance-form',
                    'enableAjaxValidation'=>false,
            )); ?>
                <?php echo $form->checkBoxRow($maintenanceForm, 'enable')?>
                <?php echo $form->html5EditorRow($maintenanceForm, 'text', array(
                        'class' => 'span4', 
                        'rows' => 5, 
                        'height' => '200', 
                        'options' => array(
                                'color' => true
                        )
                )); ?>
                    Сейчас: <b><span id="settings-clock"></span></b> по UTC (Москва: UTC + 04:00)
            <?php $this->endWidget(); ?>
        </div>

        <div class="form-actions">
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType'=>'link',
                'type'=>'danger',
                'url' => array('maintenance'),
                'label' => Yii::app()->settings->get('SiteAccess','maintenanceMode') ? 'Включить сайт' : 'Выключить сайт',
            )); ?>
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'id' => 'maintenance-form-submit',
                'buttonType'=>'ajaxLink',
                'type' => 'primary',
                'url' => array('maintenancealert'),
                'label' => 'Изменить отображение оповещения',
                'ajaxOptions' => array(
                        'data' => "js:$('#maintenance-form').serialize()",
                        'type' => 'post',
                        'dataType' => 'json',
                        'success' => "js:function(data){
                                if(data.status == 'success'){
                                    $('#settings-flash').html('<div class=\'alert in alert-block fade alert-success\'></div>')
                                    $('#settings-flash .alert').html(data.message).append('<a class=\'close\' data-dismiss=\'alert\' href=\'#\'>&times;</a>');
                                }
                        }"
                ),
            )); ?>
        </div>
 
</div>


<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'type'=>'horizontal',
        'inlineErrors' => true,
	'id'=>'settings-form',
	'enableAjaxValidation'=>false,
)); ?>
    <div class="well">
        <h4>Защита от нежелательных регистраций</h4>
        <?php echo $form->checkBoxRow($model, 'regProtectionDay'); ?>
        <?php echo $form->checkBoxRow($model, 'regProtection15Min'); ?>
        <?php echo $form->checkBoxRow($model, 'regCaptcha'); ?>
    </div>

    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=>'submit',
        'type'=>'primary',
        'label'=>'Сохранить настройки'
    )); ?>

<?php $this->endWidget(); ?>


<script>
    function startTime() {
        var tm = new Date();
        var h = tm.getUTCHours();
        var m = tm.getUTCMinutes();
        var s = tm.getUTCSeconds();
        m = checkTime(m);
        s = checkTime(s);
        document.getElementById('settings-clock').innerHTML = h + ':' + m + ':' + s;
        t = setTimeout('startTime()',500);
    }

    function checkTime(i) {
        if(i<10)
            i = '0' + i;

        return i;
    }
</script>
<?php Yii::app()->clientScript->registerScript('clock',"startTime();")?>