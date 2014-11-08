<div id="rateup-wrapper-top">
    <div class="photo-wrapper">
        <?php if($viewData['photo']) : ?>
            <?php echo CHtml::image($viewData['photo'], '', array(
                            'width' => Photo::SIZE_MEDIUM_X,
                            'height' => Photo::SIZE_MEDIUM_Y)
            );?>
        <?php else :?>
            <?php echo CHtml::image(User::getNoPic('medium'));?>
        <?php endif; ?>
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
</div>