<?php

$api = dirname(dirname(__FILE__)) . DS . 'api';
$frontend = dirname($api);

Yii::setPathOfAlias('api', $api);

return CMap::mergeArray(
        // наследуемся от main.php
        require('main.php'), 
        array(
                'basePath' => $frontend,

                'name'=>'Jollyday API',

                // Настраиваем пути до основных компонентов нашего api
                'controllerPath' => $api. DS . 'controllers',

                'runtimePath' => $frontend. DS . 'runtime',

                'import'=>array(   
                        'api.models.*',
                        'api.components.*',
                        'api.helpers.*',
                        'application.messages.*',
                ),
                 
                'components' => array(
                        'log' => array(
                                'class'=>'CLogRouter',
                                'routes'=>array(
                                        array(
                                            'class'=>'CFileLogRoute',
                                            'levels' => 'error, warning'
                                        ),
                                ),
                        ),
                        'mail' => array(
                                'class' => 'ext.yii-mail.YiiMail',
                                'transportType' => 'smtp',
                                'transportOptions' => array(
                                        'host' => 'smtp.gmail.com',
                                        'port' => '465',
                                        'encryption'=>'tls',
                                ),
                                'viewPath' => 'mailviews',
                                'logging' => true,
                                'dryRun' => false
                        ),
                        'messages' => array( 
                                'forceTranslation' => true,
                        ),
                        'user'=>array(
                                'class' => 'CWebUser',
                                'allowAutoLogin' => false,
                                'loginUrl' => null,
                        ),
                ),
       )
); 