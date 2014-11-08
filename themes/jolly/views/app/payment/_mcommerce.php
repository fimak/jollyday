<?php if($operation == JPayment::OPERATION_GIFT) : ?>
    <h2><?php echo CHtml::image("/images/fancybox-icons/payment.png", "icon", array('width'=>28,'height'=>28));?>Оплатить подарок</h2>
<?php elseif($operation == JPayment::OPERATION_RATING):?>
    <h2><?php echo CHtml::image("/images/fancybox-icons/up.png", "icon", array('width'=>28,'height'=>28));?>Подняться в рейтинге</h2>
<?php endif;?>
    
<div class="fancybox-content-padding-thin">
        <?php if($operation == JPayment::OPERATION_GIFT) : ?>
            <?php $this->renderPartial('_pay_top_gift', array(
                'viewData' => $viewData
            ));?>
        <?php elseif($operation == JPayment::OPERATION_RATING):?>
            <?php $this->renderPartial('_pay_top_rateup', array(
                'viewData' => $viewData
            ));?>
        <?php endif;?>
    
        <?php echo CHtml::beginForm(false, 'post' ,array(
                'id' => 'page-payment-mcommerce-form'
        ));?>
    
            <?php if($operation == JPayment::OPERATION_GIFT) : ?>
                <?php echo CHtml::hiddenField('id_reciever', $viewData['form']['id_reciever']); ?>
                <?php echo CHtml::hiddenField('postcard', $viewData['form']['postcard']); ?>
                <?php echo CHtml::hiddenField('id_gift', $viewData['form']['id_gift']); ?>
                <?php echo CHtml::hiddenField('is_private', $viewData['form']['is_private']); ?>
            <?php endif;?>
    
    
            <?php echo CHtml::hiddenField('phone', '')?>
            <?php echo CHtml::hiddenField('anotherPhone', 0)?> 
        <?php echo CHtml::endForm();?>
    
        <div id="modal-mcommerce-wrapper">
            <div id="modal-mcommerce-header">
                Оплатить с мобильного телефона:
            </div>
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
            </div>
            <div id="modal-mcommerce-attention"><b>Внимание!</b> Услуга доступна только для абонентов МТС, Билайн, Мегафон</div>
            <div id="modal-mcommerce-paybutton-wapper">
                <?php echo CHtml::tag('button', array(
                        'type' => 'submit', 
                        'class' => 'big-orange-button trSubmitMcommerceForm',
                        'data-link' => J::url('/app/payment/mcommerce', array('op' => $operation)),
                ), 'Оплатить');?>
            </div>
            <div id="mcommerce-order-status" class="flash-message"></div>
        </div>
        <div class="fancybox-content-padding-thick">
            <div class="fancybox-buttons">
                <?php echo CHtml::tag('button', array(
                        'type' => 'button', 
                        'class' => 'button-square aquamarine' , 
                        'onClick' => '$.fancybox.close()'
                ), 'Отмена');?>
            </div>
        </div>

</div>
    
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