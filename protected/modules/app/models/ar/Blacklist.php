<?php

/**
 * Модель для чёрного списка
 */

class Blacklist extends CActiveRecord
{
        const STATUS_NO = 0; // пользователи не в ЧС
        const STATUS_OWN = 1; // пользователь в ЧС у текущего пользователя
        const STATUS_OTHER = 2; // текущий пользователь в ЧС у пользователя
           
	/**
	 * Метод возвращает статичную модель определённого AR-класса
         * 
	 * @param string $className имя класса ActiveRecord.
	 * @return Blacklist статичная модель класса
	 */ 
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
         * Метод возвращает имя связанной с данной моделью таблицы базы данных
         * 
	 * @return string имя таблицы
	 */
	public function tableName()
	{
		return 'blacklist';
	}    
    
        /**
         * Метод получает статус записи чёрного списка между двумя пользователями
         * 
         * @param integer $currentUserID ID первого (текущего) пользователя
         * @param integer $checkedUserID ID второго пользователя
         * @return integer статус записи
         */
        public static function getBlacklistStatus($currentUserID, $checkedUserID)
        {
                $row = Yii::app()->db->createCommand()
                            ->select('id_user')
                            ->from('blacklist')
                            ->where('(id_blacklisted = :first AND id_user = :second) OR (id_blacklisted = :second AND id_user = :first)', array(
                                    ':first' => $currentUserID,
                                    ':second' => $checkedUserID))
                            ->queryScalar();
                
                if(empty($row))
                        return self::STATUS_NO;
                elseif($row == $currentUserID)
                        return self::STATUS_OWN; // запись добавил текущий пользователь
                elseif($row == $checkedUserID)
                        return self::STATUS_OTHER; // текущего пользователя добавили
                else
                        return false;
        }

        /**
         * Метод добавляет выбранного пользователя в чёрный список текущего пользователя.
         * Соответственно далее удалаются записи из таблицы предложений
         * 
         * @param type $idBlacklisted ID добавляемого пользователя
         */
        public static function addUserToMyBlackList($idBlacklisted)
        {
                $idUser = Yii::app()->user->id;
                
                // если сам себя или ещё раз, то атата
                if($idUser == $idBlacklisted || self::getBlacklistStatus($idUser, $idBlacklisted))
                        return;
                
                $command = Yii::app()->db->createCommand()
                        ->insert('blacklist', array(
                                'id_user' => $idUser,
                                'id_blacklisted' => $idBlacklisted,
                        ));
                
                // если пользователь занесён в чёрный список удаляем пользователей из
                // таблицы предложений и сообщениям в переписке ставим статус удалено
                if($command)
                {
                        Offer::deleteOfferFromList($idUser, $idBlacklisted);
                        
                        Yii::app()->db->createCommand()
                                ->update(
                                        'message',
                                        array(
                                                'status' => Message::STATUS_DELETED
                                        ), 
                                        '(id_reciever = :id_reciever AND id_sender = :id_sender) OR (id_reciever = :id_sender AND id_sender = :id_reciever)',
                                        array(
                                                'id_sender' => $idUser,
                                                'id_reciever' =>  $idBlacklisted
                                        )
                                );
                }
                
                return $command;
        }
        
        /**
         * Исключает пользователя из чёрного списка текущего пользователя
         * 
         * @param type $idBlacklisted ID пользователя, исключаемого из чёрного списка
         */
        public static function deleteFromMyBlackList($idBlacklisted)
        {
                $idUser = Yii::app()->user->id;
                
                if($idUser == $idBlacklisted)
                        return;
                
                $result = Yii::app()->db->createCommand()
                        ->delete('blacklist',
                                'id_user = :userId AND id_blacklisted = :idBlacklisted',
                                array(
                                        'userId' => $idUser,
                                        'idBlacklisted' => $idBlacklisted
                                )
                        );
                
                return $result;
        }       
}

?>
