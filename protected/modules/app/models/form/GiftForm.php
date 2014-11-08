<?php

/**
 *  
 */

class GiftForm extends CFormModel
{
        /**
         * @var integer ID получателя подарка
         */
        public $id_reciever;
        
        /**
         * @var integer ID подарка (прототипа)
         */
        public $id_gift;
        
        /**
         * @var string открытка к подарку 
         */
        public $postcard;
        
        /**
         * @var boolean показывать ли открытьку только получателю и отправителю
         */
        public $is_private;
        
        /**
         * @var integer ID отправителя 
         */
        public $id_sender;
        
        const POSTCARD_LENGTH = 150;
        
        /**
         * Метод, возвращающий массив с описанием правил валидации модели
         * 
	 * @return array массив правил валидации
	 */
	public function rules()
	{
		return array(
                    array('postcard', 'filter', 
                            'filter' => array($this, 'filterPostcard'),
                    ),
                    array('id_reciever', 'exist', 'className' => 'User', 'attributeName' => 'id'),
                    array('id_gift', 'exist', 'className' => 'Gift', 'attributeName' => 'id'),
                    array('postcard', 'length', 'allowEmpty'=>true, 'max'=>  self::POSTCARD_LENGTH, 'tooLong'=>'Текст открытки слишком длинный' ),
                    array('is_private', 'boolean'),
		);
	}
        
        /**
         * Метод проверяет, больше ли указанная сумма, чем счёт пользователя.
         * 
         * @param float $cost
         * @return boolean
         */
        public function checkAccount($cost)
        {
                return $cost > Yii::app()->user->getAccount();
        }
        
        /**
         * Подарить подарок
         * 
         * @param type $id_sender
         * @param type $id_reciever
         * @param type $id_gift
         * @param type $postcard
         * @return boolean
         */
        public function sendGift()
        {       
                $result =  Yii::app()->db->createCommand()->insert('im_user_gift', array(
                        'id_sender' => $this->id_sender,
                        'id_reciever' => $this->id_reciever,
                        'id_gift' => $this->id_gift,
                        'postcard' => $this->postcard,
                        'date' => Yii::app()->localtime->getUTCNow(),
                        'is_private' => $this->is_private,
                ));
                
                if($result)
                {
                        // сбрасываем кеш подарков получателя при дарении
                        Yii::app()->cache->delete('gifts_'.$this->id_reciever);
                        $userData = User::getBaseInfo($this->id_reciever);
                        $senderData = User::getBaseInfo($this->id_sender);
                        
                        if(JSMS::checkSendingAvailability($this->id_reciever, JSMS::TYPE_GIFT))
                        {
                                JSMS::giftMessage($userData['phone'], $senderData['name']);
                                JSMS::updateSmsLogTime($this->id_reciever, JSMS::TYPE_GIFT);
                        }
                }
                
                return $result;
        }
        
        public function filterPostcard($value)
        {
                // вырезаем всё, кроме цифр
                return str_replace("\n", "", $value);
        }  
}