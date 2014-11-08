<h2><?php echo CHtml::image("/images/fancybox-icons/settings.png", "icon", array('width'=>28,'height'=>28));?>Изменение номера мобильного телефона</h2>

    <div class="fancybox-content">
        <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'set-phone-form',
                'htmlOptions' => array(
                        'class' => 'form-light'
                ),
                'enableAjaxValidation' => false,
                'enableClientValidation' => true,
                'clientOptions' => array(
                        'validateOnSubmit' => false,
                        'validateOnChange' => false,
                        'validateOnType' => false,
                )

        )); ?>
            <div id="json-response-questionary" class="flash-message"></div>

            <div class="row first">
                <div class="left-short">
                    <?php echo $form->labelEx($model, 'phone'); ?>
                </div>
                <div class="right-medium">
                    <?php $this->widget('CMaskedTextField', array(
                            'model' => $model,
                            'attribute' => 'phone',
                            'mask' => '+7 (9?99) 999-99-99',
                            'htmlOptions' => array(
                                    'class' => 'input-long',
                                    'placeholder' => '+7 (XXX) XXX-XX-XX',
                            ),
                    ));?>
                    <?php echo $form->error($model, 'phone'); ?>  
                </div>
            </div>

            <div class="row hide" id="newphone-code-row">
                <div class="left-short">
                    <?php echo $form->labelEx($model, 'code'); ?>
                </div>
                <div class="right-medium">
                    <?php echo $form->textField($model, 'code', array('maxlength' => 32, 'class' => 'input-long')); ?>
                    <?php echo $form->error($model, 'code'); ?>
                </div>
            </div>
            <div class="fancybox-buttons">
                <?php echo CHtml::tag('button', array(
                        'type' => 'button',
                        'data-link' => J::url('settings/setphone'),
                        'id' => 'trSubmitPhoneSet',
                        'class' => 'button-square orange'
                ), 'Получить код'); ?>   
                <?php echo CHtml::tag('button', array('type' => 'button', 'class' => 'button-square aquamarine' , 'onClick' => '$.fancybox.close()'), 'Отмена');?>
            </div>
        <?php $this->endWidget(); ?>
    </div> 
