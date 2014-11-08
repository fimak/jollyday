<?php

/**
 * Модель для таблицы сообщений
 */
class Metamessage extends CActiveRecord
{
        const TYPE_MESSAGE = 0;
        const TYPE_GIFT = 1;
           
        const STATUS_DELETED = -1;
        const STATUS_UNREAD = 0;
        const STATUS_READ = 1;
            
        public $relatedEntity;
        
             
	/**
	 * Метод возвращает статичную модель определённого AR-класса
         * 
	 * @param string $className имя класса ActiveRecord.
	 * @return Message статичная модель класса
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
		return 'message';
	} 
        
        /**
         * Метод получает последние метасообщения указанного пользователя
         * 
         * @param integer $userID
         * @param integer $limit
         * @return array
         */
        public static function getLastMetaMessages($userID, $limit)
        {
                $criteria = new CDbCriteria;
                
                // выбираем только сообщения с существующими предложениями
                $criteria->select = 't.id_related, t.type';
                $criteria->join .= " JOIN user sender ON sender.id = t.id_sender";
                $criteria->join .= " JOIN user reciever ON reciever.id = t.id_reciever";
                
                $criteria->addCondition("t.id_reciever = {$userID} OR t.id_sender = {$userID}");
                $criteria->addCondition("sender.fl_deleted <> 1 && reciever.fl_deleted <> 1");

                // только неудалённые сообщения
                $criteria->addCondition('t.status >= 0');
                $criteria->addCondition("t.type = " . self::TYPE_MESSAGE);
                
                $criteria->order = "t.paid = 1 AND t.id_reciever = $userID DESC, MAX(t.date) DESC";
                $criteria->group = '
                        CASE t.type
                            WHEN 0 THEN t.id_related
                            ELSE t.id
                        END
                ';
                $criteria->limit = $limit;
                
                // получаем сообщения по критерию
                $metaMessages = self::model()->findAll($criteria);              
 
                return $metaMessages;
        }
        
        
        /**
         * Метод помечает сообщения как прочитанные в диалоге между текущим пользователем
         * и отправителем
         * 
         * @param integer $senderID ID отправителя
         * @param array $messageIds ID сообщений для поментки о прочтении
         */
        public static function markAsRead($senderID, $messageIds = null)
        {
                if(empty($messageIds) && is_array($messageIds))
                        return;
                
                elseif($messageIds === null)
                        Yii::app()->db->createCommand()
                                ->update(
                                        'message',
                                        array(
                                                'status' => self::STATUS_READ
                                        ),
                                        'id_reciever = :recieverID AND id_sender = :senderID AND status = :parStatus', 
                                        array(
                                                'recieverID' => Yii::app()->user->id,
                                                'senderID' => $senderID,
                                                'parStatus' => self::STATUS_UNREAD,
                                        )
                        );
                else
                        Yii::app()->db->createCommand()
                                ->update(
                                        'message',
                                        array(
                                                'status' => self::STATUS_READ
                                        ),
                                        'id_reciever = :recieverID AND id_sender = :senderID AND status = :parStatus AND id IN (:messageIds)', 
                                        array(
                                                'recieverID' => Yii::app()->user->id,
                                                'senderID' => $senderID,
                                                'parStatus' => self::STATUS_UNREAD,
                                                'messageIds' => implode(',', $messageIds),
                                        )
                        );
        }
        
        protected function afterFind()
        {   
                switch ($this->type)
                {
                        case self::TYPE_MESSAGE:
                                $this->relatedEntity = Offer::model()->findByPk($this->id_related);
                                break;
                        case self::TYPE_GIFT:
                                $this->relatedEntity = Gift::model()->findByPk($this->id_related);
                                break;
                        default:
                                break;
                }
        }
}
