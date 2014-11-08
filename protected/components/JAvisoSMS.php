<?php

/**
 * Класс для работы с сервисом AvisoSMS Мобильная коммерция
 *
 * Документация: http://avisosms.ru/m-commerce/api/
 */
class JAvisoSMS extends CApplicationComponent
{
            const STATUS_SUCCESS = 'success';
            const STATUS_FAILURE = 'failure';
            const STATUS_CANCEL = 'cancel';
            const STATUS_PENDING = 'pending';

            /**
             * @var  Ссылка до API
             */
            public $url = 'https://api.avisosms.ru/mc/';

            /**
             * @var  Имя пользователя в системе AvisoSMS
             */
            public $username;

            /**
             * @var  Ключ доступа. Указывается в настройках аккаунта (Настройки удалённого доступа)
             */
            public $secureHash;

            /**
             * @var  ID сервиса
             */
            public $serviceId;

            /**
             * @var  Время ожидания ответа от сервера в секундах
             */
            public $timeout = 10;

            /**
             * @var  Кодировка приложения
             */
            public $charset = 'UTF-8';

            /**
             * @var  Расшифровка статусов заказов.
             */
            public $order_status = array(
                    'success' => 'Заказ успешно оплачен',
                    'failure' => 'Заказ не был оплачен',
                    'cancel'  => 'Заказ был отменён пользователем со стороны сотового оператора',
                    'pending' => 'Заказ обрабатывается',
            );

            /**
             * @var  Расшифровка статусов.
             */
            private $_status    = array(
                    '0' => 'Заказ создан успешно',
                    '1' => 'Неизвестная ошибка',
                    '2' => 'Для данного номера не доступна услуга мобильной коммерции',
                    '3' => 'Параметры переданы неверно',
                    '4' => 'Ошибка авторизации',
                    '5' => 'Ошибка проверки цифровой подписи',
                    '255' => 'Ошибка соединения с сервером',
            );

            private $_response = null;
            private $_error_message = null;

            /**
             * @var  Режим тестирования
             */
            public $test        = false;
            public $debug_text  = '';

            /**
             * @var  Кодировка скрипта
             */
            const CHARSET = 'UTF-8';

            /**
             * @param string Описание заказа. Максимальная длина 100 символов, минимальная - 10.
             * @param string Сумма заказа. Дробные числа указываются через точку. Максимум до сотых долей.
             * @param string Сообщение, отправляемое пользователю, в случае успешного завершения оплаты.
             * @param string Телефон абонента.
             * @param string Необязательный параметр. ID платежа в системе магазина. До 100 знаков.
             *
             * @return  boolean Возвращает true, если status = 0, иначе false
             */
            function createOrder($description, $price, $success_message, $phone, $merchant_order_id = '')
            {
                    $data = array(
                            'description'       => $description,
                            'price'             => (float)number_format($price, 2, '.', ''),
                            'success_message'   => $success_message,
                            'phone'             => $phone,
                            'merchant_order_id' => $merchant_order_id,
                    );
                    return $this->send($data, 'create_order');
            }

            /**
             * Запрос статуса заказа
             * 
             * @return  boolean Возвращает true, если status = 0, иначе false
             */
            function getOrderStatus($phone, $order_id)
            {
                    $data = array(
                            'phone'             => $phone,
                            'order_id'          => $order_id,
                    );
                    return $this->send($data, 'get_order_info');
            }

            /**
             * Обработка оповещения о платеже
             * 
             * @return array|boolean массив полученных данных или falseы
             */
            function getResponse()
            { 
                    $data = file_get_contents("php://input");
                    $data = CJSON::decode($data);

                    $isRecievedRequiredParams = isset(
                            $data['sign'], $data['order_id'], $data['order_status'], $data['phone'], 
                            $data['merchant_price'], $data['charged_sum'], $data['merchant_order_id']
                    );
                    
                    return $isRecievedRequiredParams ? $data : false;
            }

            /**
             * Обращение к API
             *
             * @param   array       Массив с данными
             * @param   string      Название функции
             * @return  boolean Возвращает true, если status = 0, иначе false
             */
            function send($data, $postfix)
            {
                    if ($this->test)
                            $data['test'] = true;

                    if ($this->charset <> self::CHARSET) 
                            foreach($data as $k => $v) 
                                $data[$k] = iconv($this->charset, self::CHARSET, $v);

                    $data['username'] = $this->username;
                    $data['service_id'] = $this->serviceId;    
                    $data['sign'] = md5($data['phone'] . $data['service_id'] . $data['username'] . $this->secureHash);

                    $url = $this->url.$postfix.'/';
                    $json_data = json_encode($data);

                    $this->debug_text .= 'Запрос: <b>' . $postfix . '</b><br />';        
                    $this->debug_text .= '<pre>' . print_r($data, true) . '</pre>';
                    $this->debug_text .= 'Цифровая подпись: '. $data['sign'] . '<br />';
                    $this->debug_text .= 'Строка для подписи: '. ($data['phone'] . $data['service_id'] . $data['username'] . $this->secureHash). '<br />';

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
                    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
                    curl_setopt($ch, CURLOPT_COOKIE, 0); 
                    curl_setopt($ch, CURLOPT_VERBOSE, 1);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);

