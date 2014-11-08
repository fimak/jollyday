<?php

return CMap::mergeArray(
        // получаем компонент соединения с БД
        require('main.php'), 
        array(
                'basePath' => dirname(__FILE__) . DS . '..',

                'name' => 'Jollyday Console',

                'localeDataPath' => '..' . DS .'protected' . DS . 'locale',
            
                'components'=>array(
                        'file'=>array(
                                'class'=>'ext.yii-cfile.CFile',
                        ),
                        'zip' => array(
                                'class'=>'ext.zip.EZip'
                        ),
                ),
            
                'commandMap'=>array(
                        'migrate'=>array(
                                'class'=>'system.cli.commands.MigrateCommand',
                                'migrationTable'=>'_migration',
                        ),
                ), 
        )
); 