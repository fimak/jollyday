<h2><?php echo CHtml::image("/images/fancybox-icons/delete.png", "icon", array('width'=>28,'height'=>28));?>Удаление анкеты</h2>

<div class="fancybox-content">
    Введите проверочный код для подтверждения удаления анкеты:

    <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'delete-profile-form',
            'htmlOptions' => array(
                    'class' => 'form-light'
            )
    )); ?>
	<div class="row">
            <div class="left-medium">
                <?php echo $form->textField($model,'captcha', array('class' => '.input-medium-capthca')); ?>
                <?php echo $form->error($model,'captcha'); ?>
            </div>
            <div class="capthca-image-row">
                <?php $this->widget('CCaptcha', array(
                        'clickableImage' => true,
                        'captchaAction' => '/site/captcha',
                        'buttonOptions' => array(
                                'id' => 'capthcha-button-'.uniqid(),
                        ),
                        'buttonLabel' => 'Обновить код',
                        'imageOptions' => array(
                                'class' => 'capthca-image',
                                'width' => '123px',
                                'height' => '50px'
                        ),                        
                )); ?>
            </div>
        </div>
        <div class="fancybox-buttons">
            <?php echo CHtml::ajaxSubmitButton('Удалить анкету', array('settings/delete'), array(
                                'url' => array('settings/delete'),
                                'type' => 'post',
                                'update' => '#fancybox-container'
                        ),
                        array(
                                'id' => 'profile-delete-uid-'.uniqid(),
                                'class' => 'button-square orange'
                        )
            ); ?>
            <?php echo CHtml::tag('button', array('type' => 'button', 'class' => 'button-square aquamarine' , 'onClick' => '$.fancybox.close()'), 'Отмена');?>
        </div>
    <?php $this->endWidget(); ?>    
</div>