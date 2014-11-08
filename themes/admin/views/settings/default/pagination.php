<?php
$this->breadcrumbs = array(
    'Настройки',
    'Пагинация'
);
?>

<h2>Настройки пагинации</h2>
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


<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'type'=>'vertical',
        'inlineErrors' => true,
	'id'=>'settings-pagination-form',
        'htmlOptions' => array(
                'class' => 'well'
        ),
)); ?>

    <?php echo $form->textFieldRow($model,'compactMessages', array('maxlength'=>3, 'class' => 'span1')); ?>
    <?php echo $form->textFieldRow($model,'profileMessages', array('maxlength'=>3, 'class' => 'span1')); ?>
    <?php echo $form->textFieldRow($model,'chatMessages', array('maxlength'=>3, 'class' => 'span1')); ?>
    <?php echo $form->textFieldRow($model,'news', array('maxlength'=>3, 'class' => 'span1')); ?>
    <?php echo $form->textFieldRow($model,'feedbacks', array('maxlength'=>3, 'class' => 'span1')); ?>
    <?php echo $form->textFieldRow($model,'searchResults', array('maxlength'=>3, 'class' => 'span1')); ?>
    <div>
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType'=>'submit',
            'type'=>'primary',
            'label'=>'Сохранить'
        )); ?>
    </div>

<?php $this->endWidget(); ?>