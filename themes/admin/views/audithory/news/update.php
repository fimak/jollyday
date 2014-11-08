<?php
$this->breadcrumbs=array(
        'Аудитория' => array('/audithory'),
	'Новости'=>array('index'),
	'Редактирование',
);

$this->submenu=array(
        array('label'=>'Назад', 'url'=>array('index')),
	array('label'=>'Просмотр', 'url'=>array('view', 'id'=>$model->id)),
);
?>

<h2>Редактирование новости #<?php echo $model->id; ?></h2>

<p>
   Для оформления вёрстки новости следует использовать следующие классы:
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
	'id'=>'news-form',
        'type'=>'vertical',
        'inlineErrors' => true,
	'enableAjaxValidation'=>true,
        'htmlOptions' => array( 
                'enctype' => 'multipart/form-data',
                'class' => 'well'
        ),  
)); ?>	
    <?php echo $form->textFieldRow($model, 'title', array('maxlength'=>255)); ?>
    <?php echo $form->textFieldRow($model, 'alias', array('maxlength'=>32)); ?>
    <?php echo $form->fileFieldRow($model, 'uploadedFile'); ?>
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
    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'label'=>'Редактировать',
            'type'=>'primary',
        )); ?>
    </div>
<?php $this->endWidget(); ?>