                    $result = curl_exec($ch);

                    $this->debug_text .= 'Передаем запрос '.$url.': <br><pre>'.print_r($data, true).'</pre>';
                    $this->debug_text .= 'Получаем ответ: <br><pre>'.print_r(json_decode($result), true).'</pre>';
                    $this->_response = array('status' => 255);

                    //echo $this->debug_text;
                    if (curl_errno($ch))
                    {
                        $this->_error_message = curl_error($ch);
                    }
                    else
                    {
                        $this->_response = array_merge($this->_response, (array)json_decode($result, true));
                        $this->_error_message = $this->_status[$this->_response['status']];
                    }

                    curl_close($ch);
                    return $this->_response;
            }

            /**
             * Возвращает ответ от сервера
             *
             * @return  array   Ответ от сервера
             */
            public function response()
                {
                    $data = $this->_response;
                    if ($this->charset <> self::CHARSET) foreach($data as $k => $v) {
                        $data[$k] = iconv(self::CHARSET, $this->charset, $v);
                    }
                    return $data;
            }

            /**
             * Возвращает текст ошибки
             *
             * @return  string   Текст ошибки
             */
            public function error_message()
            {
                    return ($this->charset == self::CHARSET) 
                            ? $this->_error_message 
                            : iconv(self::CHARSET, $this->charset, $this->_error_message);
            }

            /**
             * Возвращает текстовый статус заказа
             *
             * @param   string      Статус
             * @return  string
             */
            public function order_status($status)
            {
                    if (!isset($this->order_status[$status]))
                            return null;

                    return ($this->charset == self::CHARSET) 
                            ? $this->order_status[$status] 
                            : iconv(self::CHARSET, $this->charset, $this->order_status[$status]);
            }

            /**
             * Метод вставляет в базу ID платежа с сопутсвующими данными
             * 
             * @param integer $userId
             * @param integer $type
             * @param array $data
             */
            public function generateOrder($userId, $type, $data)
            {
                    $orderId = md5($userId.$type.microtime());

                    $data = serialize($data);

                    $insertResult = Yii::app()->db->createCommand()
                            ->insert('pay_sms_aviso', array(
                                    'id_user' => $userId,
                                    'type' => $type,
                                    'data' => $data,
                                    'merchant_order_id' => $orderId,
                                    'date' => Yii::app()->localtime->getUTCNow(),
                            ));

                    return $insertResult ? $orderId : false;
            }

            /**
             * Метод получает данные по номеру платежа
             * 
             * @param type $merchantOrderId
             * @return boolean
             */
            public function getOrderData($merchantOrderId)
            {
                    $data = Yii::app()->db->createCommand()
                            ->select()
                            ->from('pay_sms_aviso')
                            ->where('merchant_order_id = :merchantOrderId', array('merchantOrderId' => $merchantOrderId))
                            ->queryRow();

                    if(empty($data))
                            return false;

                    $data['data'] = unserialize($data['data']);

                    return !empty($data) ? $data : false;
            }

            /**
             * Метод устанавливает флаг оплат заказа
             * 
             * @param type $orderId
             * @param type $phone
             */
            public function setPaidOrderStatus($merchantOrderId, $orderId, $phone)
            {
                    $result = Yii::app()->db->createCommand()
                            ->update('pay_sms_aviso', array(
                                    'status' => self::STATUS_SUCCESS,
                                    'order_id' => $orderId,
                                    'phone' => $phone,
                            ), 'merchant_order_id = :mOrderId', array('mOrderId' => $merchantOrderId));
            }

            /**
             * Расшифровка статуса создания заказа
             * 
             * @param integer $status
             * @return string
             */
            public function getCreateOrderStatusDescription($status)
            {
                    return isset($this->_status[$status]) ? $this->_status[$status] : 'Неизвестная ошибка';
            }

            /**
             * Ответ для aviso при обработке платежа
             * 
             * @param integer $status
             */
            public function responseResult($status)
            {
                    echo CJSON::encode(array('status' => $status));
                    Yii::app()->end();
            }

            /**
             * Проверка контрольной подписи
             * 
             * @param string $phone номер телефона абонента
             * @param integer $order_status статус платежа
             * @param string $sign контрольная подпись, полученная от сервера
             * @return boolean результат проверки
             */
            public function checkSignature($phone, $order_status, $sign)
            {
                    $internalSign = md5($phone.$order_status.$this->serviceId.$this->username.$this->secureHash);

                    return $sign == $internalSign;
            }
}