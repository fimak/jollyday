<?php $this->widget('bootstrap.widgets.TbGridView', array(
        'type'=>'striped bordered condensed',
	'id'=>'messages-grid',
	'dataProvider'=>$model->search($userId),
        'filter' => $model,
        'template' => '{items}{pager}',
        'selectableRows' => 0,
	'columns'=>array(
                'id',
                'date',
                'text',
		array(
                        'header' => 'Отправитель',
                        'name' => 'senderName',
                        'value' => 'isset($data->sSender) ? CHtml::link($data->sSender->name, array("user/view", "id" => $data->sSender->id)) : "Не существует"',
                        'type' => 'html',
                ),
		array(
                        'header' => 'Получатель',                        
                        'name' => 'recieverName',
                        'value' => 'isset($data->sReciever) ? CHtml::link($data->sReciever->name, array("user/view", "id" => $data->sReciever->id)) : "Не существует"',
                        'type' => 'html'
                ), 
	),
)); ?>