<!DOCTYPE html>
<html>
<head>
    <?php echo CHtml::metaTag('text/html; charset=utf-8', null, 'Content-Type')?>
    <?php echo CHtml::cssFile(Yii::app()->theme->baseUrl.'/css/style.css')?>
    <?php echo CHtml::linkTag('icon', 'image/ico', '/favicon.ico')?>

    <!--[if IE 9]>
          <?php echo CHtml::cssFile(Yii::app()->theme->baseUrl.'/css/ie9.css')?>
    <![endif]-->
    <!--[if IE 8]>
        <?php echo CHtml::cssFile(Yii::app()->theme->baseUrl.'/css/ie8.css')?>
    <![endif]-->

    <?php echo CHtml::tag('title', array(), $this->pageTitle)?>
    <?php Yii::app()->clientScript->registerPackage('mainpage'); ?>     
</head>

<body>
    <?php $this->widget('JMaintenanceWidget', array(
            'enabled' => Yii::app()->settings->get('Maintenance', 'enableNoticeWidget', 0),
            'id' => 'maintenance-alert'
    ))?>
    <?php $this->widget('ext.fancybox2.EFancyBox'); ?>
    <?php $this->widget('application.widgets.JFormStyler.JFormStyler', array(
            'target' => '#search-form select, #support-form select',
            'skin' => 'main'
    ));?>
  <div id="main">
    <div id="header">
      <div id="header-inner">
        <div id="header-top">
          <div id="logo-slogan-wrapper" class="header-top-block">
                <div id="logo">
                    <?php echo CHtml::link(CHtml::image('/images/logo.png', 'Jollyday - знакомства в один клик. Бесплатный сайт знакомств и реальных свиданий.'), array('/site/index'))?>
                </div>
                <div id="site-slogan">Бесплатный сайт знакомств и реальных свиданий в один клик</div>
          </div>
          <div id="register-button-wrapper" class="header-top-block">
              <?php echo CHtml::tag('button', array(
                    'class' => 'trLoadRegisterForm',
                    'data-link' => J::url('register/ajax/form'),
              ), 'Бесплатная регистрация', true); ?>
          </div>
        <?php $this->widget('JLoginWidget', array(
            'formModel' => new LoginForm,
            'submitButtonSelector' =>'#trSubmitLoginForm'
        )); ?>
        </div>
      </div>
    </div><!-- header -->

    <div id="content">
      <div id="content-top">
        <div id="content-top-inner">
          <div id="filter-wrapper" class="clearfix">
              <?php $this->widget('JSearchWidget', array(
                    'formModel' => new SearchForm,
              )); ?>
          </div>
        </div>
      </div>
      <div id="content-inner">
          <?php echo $content; ?>
      </div>
      <?php $this->widget('JTopRatedWidget', array(
            'enable' => $this->enableTopRated,
      ))?>  
    </div>
    <div id="hfooter"></div>
  </div>
    <div id="footer">
        <div id="footer-inner">
          <div id="footer-menu" class="footer-block">
            <?php $this->widget('zii.widgets.CMenu', array(
                    'id' => 'footer-menu-list',
                    'items' => array(
                        array(
                                'label' => 'Познакомиться с генеральным директором',
                                'url' => 'javascript:void(0)',
                                'linkOptions' => array(
                                        'class' => 'trIntmdRegister',
                                        'data-link' => J::url('/register/ajax/intmdregister', array('id' => User::BOSS)),
                                )
                        ),
                        array(
                                'label'=>'Как написать сообщение?',
                                'url'=>array('/site/page', 'view' => 'howto'),
                        ),
                        array(
                                'label'=>'Уникальная система поиска',
                                'url'=>array('/site/page', 'view' => 'search'),
                        ),
                        array(
                                'label'=>'15 вариантов знакомства?',
                                'url'=>array('/site/page', 'view' => 'variants'),
                        ),
                        array(
                                'label'=>'Служба поддержки',
                                'url'=>array('/site/feedback'),
                        ),
                    )
            ))?>
          <?php $this->widget('application.widgets.JCounters')?>
          </div>
          <div id="footer-description">
              Как назначить свидание в один клик? На jollyday.ru - легко! Общение, флирт, свидания и многое другое стало в 10 раз быстрее. <br />
              Поиск мужчин и девушек с фотографиями и перепиской теперь на одной странице. 
          </div>
        </div>
    </div>
    <div style="display:none;">
        <div id="fancybox-container">

        </div>
    </div>
    <?php $this->widget('JScrollTop', array(
            'containerID' => 'scrolltop',
            'scrollDistance' => 200,
            'scrollSpeed' => 0,
            'linkText' => 'Наверх',
    ));?>
</body>
</html>
