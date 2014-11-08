<?php
$this->breadcrumbs=array(
	'Статистика',
        'Мобильные'
);

$this->submenu=array(

);
?>

<h2>Мобильные</h2>

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'user-form',
        'type'=>'vertical',
        'htmlOptions'=>array('class'=>'well'),
)); ?>		
    <?php echo $form->dropDownListRow($model,'id_region', Region::getList(),
            array(
                'ajax' => array(
                    'type' => 'post',
                    'url' => J::url('/default/cities'), 
                    'dataType'=>'json',
                    'data'=>array('id_region'=>'js:this.value'),  
                    'success'=>'function(data) {
                        $("#RegionForm_id_city").html(data.dropDownCities).trigger("refresh");
                    }'
                ),
                'class' => 'input-long',
                'prompt' => 'Все регионы'
            )
    );?>
    <?php echo $form->dropDownListRow($model,'id_city', City::getCitiesListByRegion($model->id_region), array(
            'prompt' => 'Все города'
    )); ?>

    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'label'=>'Отправить',
            'type'=>'primary',
        )); ?>
    </div>

<?php $this->endWidget(); ?>

<?php $this->widget('bootstrap.widgets.TbExtendedGridView', array(
        'type'=>'striped bordered condensed',
	'id'=>'feedback-grid',
	'dataProvider'=>$itemsProvider,
	'filter'=>null,
        'enableSorting' => true,
        'selectableRows' => 0,
        'template' => '{items}{extendedSummary}',
	'columns'=>array(
		array(
                    'name' => 'lastdigit',
                    'header' => 'Номер',
                    'value' => '"+7 (***) *** - ***" . $data["lastdigit"]',
                    'footer'=>'Всего пользователей'
                ),
		array(
                    'class'=>'bootstrap.widgets.TbTotalSumColumn',
                    'name' => 'count',
                    'header' => 'Количество пользователей',
                    'value' => '$data["count"]',
                ),  
	),
)); ?>