<?php

define('GLOBAL_CACHE_TIME', 3600);
define('DS', DIRECTORY_SEPARATOR);
define('WEBROOT', dirname(__FILE__) . DS . '..');

$yiic = dirname(__FILE__) . DS . '..' . DS . 'yii' . DS . 'yiic.php';
$config = dirname(__FILE__) . DS . 'config' .DS . 'console.php';

require_once($yiic);
