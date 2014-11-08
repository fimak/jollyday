<!DOCTYPE html>
<html>
<head>
  <?php echo CHtml::metaTag('text/html; charset=utf-8', null, 'Content-Type')?>
  <?php echo CHtml::linkTag('icon', 'image/ico', '/favicon.ico')?>
   <?php echo CHtml::cssFile(Yii::app()->theme->baseUrl.'/css/style.css')?>
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/themes/main/css/error404.css" />

  <title><?php echo CHtml::encode($this->pageTitle); ?></title>

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
      <div id="error404-wrapper">
          <div id="404img"><img src="<?php echo Yii::app()->baseUrl; ?>/themes/main/img/404.png"></div>
          Страница не найдена. Скорее всего, она куда-то пропала, а возможно, её не было никогда.
          <?php echo CHtml::tag('button', array(
                    'id' => 'back-from404',
                    'onClick' => 'window.location.href = "/index.php"',
                    'style' => 'cursor: pointer;',
              ), 'Вернуться на главную', true); ?>
      </div>
      <img id="girl-image" src="<?php echo Yii::app()->baseUrl; ?>/themes/main/img/girl3.png">
        <?php $this->widget('application.widgets.JCounters')?>
    </div>
</body>
</html>