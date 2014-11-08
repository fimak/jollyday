<?php

$backend = dirname(dirname(__FILE__)) . DS . 'backend';
$frontend = dirname($backend);

Yii::setPathOfAlias('backend', $backend);

return CMap::mergeArray(
        // наследуемся от main.php
        require('main.php'), 
        array(
            'basePath' => $frontend,

            'name'=>'Администрирование Jollyday',

            'theme' => 'admin',

            'preload' => array(
                    'bootstrap'
            ),

            // Настраиваем пути до основных компонентов нашего backend
            'controllerPath' => $backend. DS . 'controllers',

            'runtimePath' => $frontend. DS . 'runtime',

            'modulePath' => $backend. DS . 'modules',

            'import'=>array(   
                    'backend.models.*',
                    'backend.components.*',
                    'backend.helpers.*',
            ),

            'modules'=>array(
                    'geography' => array(
                            'class' => 'backend.modules.geography.GeographyModule'
                    ),
                    'audithory' => array(
                            'class' => 'backend.modules.audithory.AudithoryModule'
                    ),
                    'entity' => array(
                            'class' => 'backend.modules.entity.EntityModule'
                    ),
                    'settings' => array(
                            'class' => 'backend.modules.settings.SettingsModule'
                    ),
                    'statistics' => array(
                            'class' => 'backend.modules.statistics.StatisticsModule'
                    ),
                    'gii'=>array(
                            'class' => 'system.gii.GiiModule',
                            'password' => false,
                            'ipFilters' => array('127.0.0.1','::1'),
                            'generatorPaths' => array(
                                    'bootstrap.gii',
                            ),
                    ),
            ),         
            'defaultController'=>'default',

            'components'=>array(
                    'user'=>array(
                            'class' => 'JBackendWebUser',
                            'allowAutoLogin'=>true,
                            'loginUrl' => array('default/login'),                       
                    ), 
                    'errorHandler'=>array(
                            'errorAction'=>'/default/error',
                    ),
                    'bootstrap'=>array(
                            'class'=>'ext.bootstrap.components.Bootstrap',
                            'responsiveCss' => true,
                    ),
                    'urlManager' => array(
                            'rules' => array(
                                    '<controller>/<action>' => '<controller>/<action>',
                            ),
                    ),
            ),

            'onBeginRequest' => function(){
                    // ставим алиас текущей темы - удобно!!!
                    Yii::setPathOfAlias('theme', Yii::app()->theme->basePath);
                    
                    /*if(Yii::app()->user->isGuest)
                            Yii::app()->catchAllRequest = array('/default/login');*/
            }                    
       )
);