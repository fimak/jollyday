<?php

class YiiDebugToolbarPanelJollyday extends YiiDebugToolbarPanel
{
        public function getMenuTitle()
        { 
                return 'Jollyday'; 
        }

        public function getMenuSubTitle()
        { 
                return 'Отладка сайта'; 
        }
        
        public function getTitle()
        { 
                return 'Jollyday'; 
        }
        
        public function getSubTitle()
        { 
                return 'Отладка сайта'; 
        }
        
        public function run()
        {
                $this->render('jollyday');
        }
}
?>
