<?php

/**
 * Модель для таблицы сообщений
 */
class Message extends CActiveRecord
{       
        const STATUS_DELETED = -1;
        const STATUS_UNREAD = 0;
        const STATUS_READ = 1;
        
        /** @var boolean метка, принадлежит ли сообщение текущему пользователю */
        public $isOwn; // метка, своё ли это сообщение
        
        public $sender; // поле для модели User отправителя сообщения
        public $reciever; // поле для модели User получателя сообщения
        
        // поля для имени получателя и отправителя
        public $senderName;
        public $recieverName;
                
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
         * Метод, возвращающий массив с описанием правил валидации модели
         * 
	 * @return array массив правил валидации
	 */
	public function rules()
	{
		return array(
                        array('senderName, recieverName, date, text, id', 'safe', 'on' => 'search')
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
                        // к следующим двум связям к пользователям нужно обращаться только в админке 
                        // (там они сделаны для поиска), чтобы не создавать лишние запросы в бд
                        'sReciever' => array(self::BELONGS_TO, 'User', 'id_reciever'),
                        'sSender' => array(self::BELONGS_TO, 'User', 'id_sender'),
		);
	}

	/**
         * Метод возвращает массив настраиваемых названий
         * атрибутов модели (имя атрибута => название)
         * 
	 * @return array массив названий атрибутов
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_sender' => 'Id Sender',
			'id_reciever' => 'Id Reciever',
			'id_offer' => 'Id Offer',
			'text' => 'Текст',
			'date' => 'Дата',
			'fl_read' => 'Fl Read',
			'type' => 'Type',
		);
	}
        
        /**
         * Метод, вставляющий сообщение в таблицу
         * 
         * @param integer $idSender ID получателя
         * @param integer $idReciever ID отправителя
         * @param integer $idOffer ID предложения
         * @param string $text текст сообщения
         * @param integer $type тип сообщения
         * @return boolean результат операции
         */
        public static function add($idSender, $idReciever, $idOffer, $text)
        {            
                return Yii::app()->db->createCommand()->insert('message', array(
                        'id_sender' => $idSender,
                        'id_reciever' => $idReciever,
                        'id_related' => $idOffer,
                        'type' => Metamessage::TYPE_MESSAGE,
                        'paid' => 0,
                        'text' => $text,
                        'date' => Yii::app()->localtime->getUTCNow(),
                ));
        }
             
        /**
         * Метод, возвращающий предопределённое сообщение по его ID
         * 
         * @param string $id ID сообщения
         * @param integer $gender_sender пол отправителя
         * @param integer $gender_reciever пол получателя
         * @return string сообщение
         * @throws Exception 404
         */
        public static function predefined($id, $gender_sender = null, $gender_reciever = null)
        {
                if($gender_sender == null && $gender_reciever == null)
                {
                        $data = array(
                            '1'                 => 'я предлагаю тебе переписку на сайте.',
                            '2'                 => 'я хочу предложить тебе пообщаться по телефону.',
                            '3'                 => 'я хочу пригласить тебя на прогулку по городу.',
                            '4'                 => 'я хочу пригласить тебя на чашечку кофе или чая.',
                            '5'                 => 'я хочу пригласить тебя в кино.',
                            '6'                 => 'я хочу предложить тебе покататься со мной на авто.',
                            '7'                 => 'я хочу пригласить тебя на романтический ужин в ресторане.',
                            '8'                 => 'я хочу пригласить тебя в боулинг.', 
                            '9'                 => 'я хочу пригласить тебя в ночной клуб.',
                            '10'                => 'я предлагаю устроить совместный шоппинг.',
                            '11'                => 'я приглашаю тебя покататься на роликах или коньках.',
                            '12'                => 'я хочу предложить тебе совместное путешествие.',
                            '13'                => 'я хочу исполнить любое твое желание.',
                            '14'                => 'я хочу пригласить тебя на экстремальное приключение.',
                            '15'                => 'я хочу пригласить тебя в баню или сауну.',
                        );

                        return isset($data[$id]) ? $data[$id] : '';
                }
                else
                {
                        // определяем тип пару: кто-кому
                        if($gender_sender == JGender::MALE && $gender_reciever == JGender::MALE)
                                $g2g = 0;
                        elseif($gender_sender == JGender::MALE && $gender_reciever == JGender::FEMALE)
                                $g2g = 1;
                        elseif($gender_sender == JGender::FEMALE && $gender_reciever == JGender::MALE)
                                $g2g = 2;
                        elseif($gender_sender == JGender::FEMALE && $gender_reciever == JGender::FEMALE)
                                $g2g = 3;
                        else
                                throw new Exception('Пол должен быть 0 или 1');
                        
                        $data = array(
                                'num_agree' => array(
                                        0 => 'я согласен. Мой номер телефона: +7 ',
                                        1 => 'я согласен. Мой номер телефона: +7 ',
                                        2 => 'я согласна. Мой номер телефона: +7 ',
                                        3 => 'я согласна. Мой номер телефона: +7 '
                                ),
                                'msg_agree' => array(
                                        0 => 'я согласен.',
                                        1 => 'я согласен.',
                                        2 => 'я согласна.',
                                        3 => 'я согласна.'
                                ),
                                'dlg_agree' => array(
                                        0 => 'я согласен.',
                                        1 => 'я согласен.',
                                        2 => 'я согласна.',
                                        3 => 'я согласна.'
                                ),                           
                        );
                        
                        return isset($data[$id][$g2g]) ? $data[$id][$g2g] : '';
                }
        }
              
