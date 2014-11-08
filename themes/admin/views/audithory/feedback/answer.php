<?php
$this->breadcrumbs=array(
        'Аудитория' => array('/audithory'),
	'Служба поддержки' => array('index'),
        'Переписка'
);

$this->submenu=array(
	array('label'=>'Назад', 'url'=>array('index')),
);
?>

<h2>Служба поддержки - ответ</h2>

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

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data' => $model,
	'attributes' => array(
                'id',
                array(
                    'label' => 'Имя',
                    'value' => $model->name
                ),
                array(
                    'label' => 'Почта',
                    'value' => $model->email
                ),
                array(
                    'label' => 'Телефон',
                    'value' => Yii::app()->format->formatPhone($model->phone, true)
                ),
                array(
                    'label' => 'Можно связаться по телефону',
                    'value' => $model->is_phone_contact,
                    'type' => 'boolean'
                ),
                array(
                    'label' => 'Тема',
                    'value' => Feedback::getShortSubjectDescription($model->subject),
                ),
                'text',
                array(
                    'label' => 'Ответ',
                    'value' => $model->answer,
                    'type' => 'html'
                ),
                array(
                    'label' => 'Статус',
                    'value' => Feedback::getStatusDescription($model->status),
                ),
                'date'
	),
)); ?>

<?php if($model->status == Feedback::STATUS_NEW) : ?>
    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id'=>'feedback-answer-form',
            'type'=>'vertical',
            'inlineErrors' => true,
            'htmlOptions' => array( 
                    'class' => 'well'
            ),  
    )); ?>	
        <?php echo $form->textFieldRow($model,'mailSubject', array('maxlength'=>255)); ?>
        <?php echo $form->ckEditorRow($model, 'answer', array(
                'options' => array(
                        'toolbar' => array(
                                array(
                                        'name' => 'document',
                                        'items' => array('Source','-','Save','NewPage','DocProps', 'Preview'),
                                ),
                                array(
                                        'name' => 'clipboard',
                                        'items' => array('Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo'),
                                ),
                                array(
                                        'name' => 'basicstyles',
                                        'items' => array('Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat'),
                                ),
                                array(
                                        'name' => 'links',
                                        'items' => array('Link','Unlink','Anchor'),
                                ),
                                array(
                                        'name' => 'insert',
                                        'items' => array('Image','Table','HorizontalRule','SpecialChar'),
                                ),
                                array(
                                        'name' => 'colors',
                                        'items' => array('TextColor','BGColor'),
                                ),
                                array(
                                            'name' => 'paragraph',
                                            'items' => array('NumberedList','BulletedList','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','CreateDiv',)
                                ),
                                array(
                                        'name' => 'tools',
                                        'items' => array('Maximize', 'ShowBlocks')
                                ),
                        ),
                ),
        ));?>
        <p>
            <br />
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit',
                'label'=>'Ответить',
                'type'=>'primary',
            )); ?>
        </p>
    <?php $this->endWidget(); ?>
<?php else :?>
    Обращение обработано
<?php endif; ?>
