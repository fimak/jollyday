<div class="method-inner-title">&nbsp;</div>
<?php echo CHtml::beginForm(Yii::app()->intellectmoney->server, 'post' ,array(
        'id' => 'page-payment-intellectmoney-form'
));?>
    <div class="summ-calculate-wrapper">
      <span class="summ-calculate-title">На счет зачислится:</span><br />
      <span class="summ-calculate-result"><span id="page-payment-form-money"></span> + <span id="page-payment-form-bonus" class="color-blue"></span> = <span id="page-payment-form-total"></span> монет</span>
    </div>
    <div class="summ-for-pay">
      <div class="summ-for-pay-wrapper">
        <p>К оплате</p>
        <p class="summ-for-pay-total"><span id="page-payment-form-amount"></span> рублей</p>
      </div>
            <?php echo CHtml::tag('button', array(
                    'type' => 'submit', 
                    'id' => 'pay-button',
                    'class' => 'big-button'
            ), 'Оплатить');?>         
    </div>
    <?php echo CHtml::hiddenField('recipientAmount', $imData['recipientAmount'], array(
        'id' => 'field-amount'
    ))?>
    <?php echo CHtml::hiddenField('userField_1', $imData['userField_1'])?>
    <?php echo CHtml::hiddenField('serviceName', $imData['serviceName'])?>
    <?php echo CHtml::hiddenField('orderId', $imData['orderId']);?>
    <?php echo CHtml::hiddenField('eshopId', $imData['eshopId'])?>    
    <?php echo CHtml::hiddenField('recipientCurrency', $imData['recipientCurrency'])?>
    <?php echo CHtml::hiddenField('successUrl', $imData['successUrl'])?>
    <?php echo CHtml::hiddenField('failUrl', $imData['failUrl'])?>
    <?php echo CHtml::hiddenField('user_email', $imData['user_email']);?>
    <?php echo CHtml::hiddenField('userName', $imData['userName']);?>
    <?php echo CHtml::hiddenField('preference', 'bankCard');?>
<?php echo CHtml::endForm();?>