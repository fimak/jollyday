<?php

/**
 * Команда очистки кеша
 */
class ClearcacheCommand extends CConsoleCommand
{
        public function actionIndex()
        { 
                Yii::app()->cache->flush();
                echo "\n\n]  Cache flushed\n";                     
                return 1;
        }
}

?>
