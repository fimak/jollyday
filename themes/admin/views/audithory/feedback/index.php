<?php
$this->breadcrumbs=array(
        'Аудитория' => array('/audithory'),
	'Служба поддержки',
);

$this->submenu=array(
	
);
?>

<h2>Служба поддержки</h2>
<?php $deleteUrl = J::url('/audithory/feedback/bulk', array('action' => 'delete'))?>
<?php $this->widget('bootstrap.widgets.TbExtendedGridView', array(
        'type'=>'striped bordered condensed',
	'id'=>'feedback-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
        'selectableRows' => 0,
        'rowCssClassExpression' => function($row, $data){
                if($data->status == Feedback::STATUS_NEW)
                        return 'info';
        },
	'columns'=>array(
		'id',
		array(
                    'name' => 'email',
                    'header' => 'Почта',
                    'value' => '$data->email'
                ),
                array(
                    'name' => 'subject',
                    'header' => 'Тема',
                    'value' => 'Feedback::getShortSubjectDescription($data->subject)',
                    'filter' => Feedback::getShortSubjects()
                ),
                'date',
                array(
                    'name' => 'status',
                    'value' => 'Feedback::getStatusDescription($data->status)',
                    'filter' => Feedback::getStatusTypes()
                ),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
                        'template' => '{view} {delete}'
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
                                            $.fn.yiiGridView.update('feedback-grid');
                                        }
                                    });
                                }",
                                'id' => 'feedback-grid-bulk-delete',
                                'htmlOptions' => array(
                                        'confirm' => 'Вы уверены что хотите удалить выбранные сообщения?'
                                )
                        ),
                ),
                'checkBoxColumnConfig' => array(
                        'name' => 'id',
                ),
        ),          
)); ?>