        /**
         * Метод возвращает список моделей удовлетворяющих условиям поиска в 
         * админке
         * 
         * @return CActiveDataProvider
         */
	public function search($userID)
	{
		$criteria=new CDbCriteria;
                $criteria->with = array('sSender', 'sReciever');
		$criteria->compare('t.id',$this->id);
		$criteria->compare('sSender.name',$this->senderName, true);
		$criteria->compare('sReciever.name',$this->recieverName, true);
		$criteria->compare('t.date',$this->date);
                $criteria->addCondition("t.id_sender = {$userID} OR t.id_reciever = {$userID}");
                $criteria->compare('t.type', Metamessage::TYPE_MESSAGE);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'sort'=>array(
                                'defaultOrder'=>'t.id DESC',
                                'attributes'=>array(
                                        'sSender.name'=>array(
                                                'asc'=>'sSender.name',
                                                'desc'=>'sSender.name DESC',
                                        ),
                                        'sReciever.name'=>array(
                                                'asc'=>'sReciever.name',
                                                'desc'=>'sReciever.name DESC',
                                        ),
                                        '*',
                                ),
                        ),
                        'pagination' => array(
                                'pageSize' => 50,
                        ),                        
		));
	}
        
        /**
         * Метод возвращает метку, является ли отправитель сообщения текущим пользователем
         * 
         * @return boolean
         */
        public function isOwn()
        {
                return $this->id_sender == Yii::app()->user-id;
        }
        
        /**
         * Метод получает массив сообщений для чата
         * 
         * @param integer $offerID ID предложения
         * @param integer $limit лимит выборки
         * @param integer $lastID ID последнего сообщения в чате
         * @return array массив собщений
         */
        public static function getChatMessages($offerID, $lastID = 0)
        {
                $result = Yii::app()->db->createCommand()
                        ->select('
                                message.id AS id,
                                message.text AS text,
                                message.date AS date,
                                sender.id AS sender_id,
                                sender.name AS sender_name,
                                reciever.id AS reciever_id,
                                reciever.name AS reciever_name
                        ')
                        ->from('message')
                        ->where('message.id_related = :offerID AND message.id > :lastID AND message.status >= 0', array('offerID' => $offerID, 'lastID' => $lastID))
                        ->join('user sender', 'sender.id = message.id_sender')
                        ->join('user reciever', 'reciever.id = message.id_reciever')
                        ->order('date DESC')
                        ->queryAll();
                
                return array_reverse($result);
        }
}