<?php

/**
 * Компонент менеджера авторизации на сайте
 */
class JPhpAuthManager extends CPhpAuthManager
{
        /**
         * Инициализация компонента
         */
        public function init()
        {
                // Подключение иерархии ролей
                if($this->authFile===null)
                        $this->authFile=Yii::getPathOfAlias('application.config.auth').'.php';
                
                parent::init();

                // Для гостей у нас и так роль по умолчанию guest.
                if(!Yii::app()->user->isGuest)
                {
                        // Связываем роль, заданную в БД с идентификатором пользователя,
                        // возвращаемым UserIdentity.getId().
                        $this->assign(Yii::app()->user->role, Yii::app()->user->id);
                }
        }
}
?>
