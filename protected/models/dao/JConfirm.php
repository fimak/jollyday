<?php

/**
 * Класс-хелпер для работы с таблицами для данных, требующих
 * подтверждения
 *
 */
class JConfirm 
{
        /**
         * Метод вставляет запись в таблицу новых емейлов для подтверждения
         * 
         * @param type $userID Id пользователя
         * @param type $email верифицируемый адрес
         */
        public static function newEmail($userID, $email)
        {
            
                $count = Yii::app()->db->createCommand()
                        ->select('COUNT(*)')
                        ->from('new_email')
                        ->where('id_user = :id_user',array(
                                ':id_user' => $userID
                        ))
                        ->queryScalar();
                
                $code = JRandom::md5();
                $date = Yii::app()->localtime->UTCNow;
                // если такой емейл уже есть, то заменяем код и дату
                if($count)
                {
                        $result = Yii::app()->db->createCommand()
                                ->update('new_email', 
                                        array(
                                                'email' => $email,
                                                'code' => $code,
                                                'date' => $date,
                                        ), 
                                        'id_user = :userID', 
                                        array(
                                                'userID' => $userID
                                        )
                                );
                }
                else
                {
                        // иначе просто вставляем запись
                        $result = Yii::app()->db->createCommand()
                                ->insert('new_email', array(
                                        'email' => $email,
                                        'code' => $code,
                                        'date' => $date,
                                        'id_user' => $userID
                        ));
                }                           
               
                if($result)
                {
                        // ставим пользователю флаг, что адрес не подтверждён
                        // и отсылаем письмо
                        Yii::app()->db->createCommand()
                                ->update('user', 
                                        array(
                                                'fl_newmail' => 1,
                                        ), 
                                        'id = :userID', 
                                        array(
                                                'userID' => $userID
                                        )
                                );
                        
                        JMail::activateMail($userID, $code, $email);
                }
                return $result;
        }
        
        public static function isEmailChecked($userID)
        {
                return Yii::app()->db->createCommand()
                        ->select('fl_newmail')
                        ->from('user')
                        ->where('id = :userID AND fl_newmail = 0',array(':userID' => $userID))
                        ->queryRow();
        }
        
        /**
         * Метод сверяет полученный код активации емейла и id пользователя с существующими в 
         * таблице новых емейлов, и если емейл с заданным кодом и id существуют, то 
         * емейл присваивается пользователю, а запись из вспомогательной таблицы удаляется
         * 
         * @param type $code
         * @param type $userID
         * @return boolean
         */
        public static function checkEmail($code, $userID)
        {
                // выбираем данные из таблицы новых емейлов
                $row = Yii::app()->db->createCommand()
                        ->select('code, id_user, email')
                        ->from('new_email')
                        ->where('id_user = :id_user',array(':id_user' => $userID))
                        ->queryRow();

                if(empty($row))
                        return false;
                
                if($code == $row['code'] && $userID == $row['id_user'])
                {
                        // присваиваем пользователю подтвержденный емейл
                       $result = Yii::app()->db->createCommand()
                                ->update('user', array(
                                            'email' => $row['email'],
                                            'fl_newmail' => 0,
                                        ),
                                        'id = :id',
                                        array(
                                            'id' => $userID
                                        )     
                        );
                    
                        // удаляем запись из временной таблицы и убираем флаг изменения емейла у юзера
                        if($result)
                        {
                                Yii::app()->db->createCommand()
                                        ->delete('new_email','id_user = :id_user',array(
                                                'id_user' => $userID
                                        )
                                );
                                
                        }
                        // Пишем сообщение в историю
                        History::log($row['id_user'], History::EVENT_NEWMAIL, $row['email']);
                        
                        return true;
                }

                else
                        return false;
        }
        
