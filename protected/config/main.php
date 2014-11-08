<?php

Yii::setPathOfAlias('mailviews', WEBROOT. DS . 'themes' . DS . 'mail');
// Конфигурация приложения
return CMap::mergeArray(
        require('db.php'), 
        array(
                'preload' => array(             
                        'settings',
                        'log',
                ),

                'timeZone' => 'UTC',

                'language' => 'ru',

                'sourceLanguage' => 'ru',

                'localeDataPath' => 'protected' . DS . 'locale',

                //импорт директрорий для автозагрузки классов
                'import'=>array(
                        'application.helpers.*',
                        'application.models.static.*',
                        'application.models.ar.*',
                        'application.models.form.*',
                        'application.models.dao.*',     
                        'application.components.*',
                        'application.validators.*',
                        'application.behaviors.format.*',
                        'application.behaviors.ar.*', 
                        'ext.yii-mail.*',
                        'ext.yii-debug-toolbar.*',
                        'application.widgets.*',
                        'application.widgets.JTopRatedWidget.*',
                ),

                // компоненты приложения
                'components'=>array(			
                        'log'=>array(
                                'class'=>'CLogRouter',
                                'routes'=>array(
                                        array(
                                                'class'=>'ext.yii-debug-toolbar.YiiDebugToolbarRoute',
                                                'levels' => 'error, warning, notice, trace, info',
                                                'ipFilters'=>array('127.0.0.1'),
                                        ),
                                        array(
                                                'class' => 'CFileLogRoute',
                                                'levels' => 'error, warning, notice',
                                                'categories' => '!exception.CHttpException.*',
                                        )
                                ),
                        ),
                        'mail' => array(
                                'class' => 'ext.yii-mail.YiiMail',
                                'transportType' => 'smtp',
                                'transportOptions' => array(
                                        'host' => 'email-smtp.us-east-1.amazonaws.com',
                                        'username' => 'AKIAIL3PFPB4UGDQO3VQ',
                                        'password' => 'AidCLzZmv3h7DccIw7YS0jWj51TnFxVd7w43CcV6KMpv',
                                        'port' => '465',
                                        'encryption'=>'tls',
                                ),
                                'viewPath' => 'mailviews',
                                'logging' => true,
                                'dryRun' => false
                        ),
                        'authManager' => array(
                                'class' => 'JPhpAuthManager',
                                'defaultRoles' => array('guest'),
                        ),
                        'messages' => array( 
                                'forceTranslation' => true,
                        ),
                        'image'=>array(
                                'class' => 'application.extensions.image.CImageComponent',
                                'driver' => 'ImageMagick',
                        ),

                        'format' => array(
                                'class' => 'CFormatter',
                                'booleanFormat' => array(
                                        0 => 'Нет',
                                        1 => 'Да'
                                ),
                                'numberFormat' => array(
                                        'decimals' => 1, 
                                        'decimalSeparator' => ',', 
                                        'thousandSeparator' => ' '
                                ),
                                'behaviors' => array(
                                        'PhoneFormatBehavior' => array(
                                                'class' => 'PhoneFormatBehavior',
                                                'countryCode' => '+7',
                                        ),
                                        'IpFormatBehavior' => array(
                                                'class' => 'IpFormatBehavior',
                                        ),
                                        'TimeagoFormatBehavior' => array(
                                                'class' => 'TimeagoFormatBehavior',
                                        ),
                                        'ExtendedFormatBehavior' => array(
                                                'class' => 'ExtendedFormatBehavior',
                                        )
                                ),
                        ),
                        'cache'=>array(
                                'class'=>'system.caching.CMemCache',
                                'servers' => array(
                                        array(
                                                'host' => '127.0.0.1',
                                                'port' => '11211',
                                                'weight' => 100
                                        ),
                                ),
                                'keyPrefix' => 'jolly-cache'
                        ),
                        'settings'=>array(
                                'class' => 'JSettings',
                                'cacheComponentId' => 'cache',
                                'cacheId' => 'global_website_settings',
                                'cacheTime' => 3600,
                                'tableName' => 'settings',
                                'dbComponentId' => 'db',
                                'createTable' => false,
                                'dbEngine' => 'MyISAM',
                        ),
                        'session' => array(
                                'class' => 'JDbHttpSession',
                                'sessionTableName' => '_session',
                                'connectionID' => 'db',
                                'timeout' => 900,
                                'autoCreateSessionTable' => false,
                        ),
                        'localtime'=>array(
                                'class'=>'JLocalTime',
                                'Locale' => 'ru',
                        ),
                        'assetManager'=>array(
                                'class' => 'CAssetManager',
                                'forceCopy' => YII_DEBUG,
                        ),
                        'curl' => array(
                                'class' => 'application.extensions.curl.Curl',
                        ),
                        'sms16' => array(
                                'class' => 'JSMS16',
                                'originator' => 'Jollyday',
                                'server' => 'http://xml.sms16.ru/xml/',
                                'login' => 'sbochkov',
                                'password' => '1HJf56iFsdv',
                        ),
                        'intellectmoney' => array(
                                'class' => 'JIntellectMoney',
                                'testMode' => false,
                                'server' => 'https://merchant.intellectmoney.ru/ru/',
                                'eshopId' => '450750',
                                'secretKey' => 'jollyday',
                                'recipientCurrency' => 'RUR',
                                'testEmail' => 'jollyday@iaglobus.ru',
                                'successUrl' => 'http://jollyday.ru/',
                                'failUrl' => 'http://jollyday.ru/',
                                'tableLog' => 'pay_intellectmoney'
                        ),
                        'smsOnline' => array(
                                'class' => 'JSMSOnline',
                                'username' => 'jkazantseva',
                                'password' => 'j00l1Y_d@Y',
                                'md5password' => 'gf43Z54gfd1z',
                                'prefix' => '9778'
                        ),
                        'aviso' => array(
                                'class' => 'JAvisoSMS',
                                'username' => 'iaglobus',
                                'secureHash' => '5a11969024da502e1ee31b65af51d522ce0102cd',
                                'serviceId' => '1003',
                                'test' => false,
                        ),
                        'urlManager' => array(
                                'class' => 'CUrlManager',
                                'showScriptName' => false,
                                'urlFormat' => 'path',
                                'rules' => array(
                                    
                                ),
                        ),
                ),

                'params'=>array(
                        'mail' => array(
                                'delivery' => array(
                                        array(
                                                'address' => 'jollydaysite@gmail.com',
                                                'from'=>'jollydaysite@gmail.com',
                                                'password' => 'aw3eft6yji9o;', 
                                                'host' => 'smtp.gmail.com',
                                        ),
                                        array(
                                                'address' => 'jollyday',
                                                'from'=>'jollyday@yandex.ru',
                                                'password' => 'aw3eft6yji9o;',
                                                'host' => 'smtp.yandex.ru',
                                        ),
                                        array(
                                                'address' => 'jollyday@list.ru',
                                                'from'=>'jollyday@list.ru',
                                                'password' => 'aw3eft6yji9o;',
                                                'host' => 'smtp.mail.ru',
                                        ),
                                        array(
                                                'address' => 'jollyday_site@rambler.ru',
                                                'from'=>'jollyday_site@rambler.ru',
                                                'password' => 'ZXCasdqwe123',
                                                'host'=>'mail.rambler.ru',
                                        ),
                                ),
                                'no-reply' => array(
                                        'address' => 'no-reply@jollyday.ru',
                                ),
                                'payment'=> array(
                                        'address'=>'payment@jollyday.ru'
                                ),
                                'intellectmoney-default'=>array(
                                        'address'=> 'intellectmoney@jollyday.ru'
                                ),
                                'support' => array(
                                        'address' => 'support@jollyday.ru', 
                                ),
                                'admin' => array(
                                        'address' => 'admin@jollyday.ru',
                                ),
                        ) 
                ),
        )   
);