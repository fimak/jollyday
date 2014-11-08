<html>
    <head>
        <title><?php echo $subject ?></title>
        <meta content = "text/html"  charset = "utf-8" http-equiv = "Content-Type" />
    </head>
        <body>
            <div style="background-image: url(http://jollyday.ru/themes/jolly/img/mail_layer.png); min-height: 59px; width: auto; padding: 17px 0 0 32px; color: #fff">
                <img src="http://jollyday.ru/themes/jolly/img/logo_mail.png"><br/>
            </div>
           <div style="padding-left: 30px">
                <div style="padding-top: 25px; padding-bottom: 5px; color: #000000;">
                    <b>Здравствуйте, <?php echo  iconv('CP1251','UTF-8',$user);?>!</b>
                </div> 
               
                 Вы успешно пополнили баланс личного счета на сайте jollyday.ru<br/>
                 
                 Сумма операции: <?php echo $amount.' '.Yii::t('jolly', 'ruble', $amount)?> (на Ваш счет зачислено <?php echo $coin." ".Yii::t('jolly', 'money', $coin)?> + бонус)<br/>
                 
                 Номер операции: <?php echo $orderId; ?><br/>
                 <div style="padding-bottom: 60px;">
                    Дата проведения операции <?echo $date?>
                </div>
                 
                <div style="border-top: 1px solid #939393; padding: 25px 0px ;">
                    Искренне Ваша,<br/>
                    Команда сайта знакомств jollyday.ru 
                </div>
                 
                 <span style="color: #939393;">Внимание! Это письмо было автоматически отправлено Вам почтовым роботом. Отвечать на него не нужно.</span>
            </div>
        </body>
</html>
