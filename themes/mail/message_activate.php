<html>
    <head>
        <title><?php echo $subject; ?></title>
        <meta content = "text/html"  charset = "utf-8" http-equiv = "Content-Type" />
    </head>
        <body>
            <div style="background-image: url(http://jollyday.ru/themes/jolly/img/mail_layer.png); min-height: 59px; width: auto; padding: 17px 0 0 32px; color: #fff">
                <img src="http://jollyday.ru/themes/jolly/img/logo_mail.png"><br/>
            </div>
            <div style="padding-left: 30px">
                <div style="padding-top: 25px; padding-bottom: 30px; color: #000000;">
                    <b>Здравствуйте, <?php echo $user;?>!</b>
                </div>
                Вы указали данный почтовый ящик в своей анкете на jollyday.ru<br/>
                Для его активации перейдите по ссылке, указанной ниже:<br/>
                
                <?php echo CHtml::link(
                        'http://jollyday.ru'.$this->createUrl('/register/activation/mail', array('code' => $code, 'userid' => $userID)), 
                        'http://jollyday.ru'.$this->createUrl('/register/activation/mail', array('code' => $code, 'userid' => $userID)),
                        array('target'=>'blank', 'style'=>'cursor:pointer; color:#1fa2c9; text-decoration: underline;'))?>
                <br/>
                Либо скопируйте данную ссылку и вставьте ее в адресную строку Вашего браузера.
                <br/><br/>
                <div style="padding-bottom: 60px;">
                    Если письмо пришло к Вам по ошибке, Вам не нужно совершать никаких действий.
                </div>
                <div style="border-top: 1px solid #939393; padding: 25px 0px ;">
                    Искренне Ваша,<br/>
                    Команда сайта знакомств jollyday.ru<br/>
                </div>
                <span style="color: #939393;">Внимание! Это письмо было автоматически отправлено Вам почтовым роботом. Отвечать на него не нужно.</span>
            </div>
         
    </body>
</html>