        /**
         * Метод вставлят номер телефона и пароль к нему в таблицу новых пользователей.
         * 
         * @param type $phone Номер телефона пользователя
         * @param type $password Пароль пользователя
         * @return string код активации аккаунта
         */
        public static function newUser($phone, $password)
        {
                $row = Yii::app()->db->createCommand()
                        ->select('phone')
                        ->from('new_user')
                        ->where('phone = :phone',array(
                                ':phone' => $phone))
                        ->queryRow();
                
                $date = Yii::app()->localtime->UTCNow;
                
                $code = JRandom::smsRegister();
                
                if($row)
                {
                        $result = Yii::app()->db->createCommand()
                                ->update('new_user', array(
                                                'phone' => $phone,
                                                'code' => $code,
                                                'date' => $date,
                                                'password' => $password,
                                        ),
                                        'phone = :phone',
                                        array(
                                                'phone' => $phone
                                        )
                        );
                }
                else
                {
                        $result = Yii::app()->db->createCommand()
                                ->insert('new_user', array(
                                        'phone' => $phone,
                                        'password' => $password,
                                        'code' => $code,
                                        'date' => $date,
                                ));
                }
                
                if($result)
                        return $code;                                
        }
        
        /**
         * Метод вставляюет номер телефона и пароль к ему в таблицу новых номеров.
         * 
         * @param type $phone Номер телефона пользователя
         * @param type $userID ID пользователя
         */
        public static function newPhone($userID, $phone)
        {
                $row = Yii::app()->db->createCommand()
                        ->select('id_user')
                        ->from('new_phone')
                        ->where('id_user = :id_user',array(
                                ':id_user' => $userID))
                        ->queryRow();
                
                $date = Yii::app()->localtime->UTCNow;
                
                $code = JRandom::smsRegister();
                
                if($row)
                        $result = Yii::app()->db->createCommand()
                                ->update('new_phone', array(
                                                'phone' => $phone,
                                                'code' => $code,
                                                'date' => $date,
                                        ),
                                        'id_user = :id_user',
                                        array(
                                                'id_user' => $userID
                                        )
                        );
                else
                        $result = Yii::app()->db->createCommand()
                                ->insert('new_phone', array(
                                        'id_user' => $userID,
                                        'phone' => $phone,
                                        'code' => $code,
                                        'date' => $date,
                        ));
                
                return $result;               
        }
        
        /**
         * Метод заменяет номер телефона у пользователя на номер пользователя,
         * лежащий в таблице новых номеров
         * 
         * @return string новый номер телефона юзера
         */
        public static function updatePhone($userID)
        {
                $phone = Yii::app()->db->createCommand()
                        ->select('phone')
                        ->from('new_phone')
                        ->where('id_user = :id_user', array('id_user' => $userID))
                        ->queryScalar();
            
                if($phone)
                        $result = Yii::app()->db->createCommand()
                                ->update('user',array(
                                        'phone' => $phone
                                ),
                                'id = :id',
                                array(
                                        'id' => $userID,
                                )
                        );
                
                // удаляем скопированные данные
                if($result)
                        Yii::app()->db->createCommand()
                                ->delete('new_phone', 'id_user = :id_user', array(
                                        'id_user' => $userID
                                )         
                        );
                             
                // Пишем сообщение в историю
                History::log($userID, History::EVENT_NEWPHONE, '+7'.$phone);                
                             
                return $phone;
        }
        
        /**
         * Метод возвращает код, необходимый для подтверждения нового
         * номера телефона (используется в отладочных целях)
         * 
         * @param integer $newPhone номер телефона для подтверждения
         * @return string код подтверждения
         */
        public static function getCodeByNewNumber($newPhone)
        {
                return Yii::app()->db->createCommand()
                        ->select('code')
                        ->from('new_phone')
                        ->where('phone = :newPhone', array('newPhone' => $newPhone))
                        ->queryScalar();
        }
        
        /**
         * Метод удаляет пользователя из таблицы новых пользователей
         * по коду активации
         * 
         * @param type $code код активации пользователя
         */
        public static function deleteNewUserByCode($code)
        {        
                return Yii::app()->db->createCommand()
                        ->delete('new_user', 'code = :code', array(
                                'code' => $code
                ));
        }
        
        /**
         * Метод получает по коду номер и пароль пользователя из 
         * таблицы новых пользователей
         * 
         * @param type $code код активации пользователя
         * @return type массив введенных пользователем при регистрации данных
         */
        public static function getNewUserByCode($code)
        {
                $row = Yii::app()->db->createCommand()
                        ->select('*')
                        ->from('new_user')
                        ->where('code = :code', array('code' => $code))
                        ->queryRow();
                         
                return $row;
        } 
}

?>
