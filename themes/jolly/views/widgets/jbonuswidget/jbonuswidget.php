<div id="bonus"<?php echo $secondsLeft < 60 * 60 * 2 ? ' class="bonus-attention"' : ''?>>
  <div id="bonus-info-wrapper">
    <p id="bonus-title">
      <?php if($counter < 500) : ?>
      Акция для первых 500 пользователей в регионе! Ограниченное предложение!
      <?php else : ?>
      Акция! Супер-бонус за регистрацию на сайте! Действителен только 24 часа!
      <?php endif; ?>
    </p>
    <p id="bonus-content-description">
      Пополняя счет в течении 1 суток с момента регистрации, Вы получаете <span id="bonus-orange-text">бонус <?php echo $counter < 500 ? '4' : '2'?>00%</span> с каждого платежа!
    </p>
  </div>
  <div id="bonus-timer-wrapper">
    <div id="bonus-timer-watch">
        <div id="bonus-timer-title">До конца акции осталось:</div>
        <div id="bonus-timer-time"></div>
    </div>
  </div>
</div>

<?php $this->widget('application.widgets.JCountdown.JCountdown', array(
        'direction' => 'until',
        'date' => $secondsLeft,
        'targetElement' => '#bonus-timer-time',
        'config' => array(
                'compact' => true,
                'format' => 'HMS',
                'onExpiry' => "js:function(){
                    $('#bonus').fadeOut('fast');
                }",
                'onTick' => "js:function(periods){
                        var secondsLeft = $.countdown.periodsToSeconds(periods);
                        
                        if(secondsLeft <= (60 * 20)){
                            if(secondsLeft % 2 == 1)
                                $('#bonus').removeClass('bonus-attention');
                            else
                                $('#bonus').addClass('bonus-attention');
                        }
                        else if(secondsLeft <= (60 * 60 * 2)){
                            $('#bonus').addClass('bonus-attention');
                        }
                }",
        ),
))?>

<?php Yii::app()->clientScript->registerScript('bonus-widget-init',"
    $('#bonus').click(function(){
        window.location.href = $paymentPage
    })
", CClientScript::POS_READY)?>
