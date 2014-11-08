<?php

/**
 * Класс компонента, отвекчающего за отправку SMS
 */
class JSMS16 extends CApplicationComponent
{
        /**
         * @var string имя отправителя СМС
         */
        public $originator;
        
        /**
         * @var string логин
         */
        public $login;
        
        /**
         * @var string пароль
         */
        public $password;
        
        /**
         * @var string URL сервера
         */
        public $server;
        
        /**
         * Инициализация компонента
         * 
         * @throws CException
         */
        public function init()
        {
                parent::init();
                
                if(empty($this->originator))
                        throw new CException(__CLASS__ . ' - не указано имя отправителя СМС');
                if(empty($this->login))
                        throw new CException(__CLASS__ . ' - не указан логин');
                if(empty($this->password))
                        throw new CException(__CLASS__ . ' - не указан пароль');
                if(empty($this->server))
                        throw new CException(__CLASS__ . ' - не указан сервер');
        }
         
        /**
         * Метод отправляет смс-сообщение на указанный номер
         * 
         * @param string $phone номер телефона
         * @param string $message текст сообщения
         * @return mixed результат отправки сообщения
         */
        public function singleSMS($phone, $message)
        {
                $xml = '<?xml version="1.0" encoding="utf-8" ?>
                <request>
                    <message type="sms">
                        <sender>'.$this->originator.'</sender>
                        <text>'.$message.'</text>
                        <abonent phone="'.$phone.'"/>
                    </message>
                    <security>
                        <login value="'.$this->login.'" />
                        <password value="'.$this->password.'" />
                    </security>
                </request>';

                return $this->curlRequest($this->server, $xml);
        }
        
        /**
         * Метод совершает массовую рассыку СМС
         * 
         * @param array $messages массив сообщений
         * @return object результат отправки СМС
         * @throws Exception
         */
        public function bulkSMS( $messages )
        {
                $number_sms = 1;

                $request = '<?xml version="1.0" encoding="utf-8"?><request><security><login value="' . $this->login . '" /><password value="' . $this->password . '" /></security>';
                foreach ( $messages as $message )
                {
                        if ( empty( $message->type ) )
                                throw new Exception( __METHOD__ . ' - Поле "$message->type" не может быть пустым.' );

                        if ( empty( $message->sender ) )
                                throw new Exception( __METHOD__ . ' - Поле "$message->sender" не может быть пустым.' );

                        if ( empty( $message->text ) )
                                throw new Exception( __METHOD__ . ' - Поле "$message->text" не может быть пустым.' );

                        if ( empty( $message->abonent ) )
                                throw new Exception( __METHOD__ . ' - Поле "$message->phone" не может быть пустым.' );

                        $request .= '<message type="' . $message->type . '"><sender>' . $message->sender . '</sender>';
                        $OptionalFields = array(
                                'text', 'url', 'name', 'cell', 'work', 'fax', 'email', 'position', 'organization', 'post_office_box',
                                'street', 'city', 'region', 'postal_code', 'country', 'additional'
                        );
                        foreach ( $OptionalFields as $field )
                                if ( isset( $message->$field ) )
                                        $request .= '<' . $field . '>' . htmlspecialchars( $message->$field ) . '</' . $field . '>';

                        if ( is_array( $message->abonent ) )
                        {
                                foreach ( $message->abonent as $abonent ) {
                                        $request .= $this->formatAbonent( $abonent, $number_sms );
                                        $number_sms++;
                                }
                        } 
                        else 
                        {
                                $request .= $this->formatAbonent( $message->abonent, $number_sms );
                                $number_sms++;
                        }
                        $request .= '</message>';

                }
                $request .= '</request>';
                $result = $this->request( __METHOD__, $this->server, $request );
                return $result;
        }
        
        /**
         * Метод приводит форматирует абонента СМС
         * 
         * @param string $abonent абонент
         * @param integer $number_sms число СМС
         * @return string XML
         */
        private function formatAbonent($abonent, $number_sms)
        {
                if ( !is_object( $abonent ) ) 
                        $abonent = (object)array( 'phone' => $abonent );
                return '<abonent phone="' . $abonent->phone . '" number_sms="' . $number_sms . '"'
                        . ( !empty( $abonent->phone_id ) ? ' phone_id="' . $abonent->phone_id . '"' : '' )
                        . ( !empty( $abonent->time_send ) ? ' time_send="' . date( 'Y-m-d H:i:s', $abonent->time_send ) . '"' : '' )
                        . '/>';
        }
        
        /**
         * Метод осуществляет CURL-запрос
         * 
         * @param string $body тело запроса
         * @param string $url адрес запроса
         * @return mixed адрес запроса
         */
        private function curlRequest($url, $body)
        {
                $ch = curl_init();
                curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-type: text/xml; charset=utf-8'));
                curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt( $ch, CURLOPT_CRLF, true);
                curl_setopt( $ch, CURLOPT_POST, true);
                curl_setopt( $ch, CURLOPT_POSTFIELDS, $body);
                curl_setopt( $ch, CURLOPT_URL, $url);
                
                return curl_exec($ch);  
        }
        
