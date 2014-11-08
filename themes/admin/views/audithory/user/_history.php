<?php $this->widget('bootstrap.widgets.TbGridView', array(
        'type'=>'striped bordered condensed',
	'id'=>'history-grid',
	'dataProvider'=>$model->search($userId),
        'filter' => $model,
        'template' => '{items}{pager}',
        'selectableRows' => 0,
	'columns'=>array(
                'date',
                array(
                        'name' => 'id_event',
                        'value' => 'History::getEventDescription($data->id_event)',
                        'filter' => History::getEventsList()
                ),
                array(
                        'name' => 'message',
                        'type' => 'html'
                ),
	),
)); ?>