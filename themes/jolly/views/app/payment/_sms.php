<?php if($operation == JPayment::OPERATION_GIFT) : ?>
    <h2><?php echo CHtml::image("/images/fancybox-icons/payment.png", "icon", array('width'=>28,'height'=>28));?>Оплатить подарок</h2>
<?php elseif($operation == JPayment::OPERATION_RATING):?>
    <h2><?php echo CHtml::image("/images/fancybox-icons/payment.png", "icon", array('width'=>28,'height'=>28));?>Подняться в рейтинге</h2>
<?php elseif($operation == JPayment::OPERATION_OFFERNOTICE):?>
    <h2><?php echo CHtml::image("/images/fancybox-icons/payment.png", "icon", array('width'=>28,'height'=>28));?>Оплата уведомления</h2>
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
    <?php elseif($operation == JPayment::OPERATION_OFFERNOTICE):?>
        <?php $this->renderPartial('_pay_top_offernotice', array(
            'viewData' => $viewData
        ));?>
    <?php endif;?>
    
    <div id="fancybox-payment-form">
        <div id="fancybox-payment-form-title">Оплатить с помощью СМС</div>
        <div class="fancybox-payment-form-row">
                Ваш оператор:
                <?php $this->widget('application.widgets.JSmsOperatorPicker', array(
                        'name' => 'input-operator',
                        'tariffList' => $tariffs,
                        'amountElementId' => 'sms-cost',
                        'operator' => $operator
                ))?>
                 <span id="fancybox-payment-form-to-pay">К оплате: <span id="sms-cost"></span> руб.</span>
        </div>
        <div class="fancybox-payment-form-row highlight">
                Отправьте SMS с кодом:
                <span class="fancybox-payment-form-code"><?php echo Yii::app()->smsOnline->prefix?><?php echo $code;?></span>
                На короткий номер:
                <span class="fancybox-payment-form-code"><?php echo $shortNumber;?></span>
        </div>
        <div class="fancybox-payment-form-row footnote">
                * В стоимость СМС-сообщения включены все налоги и сборы
        </div>
    </div>

    <div class="fancybox-content-padding-thick">
        <div class="fancybox-buttons no-top-margin">
            <?php echo CHtml::tag('button', array(
                    'type' => 'button', 
                    'class' => 'button-square orange' , 
                    'onClick' => 'window.location.reload(true)'
            ), 'Готово');?>
        </div>
    </div>
</div>