        /**
         * Метод конвертирует SimpleXMLElement в многомерный массив
         * 
         * @param SimpleXMLElement $xml SimpleXMLElement для конвертации
         * @return array
         * @throws Exception
         */
        private function simpleXMLToArray($xml)
        {
                $ObjectNodes = array(
                        'error',
                        'money',
                        'any_originator',
                        'version',
                        'originator'
                );

                if ( !( $xml instanceof SimpleXMLElement ) )
                        throw new Exception( __METHOD__ . ' - Параметр $xml должен иметь тип SimpleXMLElement' );

                $result = (object)null;
                $value = trim( (string)$xml );
                if ( !empty( $value ) )
                        $result->value = $value;

                foreach ( $xml->children() as $elementName => $child )
                {
                        if ( in_array( $elementName, $ObjectNodes ) )
                        {
                                if( $child->count || $child->attributes->count ) 
                                        $result->$elementName = $this->simpleXMLToArray( $child );
                                else 
                                        $result->$elementName = trim( (string)$child );
                        } 
                        else 
                        {
                                $result->{$elementName}[] = $this->simpleXMLToArray( $child );
                        }
                }
                foreach ( $xml->attributes() as $attr_name => $value )
                        $result->$attr_name = trim( $value );

                return $result;
        }
        
        /**
         * @param string $url
         * @param $body
         * @return array
         * @throws Exception
         */
        private function request($method, $url, $body)
        {
                return $this->decodeResponse( $this->curlRequest( $url, $body ), $method );
        }
        
        /**
         * @param string $response ответ сервера
         * @param string $method название метода
         * @return object
         * @throws Exception
         */
        public function decodeResponse( $response, $method = __METHOD__ )
        {
                if ( substr( $response, 0, 5) != '<?xml' )
                        throw new Exception( $method . ' - Недопустимый формат ответа сервера sms16. Текст ответа: ' . $response );

                $xml = new SimpleXMLElement( $response );
                
                if ( $xml->getName() != 'response' )
                        throw new Exception( $method . ' - Недопустимый ответ сервера sms16. Содержимое ответа: ' . $response );

                $result = (object)array( 'response' => $this->simpleXMLToArray( $xml ) );

                if ( isset( $result->response->error ) )
                        throw new Exception( $method . ' - ' . $result->response->error->value );

                return $result;
        }

        /**
         * @return object
         */
        public function version()
        {
                $request = '<?xml version="1.0" encoding="utf-8"?>
                            <request></request>';
                $result = $this->request( __METHOD__, $this->server . 'version.php', $request );
                return $result;
        }

        /**
         * @return object
         */
        public function balance()
        {
                $request = '<?xml version="1.0" encoding="utf-8"?>
                            <request>
                                <security>
                                    <login value="' . $this->login . '" />
                                    <password value="' . $this->password . '" />
                                </security>
                            </request>';
                $result = $this->request( __METHOD__, $this->server . 'balance.php', $request );
                return $result;
        }

        /**
         * @param int $from - unix timestamp
         * @param int $to - unix timestamp
         * @return object
         */
        public function incoming( $from, $to )
        {
                $request = '<?xml version="1.0" encoding="utf-8"?>
                            <request>
                                <security>
                                    <login value="' . $this->login . '" />
                                    <password value="' . $this->password . '" />
                                </security>
                                <time start="' . date('Y-m-d H:i:s', $from) . '" end="' . date( 'Y-m-d H:i:s', $to ) . '"/>
                            </request>';
                $result = $this->request( __METHOD__, $this->server . 'incoming.php', $request );
                return $result;
        }

        /**
         * @param array $id_sms ID СМС-сообщения
         * @return object
         * @throws Exception
         */
        public function state( $id_sms )
        {
                if ( !is_array( $id_sms ) || count( $id_sms ) == 0 ) {
                        throw new Exception( __METHOD__ . ' - $id_sms не может быть пустым.' );
                }

                $request = '<?xml version="1.0" encoding="utf-8"?><request><security><login value="' . $this->login . '" /><password value="' . $this->password . '" /></security><get_state>';
                foreach ( $id_sms as $id ) {
                        if ( !isset( $id ) ) {
                                throw new Exception( __METHOD__ . ' - $id не может быть пустым.' );
                        }
                        $request .= '<id_sms>' . $id . '</id_sms>';
                }
                $request .= '</get_state></request>';
                $result = $this->request( __METHOD__, $this->server . 'state.php', $request );
                return $result;
        }

        /**
         * @return object
         */
        public function originator()
        {
                $request = '<?xml version="1.0" encoding="utf-8"?><request><security><login value="' . $this->login . '" /><password value="' . $this->password . '" /></security></request>';
                $result = $this->request( __METHOD__, $this->server . 'originator.php', $request );
                return $result;
        }
}