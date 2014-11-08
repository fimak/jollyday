<?php

/**
 * This is the model class for table "offer".
 *
 * The followings are the available columns in table 'offer':
 * @property integer $id
 * @property integer $id_sender
 * @property integer $id_reciever
 * @property integer $id_method
 * @property integer $status
 * @property string $date
 */
class Offer extends CActiveRecord
{
        const NOT_ACCEPTED = 0;
        const ACCEPTED = 1;
    
        public $meetmethod;
        
        // собеседник
        public $interlocutor;
          
	/**
	 * Метод возвращает статичную модель определённого AR-класса
         * 
	 * @param string $className имя класса ActiveRecord.
	 * @return Offer статичная модель класса
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
		return 'offer';
	}
        
        public function relations()
        {
                return array(                  
                        'lastMessages' => array(self::HAS_MANY, 'Message', 'id_related',
                                'order' => 'date DESC',
                                'limit' => 3,
                                'condition' => 'lastMessages.status >= 0'
                        ),
                        'newMessagesCount' => array(self::STAT, 'Message', 'id_related',
                                'condition' => 'status = 0 AND id_reciever = :recieverID',
                                'params' => array(
                                        'recieverID' => Yii::app()->user->id
                                ),       
                        ),
                );
        }
        
        /**
         * Метод возвращает список причин для игнорирования
         * 
         * @return type
         */
        public static function getIgnoreReasonsList()
        {
                return array(
                        0 => 'Не хочу общаться',
                        1 => 'Спам'
                );
        }
        
        /**
         * Метод проверряет, есть ли между двумя пользователями предложение
         * 
         * @param type $senderID ID первого пользователя (инициатор предложения)
         * @param type $recieverID ID второго пользователя (объект предложения)
         * 
         * @return type false, если нету такого предложения, иначе ID способа знакомства
         */
        public static function isUsersInOfferList($senderID, $recieverID)
        {         
                $row = Yii::app()->db->createCommand()
                        ->select('COUNT(*), id_method')
                        ->from('offer')
                        ->where('(id_reciever = :id_reciever AND id_sender = :id_sender) OR (id_reciever = :id_sender AND id_sender = :id_reciever)', array(
                                ':id_reciever' => $recieverID,
                                ':id_sender' => $senderID))
                        ->queryRow();
                
                $row = array_values($row);
                
                return $row[0] > 0 ? $row[1] : false;                                
        }
        
        /**
         * Метод создаёт предложение между двумя пользователями
         * Добавляются каждый к каждому, создаются две записи, согласно ТЗ:
         *  - Пользователь, которому я отправил предложение
         *  - Пользователь, который мне отправил предложение
         *  - Пользователь, с которым ведется диалог (переписка, обмен телефонами и т.д.)
         * 
         * @param int $senderID ID первого пользователя (инициатор предложения)
         * @param int $recieverID ID второго пользователя (объект предложения)
         * @param int $methodID ID способа знакомства
         * @param boolean $paid оплачено ли уведомление о предложении
         * @return boolean Результат действия
         */
        public static function addUsersToOfferList($senderID, $recieverID, $methodID, $paid = false)
        {        
                $date = Yii::app()->localtime->getUTCNow();
                
                $offerData = self::getOfferData($senderID, $recieverID);
                
                
                
                // если связка в таблице уже существует, то апдейтим способ знакомства,
                // иначе добавляем запись        
                if(!$offerData)
                {
                        return Yii::app()->db->createCommand()->insert('offer', array(
                                'id_sender' => $senderID,
                                'id_reciever' => $recieverID,
                                'id_method' => $methodID,
                                'status' => 0,
                                'date_offer' => $date,                                
                        ));
                }
                elseif($offerData['id_sender'] != $senderID)
                {
                        $result = Yii::app()->db->createCommand()->update('offer', array(
                                'id_sender' => $senderID,
                                'id_reciever' => $recieverID,
                                'id_method' => $methodID,
                                'date_offer' => $date,                                     
                        ),
                        '(id_sender = :senderID AND id_reciever = :recieverID) OR (id_sender = :recieverID AND id_reciever = :senderID)',
                        array(
                                'senderID' => $senderID,
                                'recieverID' => $recieverID
                        ));
                        
                        if($result)
                                self::resetPaid($offerData['id']);
                        
                        return true;
                }
                else
                        return false;
        }
        
