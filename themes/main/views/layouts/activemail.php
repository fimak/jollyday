<!DOCTYPE html>
<html>
<head>
  <?php echo CHtml::metaTag('text/html; charset=utf-8', null, 'Content-Type')?>
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/themes/main/css/mailactive.css" />
  <?php echo CHtml::linkTag('icon', 'image/ico', '/favicon.ico')?>
  <title>Jollyday :: Электронная почта активирована</title>

</head>

<body>
  <div id="main">
    <div id="header">
      <div id="header-inner">
        <div id="header-top">
          <div id="logo-slogan-wrapper" class="header-top-block">
                <div id="logo">
                    <a href="<?php echo $this->createUrl('/site/index')?>">
                        <?php echo CHtml::link(CHtml::image('/images/logo.png', 'logo'), array('/site/index'))?>
                    </a>
                </div>
                <div id="site-slogan">Бесплатный сайт знакомств и реальных свиданий в один клик</div>
          </div>

        <?php $this->widget('JLoginWidget', array(
            'formModel' => new LoginForm,
            'submitButtonSelector' =>'#trSubmitLoginForm'
        )); ?>
        </div>

      </div>
    </div>
    <div id="content">

      <div id="content-inner">
          <?php echo $content; ?>
      </div>
    </div>
      <div id="footer">
            <div id="footer-inner">
              <div id="copyright" class="footer-block">
                  &copy; 2013 jollyday.ru. Все права защищены.
              </div>
              <div id="footer-menu" class="footer-block">
                <?php $this->widget('application.widgets.JCounters')?>
              </div>
            </div><!-- footer-inner -->
	</div><!-- footer -->
    <div style="display:none;">
        <div id="fancybox-container">
        <!-- space here will be updated by ajax -->
        </div>
    </div>
</body>
</html>