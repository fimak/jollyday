<?php if($operation == JPayment::OPERATION_GIFT) : ?>
    <h2><?php echo CHtml::image("/images/fancybox-icons/payment.png", "icon", array('width'=>28,'height'=>28));?>Оплатить подарок</h2>
<?php elseif($operation == JPayment::OPERATION_RATING):?>
    <h2><?php echo CHtml::image("/images/fancybox-icons/payment.png", "icon", array('width'=>28,'height'=>28));?>Подняться в рейтинге</h2>
<?php endif;?>

<div class="fancybox-content-thin">
    
        <?php if($operation == JPayment::OPERATION_GIFT) : ?>
            <?php $this->renderPartial('_pay_top_gift', array(
                'viewData' => $viewData
            ));?>
        <?php elseif($operation == JPayment::OPERATION_RATING):?>
            <?php $this->renderPartial('_pay_top_rateup', array(
                'viewData' => $viewData
            ));?>
        <?php endif;?>

                        
        <?php echo CHtml::beginForm(Yii::app()->intellectmoney->server, 'post');?>
            <div id="fancybox-payment-form">
                <div id="fancybox-payment-form-title">Оплатить с помощью:
                    <span id="fancybox-payment-form-radiolist">
                    <?php echo CHtml::radioButtonList('payment_methods', 'bankCard', JPayment::getIntellectmoneyMethodList(), array(
                            'onChange' => "$('#preference').val($(this).val())",
                            'separator' => ' ',
                    ))?>
                    </span>
                </div>
                <?php echo CHtml::hiddenField('recipientAmount', $imData['recipientAmount'])?>
                <?php echo CHtml::hiddenField('userField_1', $imData['userField_1'])?>
                <?php echo CHtml::hiddenField('serviceName', $imData['serviceName'])?>
                <?php echo CHtml::hiddenField('orderId', $imData['orderId']);?>
                <?php echo CHtml::hiddenField('eshopId', $imData['eshopId'])?>    
                <?php echo CHtml::hiddenField('recipientCurrency', $imData['recipientCurrency'])?>
                <?php echo CHtml::hiddenField('successUrl', $imData['successUrl'])?>
                <?php echo CHtml::hiddenField('failUrl', $imData['failUrl'])?>
                <?php echo CHtml::hiddenField('user_email', $imData['user_email']);?>
                <?php echo CHtml::hiddenField('userName', $imData['userName']);?>
                <?php echo CHtml::hiddenField('preference', 'bankCard', array(
                    'id' => 'preference',
                ));?>
            </div>
            <div class="fancybox-content-padding-thick">
                <div class="fancybox-buttons no-top-margin">
                    <?php echo CHtml::tag('button', array(
                            'type' => 'submit', 
                            'class' => 'button-square orange' , 
                    ), 'Далее');?>
                    <?php echo CHtml::tag('button', array(
                            'type' => 'button', 
                            'class' => 'button-square aquamarine' , 
                            'onClick' => '$.fancybox.close()'
                    ), 'Отмена');?>
                </div>
            </div>
    <?php echo CHtml::endForm();?>
</div>