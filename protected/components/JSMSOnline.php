<?php

/**
 * Компонент работы с сервисом JSMSOnline
 *
 * @author gbespyatykh
 */
class JSMSOnline extends CApplicationComponent
{
        const CODE_STATUS_NEW = 0;
        const CODE_STATUS_PAID = 1;
    	
        /**
         * @var string логин в сисетме
         */
        public $username;
        
        /**
         * @var string пароль в системе
         */
        public $password;
        
        /**
         * @var string префикс СМС-сообщения 
         */
        public $prefix;
        
        /**
         * @var string имя таблицы, в которую импортирются стоимости номеров
         */
        public $tableCost = 'pay_sms_cost';
        
        /**
         * @var string имя таблицы
         */
        public $tableCode = 'pay_sms_code';

        /**
         * @var string кодировка обработчика запроса от SMS-Online 'utf' или 'cp1251'
         */
        public $encoding = 'utf';
        
        /**
         * @var string пароль для проверки подпись
         */
        public $md5password;
        
        /**
         * Инициализация компонента
         */
        public function init()
        {
		if(!in_array($this->encoding, array('utf', 'cp1251')))
			throw new CException(__CLASS__.': неверное значение кодировки'); 
        }

        /**
         * Команда импортирует с сервера постащика услуги таблицу со стоимостью
         * СМС на короткие намера
         * 
         * @return boolean успешность операции
         */
        public function importCostInfo()
        {
                $csv = Yii::app()->curl->run("http://num.smsonline.ru/csv/?user=$this->username&pass=$this->password");   
                $csv = trim(iconv('windows-1251', 'UTF-8', $csv));
                                           
                $strings = explode("\n", $csv);
                unset($strings[0]);
                unset($strings[1]);
               
                foreach($strings as $key => $item)
                {                                    
                        $item_csv = str_getcsv($item, ';');
                                               
                        $result[] = array(
                                'short_number' => $item_csv[1], // короткий номер
                                'operator' => $item_csv[3], // оператор
                                'cost_user' => str_replace(',', '.', $item_csv[10]), // цена для пользователя (с НДС)
                                'cost_client' => str_replace(',', '.', $item_csv[14]), // выплата заказчику
                                'tariff_group' => $item_csv[2], // тарифная группа 
                        );
                }
                 
                $queryItems = array();
                foreach($result as $item)
                {
                        // нужна только тарифная группа 0
                        if($item['tariff_group'] != '0')
                            continue;
                    
                        $queryItems[] = '("'.$item['short_number'] .'","'. $item['operator'] .'","'. $item['cost_user'] . '","'. $item['cost_client'] . '")';
                }
                
                $queryItems = implode(',', $queryItems);
                            
                Yii::app()->db->createCommand()->truncateTable($this->tableCost);
                Yii::app()->db->createCommand("INSERT INTO $this->tableCost (short_number, operator, cost_user, cost_client) VALUES $queryItems")->execute();
                
                return true;
        }
     
        /**
         * Метод привязывает к уникальному смс-коду произвольный набор параметров
         * и сохраняет данные в таблице
         * 
         * @param integer $operation тип платёжной операции
         * @param integer $userID ID пользователя
         * @param array $data массив произвольных данных (текстовых)
         * @return string|boolean если запись успешно создана, то уникальный
         * код этой записи, иначе false
         */
        public function createUniqueCode($operation, $userID, $data)
        {
                if(!in_array($operation, JPayment::getOperations()))
                        throw new CException(__CLASS__.'::'.__METHOD__ . ': тип операции не существует.');
                
                if(!is_array($data))
                        throw new CException(__CLASS__.'::'.__METHOD__ . ': параметр $data должен быть массивом.');
            
                $date = Yii::app()->localtime->getUTCNow();
                $code = $this->generateUniqueCode($userID);
                
                $command = Yii::app()->db->getCommandBuilder()->createInsertCommand($this->tableCode, array(
                        'date' => $date,
                        'operation' => $operation,
                        'code' => $code,
                        'data' => serialize($data),
                        'status' => self::CODE_STATUS_NEW,
                ))->execute();
                
                return $command ? $code : false;
        }
        
        /**
         * Метод получает данные платежа по СМС-коду
         * 
         * @param string $code SMS-код
         * @return array данные платежа
         */
        public function getSmsData($code)
        {
                return Yii::app()->db->createCommand()
                        ->select('*')
                        ->from($this->tableCode)
                        ->where('code = :smsCode', array('smsCode' => $code))
                        ->queryRow();
        }
        
        /**
         * Метод выставляет платежу статус "оплачено"
         * 
         * @param string $code код платежа
         * @param string $phone телефон плательщика
         * @return boolean результат операции
         */
        public function setPaidStatus($code, $phone, $transaction_id)
        {
                return Yii::app()->db->createCommand()
                        ->update(
                                $this->tableCode, 
                                array(
                                        'phone_user' => $phone, 
                                        'status' => self::CODE_STATUS_PAID,
                                        'transaction_id' => $transaction_id,
                                ), 
                                'code = :smsCode',
                                array(
                                        'smsCode' => $code
                                )
                        );
        }
        
        /**
         * Метод генерирует уникальный смс-код
         * 
         * @param string $prefix префикс
         * @return string уникальное число
         */
        private function generateUniqueCode($prefix)
        {
                return $prefix . time() . rand(0, 9);   
        }
        
        /**
         * Метод возвращает массив со стоимостью СМС-сообщения на указанный
         * короткий номер у различных операторов связи
         * 
         * @param type $shortNumber
         * @return type
         */
        public function getTariffList($shortNumber)
        {
                $data = Yii::app()->db->createCommand()->select('operator, cost_user')
                        ->from($this->tableCost)
                        ->where('short_number = :shortNumber', array(
                                'shortNumber' => $shortNumber
                        ))
                        ->order("FIELD(operator, 'Скай Линк', 'МегаФон', 'МТС', 'Билайн', 'Tele2') DESC")
                        ->queryAll();
                
                $result = array();
                foreach($data as $key => $value)
                        $result[$value['operator']] = $value['cost_user'];
                
                return $result;
        }

        /**
         * Метод выдаёт сообщение, которое высылается пользователю по смс при оплате услуги.
	 * Должен быть вызван при завершении обработки ответа от SMS-Online.
         * При вызове метода завершается работа приложения
         * 
         * @param string $message текст сообщения
         */
        public function responseMessage($message)
        {
		if(!is_string($message))
			throw new CEcxeption(__CLASS__.'::'.__METHOD__.'() - параметр $message должен быть строкой');		

		$messagePrefix = $this->encoding == 'utf' ? 'utf' : 'sms';

		echo $messagePrefix . '=' . $message;
                
                Yii::app()->end();
        }
         
        /**
         * Метод проверяет получены ли необходимые данные от SMS-Online
         * 
         * @param array $superGlobal суперглобальный массив запроса ($_GET или $_POST)
         * @return boolean результат провреки
         */
        public function checkRequest($global)
        {
                return isset($global['pref'], $global['txt'], $global['tid'], $global['cn'], $global['op'], $global['phone'], $global['sn']);
        }
        
        /**
         * Метод проверяет корректность полученных от SmsOnline данных
         * 
         * @param array $superGlobal суперглобальный массив запроса ($_GET или $_POST)
         * @return boolean результат провреки
         */
        public function checkSignature($global)
        {                       
                $internalHash = md5($this->md5password.$global['tid'].$global['sn'].$global['op'].$global['phone'].$global['pref'].$global['txt']);
                             
                return $internalHash == $global['md5'];
        }
}