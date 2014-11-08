<?php Yii::app()->clientScript->registerMetaTag('Служба поддержки jollyday.ru. Отвечаем на любые вопросы по сайту 24 часа в сутки.', 'description');?>

<?php $this->pageTitle = Yii::app()->name. ' :: ' . 'Служба поддержки';?>

<div id="support-title"></div>

<?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'support-form',
        'action' => J::url('/site/feedback/').'#support-form',
        'htmlOptions' => array(
                'name' => 'support-form'
        )
)); ?>

    <?php $this->widget('application.extensions.yii-flash.EFlash', array(
            'keys' => array('success','error'),
            'htmlOptions'=>array(
                    'class'=>'flash-message'
            ),
            'js' => false,
    )); ?>

    <table>
        <tr>
            <td class="support-table-left-td">
                <?php echo $form->label($model, 'name')?>
            </td>
            <td class="support-table-right-td" colspan="2">
                <?php echo $form->textField($model, 'name', array('class' => 'input-long')); ?>
                <?php echo $form->error($model, 'name'); ?>
            </td>
        </tr>
        <tr>
            <td class="support-table-left-td">
                <?php echo $form->label($model, 'email')?>
            </td>
            <td class="support-table-right-td" colspan="2">
                <?php echo $form->textField($model,'email', array('class' => 'input-long')); ?>
                <?php echo $form->error($model,'email'); ?>
            </td>
        </tr>
        <tr>
            <td class="support-table-left-td">
                <?php echo $form->label($model, 'subject')?>
            </td>
            <td class="support-table-right-td" colspan="2">
                <?php echo $form->dropDownList($model,'subject', Feedback::getSubjects(), array('class' => 'input-long')); ?>
            </td>
        </tr>
        <tr>
            <td class="support-table-left-td">
                <?php echo $form->label($model, 'text')?>
            </td>
            <td class="support-table-right-td support-table-textarea-td" colspan="2">
                <?php echo $form->textArea($model,'text', array('class' => 'input-long')); ?>
                <?php echo $form->error($model,'text'); ?>
            </td>
        </tr>
        <tr>
            <td class="support-table-left-td">
                <?php echo $form->label($model, 'captcha')?>
            </td>
            <td class="support-table-right-td">
                    <?php echo $form->textField($model,'captcha', array('class' => 'input-long')); ?>
                    <?php echo $form->error($model,'captcha', array('style' => 'top: 30px;')); ?>
            </td>
            <td>
                <div id="captcha-wrapper">
                    <?php $this->widget('CCaptcha', array(
                            'clickableImage' => true,
                            'captchaAction' => '/site/captcha',
                            'buttonOptions' => array(
                                    'id' => 'capthcha-button-'.uniqid(),
                            ),
                            'buttonLabel' => 'Получить новый код',
                            'imageOptions' => array(
                                    'class' => 'capthca-image',
                                    'width' => '123px',
                                    'height' => '50px'
                            ),                        
                    )); ?>
                    
                </div>
            </td>
        </tr>
        <tr>
            <td class="support-table-left-td"></td>
            <td class="support-table-right-td">
                <div class="row">
                    <?php echo CHtml::tag('button', array(
                            'type' => 'submit', 
                            'class' => 'button-square aquamarine'), 
                    'Отправить');?>
                </div>
            </td>
        </tr>
    </table>
<?php $this->endWidget(); ?>    