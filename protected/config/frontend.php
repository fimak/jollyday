<?php
// Конфигурация приложения
return CMap::mergeArray(
        require(dirname(__FILE__). DS . 'main.php'), 
        array(
                'basePath' => dirname(__FILE__) . DS . '..',

                'name'=>'Jollyday',

                'theme' => 'jolly',

                'preload' => array(
                        'maintenance',
                        'badbrowser'
                ),
                'modules'=>array(		
                        'register'=>array(
                                'class'=>'application.modules.register.RegisterModule',                   
                        ),
                        'app'=>array(
                                'class'=>'application.modules.app.AppModule',
                        ),            
                ),
                'theme' => 'main',
            
                'import' => array(
                        'application.widgets.JFaceRibbon.*',
                        'application.widgets.JMainRibbon.*',
                        'application.actions.*',
                        'application.widgets.*',
                        'ext.yii-flash.*',
                        'ext.fancybox2.*',
                        'ext.yii-timeago.*',
                        'ext.image.*',
                ),
            
                // компоненты приложения
                'components'=>array(
                        'user'=>array(
                                'class' => 'JWebUser',
                                'allowAutoLogin' => true,
                                'actionTable' => '_action',
                                'actionDateField' => 'date',
                                'actionIdField' => 'id_user',
                                'loginUrl' => array('/site/index'),
                        ),
                        'errorHandler'=>array(
                                'errorAction' => '/site/error',
                        ),
                        'maintenance' => array(
                                'class' => 'application.components.JMaintenance',
                                'enabledMode' => false
                        ),
                        'clientScript' => array(
                                'class' => 'JClientScript',
                                'packages' => array(
                                        'frontend' => array(
                                                'baseUrl' => '/',
                                                'js' => array(
                                                        'js/jquery.tools.tooltip.min.js',
                                                        'js/jquery.jcarousel.min.js',
                                                        'themes/jolly/js/jolly.js',
                                                ),
                                                'depends' => array(
                                                        'jquery'
                                                ),
                                        ),
                                        'mainpage' => array(
                                                'baseUrl' => '/',
                                                'js' => array(
                                                        'js/jquery.tools.tooltip.min.js',
                                                        'js/jquery.jcarousel.min.js',
                                                        'themes/main/js/main.js',
                                                ),
                                                'depends' => array(
                                                        'jquery',
                                                ),
                                        ),
                                ),
                        ),
                        'badbrowser' => array(
                                'class' => 'JBadbrowser',
                                'enabled' => true,
                                'capRoute' => '/site/badbrowser',
                                'browsers' => array('MSIE 6.0', 'MSIE 7.0','Flock','Orca','K-Meleon','Qtweb','RockMelt','SeaMonkey','SRWare','SlimBrowser')
                        ),
                        'urlManager' => array(
                                'rules' => array(
                                        '' => 'site/index',
                                        'people' => 'site/search',
                                        'login' => 'site/login',
                                        'enter' => 'site/loginform',
                                        'toprated' => 'site/toprated.loadProfiles',
                                        'page/<view:\S+>' =>'site/page',
                                        
                                        'register' => 'register/ajax/form',
                                        'try' => 'register/ajax/intmdregister',
                                        'register/request' => 'register/ajax/request',
                                        'register/confirm' => 'register/ajax/confirm',
                                        'recovery' => 'register/recovery/index',
                                        
                                        'boss' => 'site/boss',
                                        'register/personalies' => 'app/register/personal',
                                        'register/meetingways' => 'app/register/meetingway',
                                        'register/questionary' => 'app/register/questionary',
                                        'register/activation/mail/<code:\S+>/<userid:\S+>'=>'register/activation/mail',
                                    
                                        'profile' => 'app/profile/index',
                                        'messages' => 'app/message/index',
                                        'search' => 'app/search/index',
                                        'settings' => 'app/settings/index',
                                        'news' => 'app/message/news',
                                        'logout' => 'app/profile/logout',
                                        'support' => 'app/support/feedback',
                                        'feedback' => 'site/feedback',
                                        'photo/delete' => 'app/photo/delete',
                                        'photo/uploader' => 'app/photo/uploader',
                                        'account' => '/app/payment/account',
                                        'dialog/<id:\S+>' => 'app/message/dialog',
                                        '<controller>/<action>' => '<controller>/<action>',
                                ),
                        ),
                ),
            
                'onBeginRequest' => function(){
                        // ставим алиас текущей темы - удобно!!!
                        Yii::setPathOfAlias('theme', Yii::app()->theme->basePath);
                        
                        // выставляем временную зону пользователю
                        if(!Yii::app()->user->getState(Yii::app()->localtime->globalTimeZone))
                                Yii::app()->user->setTimezone();
                }
        )             
);
        
        