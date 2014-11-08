<?php

return array(
        'components' => array(
                'db' => array(
                        'connectionString' => 'mysql:host=127.0.0.1;dbname=jollyday',
                        'emulatePrepare' => true,
                        'username' => 'root',
                        'password' => '',
                        'charset' => 'utf8',
                        'enableParamLogging' => YII_DEBUG,
                        'enableProfiling' => YII_DEBUG,
                        'initSQLs' => array("set time_zone='+00:00';"),
                        'schemaCachingDuration' => GLOBAL_CACHE_TIME,
                ),
        )
);