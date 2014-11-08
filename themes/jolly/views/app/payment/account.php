<?php $this->pageTitle = Yii::app()->name. ' :: ' . 'Пополнить счёт';?>

<?php Yii::app()->clientScript->registerScript('payment-page', "
    $(document).on('click', '.pay-row-wrapper', function(){
        $('.pay-summ').removeClass('pay-checked');
        $('.pay-summ', this).addClass('pay-checked');

        var amount = $(this).data('amount');
        var money = $(this).data('money');
        var bonus = $(this).data('bonus');
        var total = money + bonus;

        $('#page-payment-form-amount').html(amount);
        $('#page-payment-form-money').html(money);
        $('#page-payment-form-bonus').html(bonus.toString().replace('.' , ','));
        $('#page-payment-form-total').html(total.toString().replace('.' , ','));  
        $('#field-amount').val(amount);   
    });
    
    $('.trAccountPayMethod:first').trigger('click');
", CClientScript::POS_READY)?>


<div id="your-money-wrapper">
      <h1 id="page-header">Пополнить счет</h1>
      <div id="your-money">
          <p id="your-money-content">Ваш счёт: 
                <span id="your-money-current"><?php echo $account?></span>
                <span id="your-money-coins"> <?php echo ($account - floor($account)) != 0 ? 'монеты' :  Yii::t('jolly', 'money', (int)$account); ?></span>
          </p>
      </div>
      <div id="exchange-rate">
        <span id="exchange-course-title">Курс:</span>
        <span id="exchange-course">1 монета = <?php echo JPayment::EXCHANGE_RATE; ?> рублей</span>
      </div>  
</div>
    
    <div id="payment-title">Выберите вариант оплаты:</div>
    <div id="payment-methods">
      <ul id="payment-method-wrapper">
        <li id="method-1" class="payment-method trAccountPayMethod" data-link="<?php echo J::url('/app/payment/loadAccountForm', array('method' => 'mCommerce'))?>">
          <div class="content-inner">
            <p class="payment-title">Мобильный платеж</p>
            <p class="payment-description">МТС, Билайн, Мегафон</p>
            <?php echo CHtml::image(Yii::app()->theme->baseUrl . '/img/payment/mobile.png', '', array(
                    'class' => 'payment-img'
            ))?>
          </div>
        </li>
          <li id="method-2" class="payment-method trAccountPayMethod" data-link="<?php echo J::url('/app/payment/loadAccountForm', array('method' => 'bankCard'))?>">
          <div class="content-inner">
            <p class="payment-title">Банковские карты</p>
            <p class="payment-description">Visa и Mastercard</p>
            <?php echo CHtml::image(Yii::app()->theme->baseUrl . '/img/payment/visa_mastercard.png', '', array(
                    'class' => 'payment-img'
            ))?>
          </div>
        </li>
        <li id="method-4" class="payment-method trAccountPayMethod" data-link="<?php echo J::url('/app/payment/loadAccountForm', array('method' => 'terminals'))?>">
          <div class="content-inner">
            <p class="payment-title">Платежные терминалы</p>
            <p class="payment-description">QIWI, Евросеть, Связной и др.</p>
            <?php echo CHtml::image(Yii::app()->theme->baseUrl . '/img/payment/terminal.png', '', array(
                    'class' => 'payment-img'
            ))?>
          </div>
        </li>
        <li id="method-5" class="payment-method trAccountPayMethod" data-link="<?php echo J::url('/app/payment/loadAccountForm', array('method' => 'iBank'))?>">
          <div class="content-inner">
            <p class="payment-title">Интернет-банк</p>
            <p class="payment-description">&nbsp;</p>
            <?php echo CHtml::image(Yii::app()->theme->baseUrl . '/img/payment/bank.png', '', array(
                    'class' => 'payment-img'
            ))?>
          </div>
        </li>
        <li id="method-3" class="payment-method unactive">
          <div class="content-inner">
            <p class="payment-title">Электронные деньги</p>
            <p class="payment-description">Яндекс деньги и Киви кошелек</p>
            <?php echo CHtml::image(Yii::app()->theme->baseUrl . '/img/payment/virtual_money.png', '', array(
                    'class' => 'payment-img'
            ))?>
          </div>
        </li>
      </ul>
    </div>
       
    <div id="payment-method-account-container">
        <div class="method-inner-left">
          <div class="method-inner-title">На сколько монет Вы хотите пополнить счет?</div>
          <?php foreach(JPayment::getAvailableAmountList() as $key => $item) : ?>
              <div class="pay-row-wrapper" data-amount="<?php echo $key; ?>" data-money="<?php echo $item; ?>" data-bonus="<?php echo $item * $bonusCoefficient; ?>">
                <span class="pay-summ">
                    <?php echo $item; ?> <?php echo Yii::t('jolly', 'money', (int)$item); ?> + 
                </span>
                  <span class="pay-bonus"> 
                      бонус <?php echo JPayment::formatAmount($item * $bonusCoefficient); ?> <?php echo JPayment::formatMoneyWord($item * $bonusCoefficient); ?>
                </span>
                <span class="pay-description">(<?php echo $key; ?> рублей)</span>
              </div>
          <?php endforeach; ?>

        </div>
        <div class="method-inner-right"></div>
    </div>

<div id="spend-ways">
   <div id="spend-ways-title">На что можно потратить?</div>
   <div id="spend-ways-wrapper">
     <table id="spend-ways-table">
         <tr>
           <td style="width: 320px; vertical-align: middle; padding-top: 15px; padding-left: 15px;">
                 <?php echo CHtml::image(Yii::app()->theme->baseUrl . '/img/payment/send-gift-img.png', '', array(
                         'class' => 'payment-img'
                 ))?>
                 <p>Сделать подарок<br /> любому пользователю</p>
           </td>
           <td style="width: 320px; vertical-align: middle;">
                  <?php echo CHtml::image(Yii::app()->theme->baseUrl . '/img/payment/rating-img.png', '', array(
                         'class' => 'payment-img'
                 ))?>
                 <p>Подняться в рейтинге<br />на 1 место</p>
           </td>
           <td style="width: 320px; vertical-align: middle; padding-top: 15px; padding-right: 15px;">
                 <?php echo CHtml::image(Yii::app()->theme->baseUrl . '/img/payment/introduction-SMS-img.png', '', array(
                         'class' => 'payment-img'
                 ))?>
                 <p>Ускорить знакомство<br />при помощи SMS</p>
           </td>
         </tr>
     </table>
   </div>
 </div>