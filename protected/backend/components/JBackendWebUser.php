<?php
/**
 * Компонент веб-пользователя
 */
class JBackendWebUser extends CWebUser
{
        /**
         * @var Admin модель администратора
         */
        private $_model = null;

        /**
         * Метод возвращает роль пользователя
         * 
         * @return string Роль пользователя
         */
        public function getRole()
        {
                if($user = $this->getModel())
                        return $user->role;
        }

        /**
         * Метод возвращает модель пользователя
         * 
         * @return Admin модель пользователя
         */        
        private function getModel()
        {
                if (!$this->isGuest && $this->_model === null)
                        $this->_model = Admin::model()->findByPk($this->id, array('select' => 'phone, role, name'));
                
                return $this->_model;
        }
        
       /**
        * Метод возвращает имя пользователя
        * 
        * @return string имя пользователя
        */         
        public function getRealname()
        {
                return $this->getModel() ?  $this->getModel()->name : null; 
        }       
}
?>
