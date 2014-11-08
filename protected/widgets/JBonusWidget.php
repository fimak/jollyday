<?php

/**
 * Класс виджета бонусной программы
 */
class JBonusWidget extends CWidget
{
        public $enabled;
        
        public $paymentPage;
    
        private $_bonusData;
        
        public function init() 
        {
                parent::init();
                
                $this->_bonusData = Yii::app()->user->getBonusData();
                
                if(!$this->_bonusData['fl_bonus_available'])
                        $this->enabled = false;
        }

        public function run()
        {
                if($this->enabled)
                        $this->render('theme.views.widgets.jbonuswidget.jbonuswidget', array(
                                'secondsLeft' => $this->_bonusData['seconds_left'],
                                'counter' => $this->_bonusData['counter'],
                                'paymentPage' => CJavaScript::encode($this->paymentPage),
                        ));
        }
}

?>
