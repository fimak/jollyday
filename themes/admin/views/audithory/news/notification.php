<?php
$this->breadcrumbs=array(
        'Аудитория' => array('/audithory/default/index'),
	'Новости'=>array('/audithory/news/index'),
	'Уведомление'
);

$this->submenu=array(
	array('label'=>'Назад', 'url'=>array('/audithory/user/view', 'id' => $user->id)),
);
?>

<h2>Отправить уведомление</h2>

<div class="row">
    <div class="span3">
        <?php echo CHtml::image($user->getUserpic('medium'), '', array(
                'class' => 'img-polaroid'
        ))?>
    </div>
    <div class="span6">
        <?php $this->widget('bootstrap.widgets.TbDetailView', array(
                'data'=>$user,
                'attributes'=>array(
                        'id',
                        'phone',
                        'name',
                        array(
                            'name' => 'id_region',
                            'value' => isset($user->region->name) ? $user->region->name : 'Не указано'
                        ),
                        array(
                            'name' => 'id_city',
                            'value' => isset($user->city->name) ? $user->city->name : 'Не указано'
                        ),            
                        array(
                            'name' => 'id_gender',
                            'value' => JGender::getDescription($user->id_gender)
                        ),  
                ),
        )); ?>
    </div>
</div>

<hr />

<p>
   Для оформления вёрстки уведомления следует использовать следующие классы:
</p> 

<table class="table table-bordered table-condensed">
    <tbody>
        <tr>
            <td width="20%"><b>.notice-item-list</b></td>
            <td>Ненумерованный список</td>
        </tr>
        <tr>
            <td><b>.notice-item-hint</b></td>
            <td>Сноска</td>
        </tr>
        <tr>
            <td><b>.notice-item-paragraph</b></td>
            <td>Абзац</td>
        </tr>
    </tbody>
</table>   

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id' => 'notification-form',
        'type' => 'horizontal',
        'inlineErrors' => true,
        'htmlOptions' => array(
                'class'=>'well'
        ),
)); ?>	

    <?php echo $form->textFieldRow($model,'title', array('size'=>60,'maxlength'=>256)); ?>
    <?php echo $form->ckEditorRow($model, 'text', array(
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
    <div class="row">
        <div class="span5">
            <?php echo $form->dropDownListRow($model, 'std_image', News::getStdImageList(), array(
                    'prompt' => 'Выберите изображение',
                    'onChange' => new CJavaScriptExpression("$('#selected-image').attr('src', $(this).val())"),
            )); ?>
        </div>
        <div class="span3">
            <?php echo CHtml::image($model->std_image, '', array(
                    'class' => 'img-polaroid',
                    'id' => 'selected-image',
            ))?>
        </div>
    </div>
    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'label'=>'Отправить',
            'type'=>'primary',
        )); ?>
    </div>

<?php $this->endWidget(); ?>
