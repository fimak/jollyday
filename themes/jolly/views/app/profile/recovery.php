<?php $this->pageTitle = Yii::app()->name. ' :: ' . 'Восстановление профиля';?>

<h1 id="page-header">Восстановление профиля</h1>

<div class="profile-recovery-form-wrapper">
    Для восстановления анкеты введите код

    <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'profile-recovery-form',
            'htmlOptions' => array(
                    'class' => 'form-light'
            )
    )); ?>
            <div class="row">
                <div class="left-medium">
                    <?php echo $form->textField($model,'captcha', array('class' => '.input-medium')); ?>
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
            <div class="row buttons">
                <?php echo CHtml::submitButton('Восстановить анкету', array(
                                    'class' => 'button-square orange'
                            )
                ); ?>
            </div>

    <?php $this->endWidget(); ?>
</div>    