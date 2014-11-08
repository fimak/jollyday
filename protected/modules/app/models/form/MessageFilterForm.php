<?php
/**
 * Модель формы фильтра сообщений
 */
class MessageFilterForm extends CFormModel
{
        /**
         * @var string тип предложения: все контакты, переписка, чёрный список
         * Подробнее в методе MessageFilterForm::messageFilterTypeValues()
         */
        public $type;
        
        /**
         * @var integer ID способа знакомства
         */
        public $mid;
        
        /**
         * @var string имя пользователя
         */
        public $name;
        
        /**
         * Метод, возвращающий массив с описанием правил валидации модели
         * 
	 * @return array массив правил валидации
	 */
        public function rules()
        {
                return array(
                        array('type, mid, name', 'safe'),
                );
        }
                
        public function buildMetaMessageCriteria()
        {
                $criteria = new CDbCriteria;
                
                $typeMessage = Metamessage::TYPE_MESSAGE;
                $typeGift = Metamessage::TYPE_GIFT;
                $statusNew = Offer::NOT_ACCEPTED;
                $statusAccepted = Offer::ACCEPTED;
                
                $userId = Yii::app()->user->id;
                
                // выбираем только сообщения с существующими предложениями и подарками
                $criteria->select = 'id_related';
                $criteria->join .= " JOIN user sender ON sender.id = t.id_sender";
                $criteria->join .= " JOIN user reciever ON reciever.id = t.id_reciever";
                
                if($this->type == 'offers')
                        $criteria->join .= " JOIN offer offer ON t.id_related = offer.id AND offer.status = $statusNew AND t.type = $typeMessage";
                elseif($this->type == 'correspondence')
                        $criteria->join .= " JOIN offer offer ON t.id_related = offer.id AND offer.status = $statusAccepted AND t.type = $typeMessage";
                
                $criteria->addCondition("t.id_reciever = $userId OR t.id_sender = $userId");
                $criteria->addCondition("sender.fl_deleted <> 1 && reciever.fl_deleted <> 1");

                // только неудалённые сообщения
                $criteria->addCondition('t.status >= 0');
                $criteria->addCondition("t.type = $typeMessage");
                
                
                $criteria->order = "t.paid = 1 AND t.id_reciever = $userId DESC, MAX(t.date) DESC";
                $criteria->group = '
                        CASE t.type
                            WHEN 0 THEN t.id_related
                            ELSE t.id
                        END
                ';
                
                return $criteria;
        }
             
        /**
         * Критерий для поиска по чёрному списку
         */
        public function buildBlacklistCriteria()
        {
                $criteria = new CDbCriteria;
                $userID = Yii::app()->user->id;
                
                $criteria->select = 'id_blacklisted';
                
                $criteria->compare('id_user', $userID);
                
                // критерий по имени собеседника
                if($this->name != null)
                { 
                        $criteria->join .= " JOIN user ON user.id = t.id_blacklisted";
                        $criteria->addCondition("user.name LIKE '%{$this->name}%'");
                }
                
                return $criteria;
        }
        
        /**
         * Метод получает значения типов фильтра сообщения
         * 
         * @return array список типов фильтра
         */
        public static function getTypeFilterValues()
        {
                return array(
                        'all' => 'Все контакты',
                        'offers' => 'Предложения',
                        'correspondence' => 'Переписка',
                        'blacklist' => 'Чёрный список'
                );
        }
}
?>
