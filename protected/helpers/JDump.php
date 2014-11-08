<?php
/**
 * Хелпер для удобного дампа переменных
 */
class JDump extends CVarDumper
{
        /**
         * Дамп переменной без остановки приложения
         * 
         * @param type $var имя переменной
         * @param type $depth глубина вложенности
         * @param type $highlight подсветка
         */
        public static function dump($var,$depth=10,$highlight=true)
        {
                echo self::dumpAsString($var,$depth,$highlight);
        }
        
        /**
         * Дамп переменной с остановкой приложения
         * 
         * @param type $var имя переменной
         * @param type $depth глубина вложенности
         * @param type $highlight подсветка
         */
        public static function ddump($var,$depth=10,$highlight=true)
        {
                echo self::dumpAsString($var,$depth,$highlight);
                Yii::app()->end();
        }
        
        /**
         * Дамп переменной в файл
         * 
         * @param type $var
         * @param type $name
         * @param type $rewritable
         */
        public static function toFile($var, $name = 'dump.txt', $rewritable = false)
        {
                $accessMode = $rewritable ? 'a' : 'w';
                
                $handle = fopen($name, $accessMode);
                
                fprintf($handle, '%s', var_export($var, true));
        }
}

?>
