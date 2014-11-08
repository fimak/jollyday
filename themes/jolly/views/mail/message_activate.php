<html>
    <head>
        <title><?php echo $subject; ?></title>
        <meta content = "text/html"  charset = "utf-8" http-equiv = "Content-Type" />
    </head>
        <body>
            Активируйте по этой ссылке ваш новый ящик на Jollyday:<br/>
           
<a href="<?php echo $this->createAbsoluteUrl('/register/activation/mail', array('code' => $code, 'userid' => $userID)); ?>">
        <?php echo $this->createAbsoluteUrl('/register/activation/mail', array('code' => $code, 'userid' => $userID)); ?>
</a>            
    </body>
</html>
