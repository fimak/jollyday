<!DOCTYPE html>
<html>
<head>
  <?php echo CHtml::metaTag('text/html; charset=utf-8', null, 'Content-Type')?>
  <?php echo CHtml::linkTag('icon', 'image/ico', '/favicon.ico')?>
  <link rel="stylesheet" type="text/css" href="css/reset.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/themes/main/css/badbrowser.css" />

  <title>Главная страница</title>

</head>

<body>
  <div id="header">
      <div id="header-inner">
          <div id="logo-slogan-wrapper" class="header-top-block">
            <div id="logo"><?php echo CHtml::link(CHtml::image('/images/logo.png', 'logo'), array('/site/index'))?></div>
            <div id="site-slogan">Бесплатный сайт знакомств и реальных свиданий в один клик</div>
          </div>
      </div>
    </div><!-- header -->
    <div id="content">
      <div id="left-column" class="block"><div id="mamont"><img src="<?php echo Yii::app()->baseUrl; ?>/themes/main/img/mamont.png"></div></div>
      <div id="right-column" class="block">
        <div id="first-row"><img src="<?php echo Yii::app()->baseUrl; ?>/themes/main/img/k.png"></div>
        <div id="second-row"><p>Для продолжения работы установите один из современных браузеров на выбор: </p></div>
        <div id="links">
          <div class="browser" id="chrome"><a href="https://www.google.com/intl/ru/chrome/browser/">Google Chrome</a></div>
          <div class="browser" id="mozilla"><a href="http://mozilla-russia.org/products/">Mozilla Firefox</a></div>
          <div class="browser" id="safari"><a href="http://www.apple.com/ru/support/safari/">Safari</a></div>
          <div class="browser" id="opera"><a href="http://ru.opera.com/download/">Opera</a></div>
          <div class="browser" id="ie"><a href="http://windows.microsoft.com/ru-ru/internet-explorer/downloads/ie-9/worldwide-languages">Internet explorer 9</a></div>
        </div>
      </div>
          <?php $this->widget('application.widgets.JCounters')?>
    </div>

</body>
</html>