        /**
         * Действие удаляет запиь из таблицы предложений познакомиться
         *  
         * @param type $senderID ID первого пользователя
         * @param type $recieverID ID второго пользователя
         */
        public static function deleteOfferFromList($senderID, $recieverID)
        {               
                Yii::app()->db->createCommand()->delete('offer',
                        '(id_sender = :id_sender AND id_reciever = :id_reciever) OR (id_sender = :id_reciever AND id_reciever = :id_sender)',
                        array(
                                'id_sender' => $senderID,
                                'id_reciever' => $recieverID
                        )
                );
        }
        
        
        /**
         * Метод получает id знакомства между пользователями
         * 
         * @param type $senderID ID первого пользователя (инициатор предложения)
         * @param type $recieverIDr ID второго пользователя (объект предложения)
         * 
         * Порядок аргументов неважен.
         * 
         * @return type false, если нету такого предложения, иначе ID способа знакомства
         */
        public static function getOfferId($senderID, $recieverID)
        {         
                $row = Yii::app()->db->createCommand()
                            ->select('id')
                            ->from('offer')
                            ->where('(id_reciever = :id_reciever AND id_sender = :id_sender) OR (id_reciever = :id_sender AND id_sender = :id_reciever)', array(
                                    ':id_reciever' => $recieverID,
                                    ':id_sender' => $senderID))
                            ->queryScalar();
                               
                return $row;                                
        }
        
        /**
         * Метод ставит пометку о подтверждении предложения
         * 
         * @param string $offerID ID предложения
         * @return boolean результат операции
         */
        public static function accept($offerID)
        {
                $result = Yii::app()->db->createCommand()
                        ->update('offer',
                                array(
                                        'status' => Offer::ACCEPTED
                                ), 
                                'id = :offerID',
                                array(
                                        'offerID' => $offerID
                                ));
                
                if($result)
                        self::resetPaid($offerID);
                
                return true;
        }

        /**
         * Метод получает данные о предложении по ID отправителя и получателя.
         * Порядок ID пользователей в аргументах не важен.
         * 
         * @param integer $offerID ID предложения
         * @return array массив с данными предложения или 0, если предложения не существует
         */
        public static function getOfferData($senderID, $recieverID)
        {
                $data = Yii::app()->db->createCommand()
                        ->select('*')
                        ->from('offer')
                        ->where('(id_reciever = :recieverID AND id_sender = :senderID) OR (id_reciever = :senderID AND id_sender = :recieverID)', array(
                            'senderID' => $senderID,
                            'recieverID' => $recieverID))
                        ->queryRow();
                
                return empty($data) ? false : $data;
        }
               
        /**
         * Метод ставит отметку об оплате уведомления
         * 
         * @param integer $offerID ID предложения
         */
        public static function setPaidNotice($offerID)
        {
                $lastMessageId = Yii::app()->db->createCommand()
                        ->select('id')
                        ->from('message')
                        ->where('id_related = :offerId AND type = :messageType',array(
                                'offerId' => $offerID,
                                'messageType' => Metamessage::TYPE_MESSAGE,
                        ))
                        ->order('date DESC')
                        ->limit(1)
                        ->queryScalar();
            
            
                return Yii::app()->db->createCommand()
                        ->update('message', array(
                                'paid' => 1
                        ), 
                        'id = :lastMessageId', 
                        array(
                                'lastMessageId' => $lastMessageId
                        ));
        }
        
        public static function resetPaid($offerID)
        {
                return Yii::app()->db->createCommand()
                        ->update('message', array(
                                'paid' => 0
                        ), 
                        'paid = 1 AND id_related = :offerId AND type = :messageType',
                        array(
                                'offerId' => $offerID,
                                'messageType' => Metamessage::TYPE_MESSAGE,
                        ));
        }

        /**
         * Событие, происходящее после поиска с помощью find-методов модели
         */
        public function afterFind()
        {
                // получаем данные о способе знакомства в моедил предложения (в виде массива)
                $this->meetmethod = JMeetmethod::getItem($this->id_method);
                
                // инвертируем порядок сообщений для соритровки по увеличению даты
                $this->lastMessages = array_reverse($this->lastMessages);
                
                // Получаем модель собеседника текущего пользователя
                $interlocutorID = $this->id_sender == Yii::app()->user->id ? $this->id_reciever : $this->id_sender;
                
                // Получаем только необходимые данные
                $criteria = new CDbCriteria;
                $criteria->select = 't.id, t.name, t.phone, t.birthday, t.id_gender, t.id_userpic, t.fl_deleted';
                
                $this->interlocutor = User::model()->with('city', 'userpic', 'lastAction')->findByPk($interlocutorID, $criteria);
                // прикрепляем к каждой модели сообщения получателя и отправителя из текущей модели,
                // чтобы не делать ещё 2 тяжёлые связи в сообщениях
                foreach($this->lastMessages as $message)
                {
                        // получаем имена получателя и отправителя сообщения
                        $message->senderName = $message->id_sender == Yii::app()->user->id ? Yii::app()->user->getRealname() : $this->interlocutor->name;
                        $message->recieverName = $message->id_reciever == Yii::app()->user->id ? Yii::app()->user->getRealname() : $this->interlocutor->name;
                        // метка, собственное ли это сообщение текущего пользователя
                        $message->isOwn = $message->id_sender == Yii::app()->user->id;
                }
        }
        
        /**
         * Метод проверяет, является ли текущий пользователь отправителем предложения
         * 
         * @return boolean результат
         */
        public function isOwn()
        {
                return Yii::app()->user->isMyId($this->id_sender);
        }
}