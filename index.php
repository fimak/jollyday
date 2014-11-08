<?php

define('GLOBAL_CACHE_TIME', 3600);
define('DS', DIRECTORY_SEPARATOR);
define('WEBROOT', dirname(__FILE__));

// путь до Yii
$yii=dirname(__FILE__) . DS . 'yii' . DS . 'yii.php';

// путь до конфига приложения
$config=dirname(__FILE__). DS .'protected'.DS.'config'.DS.'frontend.php';

// режим отладки
defined('YII_DEBUG') or define('YII_DEBUG',true);

// уровни стека вызовов в логах
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

// подкдючаем загрузчик Yii
require_once($yii);

// создаём экземпляр приложения
Yii::createWebApplication($config)->run();