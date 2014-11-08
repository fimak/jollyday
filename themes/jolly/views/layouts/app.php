<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="icon" type="image/icon" href="/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/style.css" />
    <!--[if IE 9]>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/ie9.css" />
    <![endif]-->
    <!--[if IE 8]>
        <script type="text/javascript" src="/themes/jolly/pie/PIE.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/ie8.css" />
    <![endif]-->
    
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <?php Yii::app()->clientScript->registerPackage('frontend'); ?>  
    
    <!--[if IE 8]>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/ie8.js"></script>
    <![endif]-->
<body>
    <?php $this->widget('JMaintenanceWidget', array(
            'enabled' => Yii::app()->settings->get('Maintenance', 'enableNoticeWidget', 0),
            'id' => 'maintenance-alert'
    ))?>
    <?php $this->widget('ext.fancybox2.EFancyBox'); ?>
    <?php $this->widget('application.widgets.JFormStyler.JFormStyler', array(
        'target' => 'select, input:checkbox, input:radio',
        'skin' => 'jolly'
    ));
    ?>
    <?php $this->widget('application.widgets.JAlertWidget', array(
            'userID' => Yii::app()->user->id,
            'deleteAll' => true,
    ))?>
    <div id="main">   
	<div id="header">
            <div id="header-inner">
                <div id="logo"><?php echo CHtml::link(CHtml::image('/images/logo.png', 'logo'), array('/app/profile/index'))?></div>
                <div id="mainmenu">
                    <?php $this->widget('JMainMenu',array(
                            'onlyLogout' => $this->onlyLogoutMenuItem,
                            'items'=>array(
                                    array(
                                            'label'=>'Моя страница',
                                            'url'=>array('/app/profile/index'),
                                    ),
                                    array(
                                            'label'=>'Сообщения', 
                                            'url'=>array('/app/message/index'),
                                            'counter' => array(
                                                    'countAttribute' => 'newMessageCount',
                                                    'container' => 'span',
                                                    'separator' => ' ',
                                                    'htmlOptions' => array(
                                                            'class' => 'new-messages',
                                                            'onClick' => 'window.location.href = '.CJavaScript::encode(J::url('/app/message/index'))
                                                    )
                                            )
                                    ),
                                    array(
                                            'label'=>'Поиск',
                                            'url'=>array('/app/search/index'),
                                    ),
                                    array(
                                            'label'=>'Настройки',
                                            'url'=>array('/app/settings/index'),
                                    ),
                                    array(
                                            'label'=>'Оповещения',
                                            'url'=>array('/app/message/news'),
                                            'counter' => array(
                                                    'countAttribute' => 'newNewsCount',
                                                    'container' => 'span',
                                                    'separator' => ' ',
                                                    'htmlOptions' => array(
                                                            'class' => 'new-messages'
                                                    )
                                            )
                                    ),
                                    array(
                                            'label'=>'Выход',
                                            'url'=>array('/app/profile/logout')
                                    ),
                            ),
                    )); ?>
                </div><!-- mainmenu -->
                    <?php //$this->widget('JSkinPicker');?>
                
            </div>
	</div><!-- header -->

        <div id="content">
          <div id="content-top">
              <?php $this->widget('JFaceRibbon', array(
                        'enabled' => $this->faceribbonEnable,
                        'webUser' => Yii::app()->user,
              ));?>
              
              <?php $this->widget('JBonusWidget', array(
                        'enabled' => $this->bonusWidgetEnable,
                        'paymentPage' => J::url('/app/payment/account'),
              ));?>
          </div>
          <div id="content-inner">
            <?php echo $content; ?>
          </div>
        </div><!-- content -->
        <div id="hfooter"></div>
    </div><!-- main -->
	<div id="footer">
            <div id="footer-inner">
                <div id="copyright">
                    &copy; <?php echo date('Y');?> www.jollyday.ru, все права защищены
                </div>
                <div id="footer-menu">
                    <?php $this->widget('JMainMenu',array(
                            'id' => 'mainmenu-list',
                            'onlyLogout' => $this->onlyLogoutMenuItem,
                            'enableCounters' => false,
                            'items'=>array(
                                    array(
                                            'label' => 'Познакомиться с генеральным директором',
                                            'url' => 'javascript:void(0)',
                                            'linkOptions' => array(
                                                    'class' => 'trLoadOfferForm',
                                                    'data-link' => J::url('/app/offer/loadofferform'),
                                                    'data-user-id' => 2,
                                                    'data-place' => 'profile',
                                                    'data-method-id' => 1,
                                                    'data-boss' => true,
                                            ),
                                            'visible' => !Offer::isUsersInOfferList(Yii::app()->user->id, User::BOSS)
                                    ),
                                    array(
                                            'label'=>'Служба поддержки', 
                                            'url'=>array('/app/support/feedback')
                                    )
                            )
                ));
                ?>
                </div><!-- footer-menu -->
                    <?php $this->widget('application.widgets.JCounters')?>
            </div><!-- wrapper -->
	</div><!-- footer -->
        <div style="display:none;">
            <div id="fancybox-container">
            <!-- space here will be updated by ajax -->
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