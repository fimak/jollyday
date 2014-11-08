<h2><?php echo CHtml::image("/images/fancybox-icons/up.png", "icon", array('width'=>28,'height'=>28));?>Подняться наверх</h2>
<div class="fancybox-content-thin">
    <div id="rateup-wrapper-top">
        <div class="photo-wrapper">
            <?php echo CHtml::image($photo, '', array(
                        'width' => Photo::SIZE_MEDIUM_X,
                        'height' => Photo::SIZE_MEDIUM_Y));?>
        </div>
        <div id="rate-ribbon"></div>
        <div id="rateup-info">
            Всего <b><?php echo JPayment::OPERATION_RATING; ?> монета</b> - и ваша анкета
            на <b>1-ом месте</b> в результатах поиска.<br />
            <b>ЭКСТРАБОНУС:</b> Ваше фото будет <b>№1</b> в фотоленте пользователей.<br />
            <?php if(Yii::app()->user->getAccount() >= JPayment::COST_RATING) : ?>
                <br />
                Вы уверены, что хотите получить целых <b>2 услуги</b> всего за <b><?php echo JPayment::OPERATION_RATING; ?> монету</b>?
            <?php endif; ?>
        </div>
        <?php if(Yii::app()->user->getAccount() >= JPayment::COST_RATING) : ?>
            <div id="rating-tip">* При согласии с Вашего счета будет списана 1 монета за данные услуги.</div>
        <?php endif; ?>
    </div>
    <div id="rateup-wrapper-bottom">
        <?php if(Yii::app()->user->getAccount() < JPayment::COST_RATING) : ?>
            <div id="rateup-nomoney-hint">
                У вас недостаточно средств на счёте, чтобы подняться в рейтинге.
                Вы можете 
                <?php echo CHtml::link('пополнить счёт прямо сейчас', array('/app/payment/account'), array(
                        'id' => 'nomoney-hint-link'
                ))?> <br />
                или оплатить услугу:
            </div>
        
            <div id="rateup-payment-selector">
                <?php $this->widget('JPaySelector', array(
                        'name' => 'payMethod',
                        'operation' => JPayment::OPERATION_RATING,
                        'data' => array(
                                J::url('payment/mcommerceform', array('op' => JPayment::OPERATION_RATING)) => 'мобильным платежом',
                                J::url('payment/sms', array('op' => JPayment::OPERATION_RATING)) => 'по СМС',
                                J::url('payment/merchant', array('op' => JPayment::OPERATION_RATING)) => 'другим способом'
                        ),
                        'submitButtonID' => 'trIncreaseRating',
                        'defaultSubmitLink' => J::url('profile/rateup'),
                        'submitLinkAttribute' => 'data-link',
                        'isDefaultSelected' => true,
                        'radioButtonListOptions' => array(
                                'separator' => ''
                        ),
                )); ?>
            </div>
        <?php endif;?>
    </div>
    <div class="fancybox-content-padding-thick">
        <div class="fancybox-buttons">
            <?php echo CHtml::tag('button', array(
                    'type' => 'button',
                    'id' => 'trIncreaseRating',
                    'class' => 'button-square orange button-fixed-width',
                    'data-link' => J::url('profile/rateup'),
            ), 'Да');?>
            <?php echo CHtml::tag('button', array(
                    'type' => 'button', 
                    'class' => 'button-square aquamarine button-fixed-width' , 
                    'onClick' => '$.fancybox.close()'), 
            'Нет');?>
        </div>
    </div>
</div>