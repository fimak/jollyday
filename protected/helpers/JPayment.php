<?php

/**
 * Хелпер для платёжных систем
 */
class JPayment
{
        const EXCHANGE_RATE = 30; //стоимость одной монеты
    
        // типы операций
        const OPERATION_BALANCE = 0; // пополнение баланса
        const OPERATION_RATING = 1; // подняться в рейтинге на 1 место
        const OPERATION_GIFT = 2; // сделать подарок
        const OPERATION_OFFERNOTICE = 3; // уведомление о предложении
        
        const COST_RATING = 1; // цена поднятия рейтинга
        const COST_OFFERNOTICE = 0.3; // цена уведомления
        const COST_OFFERNOTICE_RUB = 10;
        
        // множители бонусного счёта
        const BONUS_MULTIPLIER_FIVE_HUNDREDS = 4; // первые 500 по региону в первые сутки
        const BONUS_MULTIPLIER_FIRST_DAY = 2; // первые сутки
        const BONUS_MULTIPLIER_NORMAL = 0.5; // всегда
        
        const BONUS_NOT_RECIEVED = 0;
        const BONUS_RECIEVED = 1;

        /**
         * Метод получает список доступных финансовых операций на сайте
         * 
         * @return array
         */
        public static function getOperations()
        {
                return array(
                        self::OPERATION_BALANCE,
                        self::OPERATION_RATING,
                        self::OPERATION_GIFT,
                        self::OPERATION_OFFERNOTICE,
                );
        }
        
        /**
         * Метод получаем описание типа операции по его ID
         * 
         * @param integer $operationID ID операции
         * @return string описание
         */
        public static function getOperationdescription($operationID)
        {
                $data = array(
                        self::OPERATION_BALANCE => 'Пополнение баланса',
                        self::OPERATION_RATING => 'Подняться в рейтинге',
                        self::OPERATION_GIFT => 'Подарок',
                        self::OPERATION_OFFERNOTICE => 'Уведомление',
                );
                
                return isset($data[$operationID]) ? $data[$operationID] : 'Тип операции не указан';
        }


        /**
         * Метод получает список доступных сумм (в монетах) для
         * пополнения счёта пользователя и соответствующих им стоимостей
         * 
         * @return array
         */
        public static function getAvailableAmountList()
        {
                return array(
                        self::EXCHANGE_RATE * 1     => '1',
                        self::EXCHANGE_RATE * 5     => '5',
                        self::EXCHANGE_RATE * 10    => '10',
                        self::EXCHANGE_RATE * 15    => '15',
                        self::EXCHANGE_RATE * 20    => '20',
                        self::EXCHANGE_RATE * 25    => '25',
                        self::EXCHANGE_RATE * 40    => '40',
                        self::EXCHANGE_RATE * 50    => '50',
                        self::EXCHANGE_RATE * 75    => '75',
                        self::EXCHANGE_RATE * 100   => '100',
                );  
        }
        
        /**
         * Метод получает реальную стоимость указанного количества монет
         * 
         * @param float $money в условной валюте
         * @return float сумма в реальной валюте
         */
        public static function getRealCost($money)
        {
                if(!is_numeric($money))
                        throw new CException(__CLASS__.'::'.__METHOD__.': параметр $money должен быть числом');
            
                return self::EXCHANGE_RATE * $money;
        }
        
        /**
         * Метод получает соответствие: количество монет: короткий номер СМС
         * для выполнения услуги на данную сумму
         * 
         * @return array
         */
        public static function getSmsCost()
        {
                return array(
                        '0.3'   => '2320',
                        '1'     => '7375',
                        '2'     => '6365',
                        '3'     => '9999',
                        '5'     => '8385',
                        '10'    => '9395',
                );
        }
        
        /**
         * Метод получает короткий номер СМС для выбранной стоимости
         * 
         * @param string $cost цена в условной валюте
         * @return string короткий номер СМС
         */
        public static function getShortNumber($cost)
        {
                $data = self::getSmsCost();
                
                return isset($data[(string)$cost]) ? $data[(string)$cost] : '';
        }
        
        /**
         * Метод получает список типов платёжных систем в IntellectMoney для
         * формы во всплывающем окне
         * 
         * @return array список способов
         */
        public static function getIntellectmoneyMethodList()
        {
                return array(
                    'bankCard' => 'Банковская карта',
                );
        }
        
        /**
         * Метод добавляет пользователю указанную сумму на счёт
         * 
         * @param integer $userID ID пользователя
         * @param float $amount сумма для пополнения
         * @param float $bonusAmount бонуснуя сумма для пополнения
         * @return boolean результат операции
         * @throws СException
         */
        public static function addMoney($userID, $amount, $bonusAmount = 0)
        {
                if(!is_numeric($amount) || !is_numeric($bonusAmount))
                        throw new СException(__CLASS__.'::'.__METHOD__.': Сумма должна быть числом');
                
                if($amount < 0 || $bonusAmount < 0)
                        throw new СException(__CLASS__.'::'.__METHOD__.': Сумма не может быть отрицательной');
                
                $sql = "UPDATE `user` SET `account` = `account` + $amount, `account_bonus` = `account_bonus` + $bonusAmount WHERE `id` = $userID";
                
                return Yii::app()->db->createCommand($sql)->execute();
        }
        
