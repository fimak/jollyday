<?php

/**
 * Команда для работы для работы с SMSOnline
 *
 */
class SmsonlineCommand extends CConsoleCommand
{     
        /**
         * Команда выгрузки
         * 
         * @param string $subject выгрузка 'costs' - цены, 'prefixes' - префиксы стран
         */
        public function actionImport($subject)
        {
                echo "\n"; 
            
                if(!in_array($subject, array('prefixes', 'costs')))
                        echo "\n]  You should import 'costs' or 'prefixes'";
                
                switch($subject)
                {
                        case 'costs' : 
                                if(Yii::app()->smsOnline->importCostInfo())
                                        echo "\n]  Costs table succesfully imported";
                                else
                                        echo "\n]  Costs table import error";
                                break;
                            
                        default: 
                                break;
                }
                
                echo "\n"; 
                
                return 1;
        }
}

?>
