<?php
/**
 * Компонент идентификации и аутентификации пользователя в админке
 */
class JBackendUserIdentity extends CUserIdentity
{

    /**
     * @var mixed ID пользователя
     */
    protected $_id;
    
    /**
     * Аутентификация администратора
     * 
     * @return integer Код ошибки
     */
    public function authenticate()
    {
            $user = Admin::model()->find('role = "admin" AND LOWER(phone)=?', array(strtolower($this->username)));

            if(($user===null) || (Admin::hashPassword($user->salt, $this->password) !== $user->password))
            {
                    $this->errorCode = self::ERROR_USERNAME_INVALID;
            } 
            else 
            {           
                    // В качестве идентификатора будем использовать id, а не username,
                    // как это определено по умолчанию. Обязательно нужно переопределить
                    // метод getId(см. ниже).
                    $this->_id = $user->id;
                    $this->username = $user->name;
                    
                    Admin::updateLastVisitDate($user->id);
                    
                    $this->errorCode = self::ERROR_NONE;
            }
            return !$this->errorCode;
    }
 
    /**
     * Метод получения ID текущего пользователя
     * 
     * @return integer ID текущего пользователя
     */
    public function getId()
    {
        return $this->_id;
    }    
}