        /**
         * Метод вычитает из счёта пользователя указанную сумму.
         * В первую очередь сумма снимается с основного счёта, потом с бонусного.
         * 
         * @param integer $userID ID пользователя
         * @param float $amount сумма для снятия с баланса
         * @return boolean результат выполнения операции
         * @throws СException
         */
        public static function subMoney($userID, $amount)
        {
                if(!is_numeric($amount))
                        throw new СException(__CLASS__.'::'.__METHOD__.': Сумма должна быть числом');
                
                if($amount < 0)
                        throw new СException(__CLASS__.'::'.__METHOD__.': Сумма не может быть отрицательной');
            
                $currentAccount = Yii::app()->db->createCommand()
                        ->select('account, account_bonus')
                        ->from('user')
                        ->where('id = :userID', array('userID' => $userID))
                        ->queryRow();
                
                // если пользователь не существует
                if($currentAccount === false)
                        return false;
                
                $account = $currentAccount['account'];
                $bonus = $currentAccount['account_bonus'];
                
                // если сумма больше, чем счета пользователя
                if($amount > $account + $bonus)
                        return false;
                
                if($amount < $account)
                {  
                        // если основного счёта хватает, то просто вычитаем  из него сумму
                        $account -= $amount;
                }
                else
                {
                        // если не хватает, то получаем остаточную сумму, которую надо
                        // снять с бонусного счёта, и зануляем основной счёт
                        $delta = $amount - $account;
                        $account = 0;
                        $bonus -= $delta;
                }
                              
                return Yii::app()->db->createCommand()
                        ->update('user', array('account' => $account, 'account_bonus' => $bonus), 'id = :userID', array('userID' => $userID));
        }
        
        
        /**
         * Метод увеличивает счётчик количества пользователей, получивших бонус 
         * в выбранном регионе
         * 
         * @param integer $regionID ID региона
         * @return boolean результат выполения операции
         */
        public static function updateBonusCounter($regionID)
        {
                $sql = "UPDATE bonus SET count_users = count_users + 1 WHERE id_region = $regionID";
                return Yii::app()->db->createCommand($sql)->execute();
        }
        
        /**
         * Метод получает значение счётчика пользователей, получивших бонус
         * в выбранном регионе
         * 
         * @param integer $regionID
         * @return integer кколичество пользователей
         */
        public static function getBonusCounter($regionID)
        {
                return Yii::app()->db->createCommand()
                        ->select('count_users')
                        ->from('bonus')
                        ->where('id_region = :regionID', array('regionID' => $regionID))
                        ->queryScalar();            
        }
        
       /**
         * Метод получает данные о бонусной программе пользователя
         * 
         * @param integer $userID ID пользователя
         * @return array флаг получения бонуса и дата регистрации
         */
        public static function getBonusData($userID)
        {
                $data = Yii::app()->db->createCommand()
                              ->select('date_register, id_region')
                              ->from('user')
                              ->where('id = :userID', array('userID' => $userID))
                              ->queryRow();
            
                $dateRegister = $data['date_register'];
                $regionID = $data['id_region'];
                $secondsInDay = 60 * 60 * 24;
                
                if(!empty($dateRegister) && !empty($regionID))
                {                                    
                        $dtNow = time();
                        $dtRegister = strtotime($dateRegister);
                        $secondsLeft = $secondsInDay - ($dtNow - $dtRegister);
                                          
                        // если разница между датой регистрации и текущей датой более одних суток
                        // или произошла ошибка при сравнеии дат, то возвращается false
                        $data['fl_bonus_available'] = $secondsLeft > 0;                   
                        $data['seconds_left'] = $secondsLeft;  
                        $data['counter'] = JPayment::getBonusCounter($regionID);                 
                        
                        if($data['counter'] === null || $data['counter'] === false)
                                $data['counter'] = 9999;
                }
                else
                        $data = array(
                                'fl_bonus_available' => false,
                                'seconds_left' => 60 * 60 * 24 * 365,
                                'counter' => 99999999
                        );
                
                return $data;
        }
        
        /**
         * Метод склоняет по падежам слово монеты в зависимости от суммы так, как
         * надо заказчику.
         * 
         * @param float $amount суммв
         * @return string слово
         */
        public static function formatMoneyWord($amount)
        {      
                if($amount - floor($amount) == 0)
                        return Yii::t('jolly', 'money', $amount);
                else
                        return $amount < 5 ? 'монеты' : 'монет';

        }
        
        /**
         * Форматирование суммы таким образом, как надо заказчику
         * 
         * @param float $amount сумма
         * @return string отформатировання сумма
         */
        public static function formatAmount($amount)
        {
                if($amount - floor($amount) == 0)
                {
                        $amount = (int)$amount;
                        return $amount;
                }
            
                return Yii::app()->format->formatNumber($amount);
        }
        
}

?>
