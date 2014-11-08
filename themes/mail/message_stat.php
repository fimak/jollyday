<html>
    <head>
        <title><?php echo $subject; ?></title>
        <meta content = "text/html"  charset = "utf-8" http-equiv = "Content-Type" />
    </head>
    <body style="color: black;">
            <div style="background-image: url(http://jollyday.ru/themes/jolly/img/mail_layer.png); min-height: 59px; width: auto; padding: 17px 0 0 32px; color: #fff">
                <img src="http://jollyday.ru/themes/jolly/img/logo_mail.png"><br/>
            </div>
            <div style="padding-top: 25px; padding-bottom: 30px; color: #000;">
                <b>Здравствуйте, <?php echo $user;?>!</b>
                </div>
               За время Вашего отсутствия на сайте произошли следующие события:<br/>
         
               <ul style=" -webkit-padding-start: 15px;">
               <?php if($new_offers != 0):?>   
                   <li> Вы получили <span style="color: #ff7200;"><b><?php echo Yii::t('jolly', 'new_offer', $new_offers);?></b></span>:</li><br/>
                    <table>
                        <tr>
                            <?php foreach ($offer as $_offer):?>
                                <td>
                                <?php switch(JMeetmethod::getHtmlClass($_offer['id_method'])): 
                                    case "correspondence" : ?>
                                            <div style="background-image: url(http://jollyday.ru/themes/jolly/img/sprite_mm_big.png);
                                                    background-repeat: no-repeat;
                                                    margin: 10px 10px 20px 0px;
                                                    width: 83px;
                                                    height: 60px;

                                            "></div>
                                        <?php break;?>
                                    <?php case "sms" : ?>
                                            <div style="background: url(http://jollyday.ru/themes/jolly/img/sprite_mm_big.png) no-repeat 0 -60px;
                                                    margin: 10px 10px 20px 0px;
                                                    width: 83px;
                                                    height: 60px;
                                                    "></div>
                                        <?php break;?>
                                    <?php case "walking" : ?>
                                            <div style="background: url(http://jollyday.ru/themes/jolly/img/sprite_mm_big.png) no-repeat 0 -120px;
                                                    margin: 10px 10px 20px 0px;
                                                    width: 83px;
                                                    height: 60px;
                                                    "></div>
                                        <?php break;?>
                                    <?php case "coffee" : ?>
                                            <div style="background: url(http://jollyday.ru/themes/jolly/img/sprite_mm_big.png) no-repeat 0 -180px;
                                                    margin: 10px 10px 20px 0px;
                                                    width: 83px;
                                                    height: 60px;
                                                    "></div>
                                        <?php break;?>
                                    <?php case "cinema" : ?>
                                            <div style="background: url(http://jollyday.ru/themes/jolly/img/sprite_mm_big.png) no-repeat 0 -240px;
                                                   margin: 10px 10px 20px 0px;
                                                   width: 83px;
                                                    height: 60px;
                                                    "></div>

                                        <?php break;?>
                                    <?php case "riding" : ?>
                                             <div style="background: url(http://jollyday.ru/themes/jolly/img/sprite_mm_big.png) no-repeat 0 -300px;
                                                    margin: 10px 10px 20px 0px; 
                                                    width: 83px;
                                                    height: 60px;
                                                    "></div>

                                        <?php break;?>
                                    <?php case "dinner" : ?>
                                            <div style="background: url(http://jollyday.ru/themes/jolly/img/sprite_mm_big.png) no-repeat 0 -360px;
                                                    margin: 10px 10px 20px 0px;
                                                    width: 83px;
                                                    height: 60px;
                                                    "></div>

                                        <?php break;?>
                                    <?php case "bowling" : ?>
                                            <div style="background: url(http://jollyday.ru/themes/jolly/img/sprite_mm_big.png) no-repeat 0 -420px;
                                                    margin: 10px 10px 20px 0px;
                                                    width: 83px;
                                                    height: 60px;
                                                    "></div>

                                        <?php break;?>
                                    <?php case "club" : ?>
                                            <div style="background: url(http://jollyday.ru/themes/jolly/img/sprite_mm_big.png) no-repeat 0 -480px;
                                                    margin: 10px 10px 20px 0px;
                                                    width: 83px;
                                                    height: 60px;
                                                    "></div>

                                        <?php break;?>
                                    <?php case "shopping" : ?>
                                              <div style="background: url(http://jollyday.ru/themes/jolly/img/sprite_mm_big.png) no-repeat 0 -540px;
                                                    margin: 10px 10px 20px 0px;
                                                    width: 83px;
                                                    height: 60px;
                                                    "></div>

                                        <?php break;?>
                                    <?php case "skates" : ?>
                                             <div style="background: url(http://jollyday.ru/themes/jolly/img/sprite_mm_big.png) no-repeat 0 -600px;
                                                    margin: 10px 10px 20px 0px;
                                                    width: 83px;
                                                    height: 60px;
                                                    "></div>

                                        <?php break;?>
                                    <?php case "desire" : ?>
                                             <div style="background: url(http://jollyday.ru/themes/jolly/img/sprite_mm_big.png) no-repeat 0 -660px;
                                                    margin: 10px 10px 20px 0px;
                                                    width: 83px;
                                                    height: 60px;
                                                    "></div>

                                        <?php break;?>
                                    <?php case "travel" : ?>
                                             <div style="background: url(http://jollyday.ru/themes/jolly/img/sprite_mm_big.png) no-repeat 0 -720px;
                                                    margin: 10px 10px 20px 0px;
                                                    width: 83px;
                                                    height: 60px;
                                                    "></div>

                                        <?php break;?>
                                    <?php case "extreme" : ?>
                                             <div style="background: url(http://jollyday.ru/themes/jolly/img/sprite_mm_big.png) no-repeat 0 -780px;
                                                    margin: 10px 10px 20px 0px;
                                                    width: 83px;
                                                    height: 60px;
                                                    "></div>

                                        <?php break;?>
                                    <?php case "bathhouse" : ?>
                                             <div style="background: url(http://jollyday.ru/themes/jolly/img/sprite_mm_big.png) no-repeat 0 -840px;
                                                    margin: 10px 10px 20px 0px; 
                                                    width: 83px;
                                                    height: 60px;
                                                    "></div>

                                        <?php break; ?>

                                    <?php endswitch; ?>
                                </td>
                            <?php endforeach;?>
                        </tr>
                    </table>
                    <?php if($new_offers > 5):?>
                        И еще <span style="color: #ff7200;"><b><?php echo Yii::t('jolly', 'new_offer', $new_offers-5);?></b></span>.
                    <?php  endif;?>
                <?php  endif;?>
                 <?php if($new_message != 0):?>
                        <div style="border-top: 1px dotted #939393; padding-top: 10px;">
                            <li>Вам написали <span style="color: #ff7200;"><b><?php echo Yii::t('jolly', 'new_message', $new_message);?></b></span>:</li><br/>
                        <table>
                            <tr>
                                <?php foreach ($message as $_message):?>
                                <td style="text-align: center;">
                                        <?php $src ="http://jollyday.ru/photo/".$_message['id']."/".$_message['photo'];?>
                                        <img src="<?php echo $src?>"><br/>
                                        <div style="color: #1fa2c9; padding: 5px 0 5px 0;">
                                            <?php echo $_message['name'];?>
                                            <?php echo User::getAgeUser($_message['birthday']).', '.$_message['sity']?>
                                        </div>
                                    </td>
                                <?php endforeach;?>
                            </tr>
                        </table>
                        <?php if($new_message > 2):?>
                            И еще <span style="color: #ff7200;"><?php echo Yii::t('jolly', 'new_message', $new_message-2)?></span>.
                       <?php endif; ?> 
                    </div>
                <?php  endif;?>

                <?php if($new_gift != 0):?>
                    <div style="border-top: 1px dotted #939393; padding-top: 10px;">
                        <li>Вам подарили <span style="color: #ff7200;"><b><?php echo Yii::t('jolly', 'new_gift', $new_gift)?></b></span>:</li>
                            <table>
                                <tr>
                                    <?php foreach ($gift as $_gift):?>
                                        <td style="padding: 10px">
                                                <img src="http://jollyday.ru/images/gifts/<?echo $_gift['image'];?>">
                                        </td>
                                    <?php endforeach;?>
                                </tr>
                            </table>
                            <?php if($new_gift > 5):?>
                                И еще <span style="color: #ff7200;"><b><?php echo Yii::t('jolly', 'new_gift', $new_gift-5)?></b></span>.
                            <?php endif;?>
                        </ul>  
                    </div>
                <?php  endif;?>
                <div style="padding-top: 10px; padding-bottom: 20px">
                    Вы можете зайти на сайт прямо сейчас, перейдя по ссылке: <br/>
                    <?php echo CHtml::link('jollyday.ru', 'jollyday.ru',  array('target'=>'blank', 'style'=>'cursor:pointer; color:#1fa2c9; text-decoration: underline;'))?>
                 </div>
                <div style="border-top: 1px solid #939393; padding-top: 10px;">
                    Искренне Ваша,<br/>
                    Команда сайта знакомств jollyday.ru
                </div>
           
    </body>
</html>
