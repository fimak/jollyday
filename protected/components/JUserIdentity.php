<?php
/**
 * Компонент идентификации и аутентификации пользователя
 */
class JUserIdentity extends CUserIdentity
{

    protected $_id;
    
    /**
     * Аутентификация пользователя
     * @return integer Код ошибки
     */
    public function authenticate()
    {
            // Производим стандартную аутентификацию, описанную в руководстве.
            $user = User::model()->find('phone = :phone', array('phone' => $this->username));

            if(($user===null) || (User::hashPassword($user->salt, $this->password) !== $user->password))
            {
                    $this->errorCode = self::ERROR_USERNAME_INVALID;
            } 
            else 
            {           
                    // В качестве идентификатора будем использовать id, а не username,
                    // как это определено по умолчанию. Обязательно нужно переопределить
                    // метод getId(см. ниже).
                    $this->_id = $user->id;
                    $this->username = $user->phone;
                    
                    // Выставляем часовой пояс пользователя
                    Yii::app()->user->setTimezone($user->id_region);
                    
                    User::updateLastVisitDate($user->id);
                    
                    $this->errorCode = self::ERROR_NONE;
            }
            return !$this->errorCode;
    }
 
    /**
     * Метод получения ID текущего пользователя
     * @return integer ID пользователя
     */
    public function getId()
    {
        return $this->_id;
    }
}