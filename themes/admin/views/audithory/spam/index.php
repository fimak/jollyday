<?php

$this->breadcrumbs=array(
        'Аудитория' => array('/audithory/default'),
	'Спам' => array('spam/index'),
        'Жалобы'
);
?>



<h2>Жалобы</h2>


<?php $deleteUrl = J::url('/audithory/spam/bulk', array('action' => 'delete'))?>
<?php $this->widget('bootstrap.widgets.TbExtendedGridView', array(
        'type'=>'striped bordered condensed',
	'id'=>'spam-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
        'selectableRows' => 0,
        'rowCssClassExpression' => function($row, $data){
                if($data->status == Spam::STATUS_NEW)
                        return 'row-new';
                else
                        return 'row-read';
        },
	'columns'=>array(
		'id',
		array(
                        'header' => 'Отправитель',
                        'name' => 'senderName',
                        'value' => 'CHtml::link($data->sender->name, array("user/view", "id" => $data->sender->id))',
                        'type' => 'html',
                ),
		array(
                        'header' => 'Субъект',                        
                        'name' => 'subjectName',
                        'value' => 'CHtml::link($data->subject->name, array("user/view", "id" => $data->subject->id))',
                        'type' => 'html'
                ), 
		array(                  
                        'header' => 'Телефон субъекта',
                        'name' => 'subjectPhone',
                        'value' => 'CHtml::link($data->subject->phone, array("user/view", "id" => $data->subject->id))',
                        'type' => 'html'
                ),
		'date',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
                        'template' => '{delete}'
		),
	),
	'bulkActions' => array(
                'actionButtons' => array(
                        array(
                                'buttonType' => 'button',
                                'type' => 'primary',
                                'size' => 'small',
                                'label' => 'Удалить помеченные',
                                'click' => "js:function(values){
                                    $.ajax({
                                        dataType: 'json',
                                        type: 'post',
                                        data: values.serialize(),
                                        url: '$deleteUrl',
                                        success: function(data){
                                            $.fn.yiiGridView.update('spam-grid');
                                        }
                                    });
                                }",
                                'id' => 'spam-grid-bulk-delete',
                                'htmlOptions' => array(
                                        'confirm' => 'Вы уверены что хотите удалить выбранные жалобы?'
                                )
                        ),
                ),
                'checkBoxColumnConfig' => array(
                        'name' => 'id',
                ),
        ),          
)); ?>
