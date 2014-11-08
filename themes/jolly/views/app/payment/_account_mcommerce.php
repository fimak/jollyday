<div class="method-inner-title">Оплатить с мобильного телефона</div>
<div id="mcommerce-phone-form-wrapper">
    <div class="mcommerce-phone-form-row"><b>Номер телефона:</b></div>
    <div id="mcommerce-form-phone-text">
        <div id="mcommerce-form-phone-number"><?php echo Yii::app()->format->formatPhone(Yii::app()->user->getPhone(), true, true)?></div>
            <?php echo CHtml::link('Изменить', 'javascript:void(0);', array(
                    'class' => 'trChangeMcommerceNumber'
            ))?>
        </div>
        <div id="mcommerce-form-phone-input-wrapper">
            <?php $this->widget('CMaskedTextField', array(
                    'id' => 'mcommerce-form-phone-input',
                    'name' => 'mcommerce-form-phone-input',
                    'mask' => '+7 (9?99) 999-99-99',
                    'htmlOptions' => array(
                            'placeholder' => '+7 (XXX) XXX-XX-XX',
                            'class' => 'input-medium',
                    ),
            ));?>

            <?php echo CHtml::tag('button', array(
                'type' => 'button',
                'class' => 'trAcceptNewMcommerceNumber button-square aquamarine',
                'disabled' => 'disabled',
            ), 'Готово');?>

            <?php echo CHtml::link('Отмена', 'javascript:void(0);', array(
                    'class' => 'trDeclineNewMcommerceNumber'
            ))?>
        </div>
    <div class="mcommerce-phone-form-row"><b>Внимание!</b> Услуга доступна только для абонентов МТС, Билайн, Мегафон</div>
</div>

<div class="summ-calculate-wrapper">
  <span class="summ-calculate-title">На счет зачислится:</span><br />
  <span class="summ-calculate-result"><span id="page-payment-form-money"></span> + <span id="page-payment-form-bonus" class="color-blue"></span> = <span id="page-payment-form-total"></span> монет</span>
</div>
<div class="summ-for-pay-mcommerce">
  <div class="summ-for-pay-wrapper">
    <p>К оплате</p>
    <p class="summ-for-pay-total"><span id="page-payment-form-amount"></span> рублей</p>
  </div>
        <?php echo CHtml::tag('button', array(
                'type' => 'submit', 
                'id' => 'pay-button',
                'class' => 'big-button trSubmitMcommerceForm',
                'data-link' => J::url('/app/payment/mcommerce', array('op' => JPayment::OPERATION_BALANCE)),
        ), 'Оплатить');?>         
</div>
<div id="mcommerce-order-status" class="flash-message"></div>

<?php echo CHtml::beginForm(false, 'post' ,array(
        'id' => 'page-payment-mcommerce-form'
));?>
    <?php echo CHtml::hiddenField('price', '', array(
        'id' => 'field-amount'
    ))?>
    <?php echo CHtml::hiddenField('phone', '')?>
    <?php echo CHtml::hiddenField('anotherPhone', 0)?> 
<?php echo CHtml::endForm();?>

<?php Yii::app()->clientScript->registerScript('mcommerce-script', "
$(document).on('click', '#mcommerce-phone-form-wrapper .trChangeMcommerceNumber', function(){
    $('#mcommerce-form-phone-text').hide();
    $('#mcommerce-form-phone-input-wrapper').show();
    $('#mcommerce-form-phone-input').focus();
});

$(document).on('click', '#mcommerce-phone-form-wrapper .trDeclineNewMcommerceNumber', function(){
    $('#mcommerce-form-phone-text').show();
    $('#mcommerce-form-phone-input-wrapper').hide();
});

$(document).on('click', '#mcommerce-phone-form-wrapper .trAcceptNewMcommerceNumber', function(){
    var digitCount = $('#mcommerce-form-phone-input').val().replace(/\D+/g,'').length;
    if(digitCount == 11){
        var newPhone = $('#mcommerce-form-phone-input').val();

        $('#mcommerce-form-phone-number').html(newPhone);
        $('#page-payment-mcommerce-form #phone').val(newPhone);
        $('#page-payment-mcommerce-form #anotherPhone').val(1);
        
        $('#mcommerce-form-phone-text').show();
        $('#mcommerce-form-phone-input-wrapper').hide();
    }
});

$(document).on('keyup keypress blur', '#mcommerce-form-phone-input', function(){
    var button = $('#mcommerce-phone-form-wrapper .trAcceptNewMcommerceNumber');
    var digitCount = $(this).val().replace(/\D+/g,'').length;
    
    if(digitCount == 11)
        $(button).removeClass('disabled-login-button').removeAttr('disabled');
    else
        $(button).addClass('disabled-login-button').attr('disabled','disabled');

    return true;
});
", CClientScript::POS_READY)?>
