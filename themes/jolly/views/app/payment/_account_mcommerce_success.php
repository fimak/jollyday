<h2><?php echo CHtml::image("/images/fancybox-icons/mobile.png", "icon", array('width'=>28,'height'=>28));?>Мобильный платеж</h2>

<div class="fancybox-content-padding-thin">
    <div id="fancybox-textured-wrapper">
        <div id="mcommerce-sucess-subheader">
            <b>Дальнейшие инструкции по совершению мобильного платежа отправлены Вам на мобильный телефон.</b>
        </div>
    </div>    
    <div class="fancybox-content-padding-thick">

    <div id="mcommerce-account-success-wrapper" class="clearfix">        
        <div id="mcommerce-account-success-left">      
            <div class="summ-calculate-wrapper">
              <span class="summ-calculate-title">На счет зачислится:</span><br />
              <span class="summ-calculate-result"><?php echo $money?> + <span class="color-blue"><?php echo $bonus?></span> = <?php echo $summ?></span> <?php echo $moneyWord?>
            </div>

            <div id="mcommerce-order-info">
                <div class="mcommerce-order-info-row">
                    Номер телефона: <span class="mcommerce-order-info-value"><?php echo $phone?></span>
                </div>
                <div class="mcommerce-order-info-row">
                    К оплате: <span class="mcommerce-order-info-value"><?php echo $price ?> <?php echo Yii::t('jolly', 'ruble', $price)?></span>
                </div>                 
            </div>
        </div>
        <?php echo CHtml::image('/images/common/icon_big_phone.png'); ?>
    </div>

    <div class="hint" id="mcommerce-account-success-hint">
        <p>* Сумма платежа указана с учётом всех комиссий, налогов и сборов.</p>
        <p>Никаких дополнительных списаний или подписок на платные услуги не последует.</p>
    </div>
    <div class="fancybox-buttons">
        <?php echo CHtml::tag('button', array(
                'type' => 'button',
                'class' => 'button-square aquamarine',
                'onClick' => '$.fancybox.close()'),
                'Готово'
        );?>
    </div>
    </div>
</div>