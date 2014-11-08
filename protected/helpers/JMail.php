<?php
/**
 * Обёртка для работы с почтой
 *
 * @property string $activateSubject Тема письма для активации почтового ящика
 */
class JMail extends CApplicationComponent
{
        const TYPE_TEXT = 0;
        const TYPE_HTML = 1;
        
        public static $activateSubject = 'Подтверждение адреса электронной почты';
        public static $intellectmoneySubject = 'Платеж на сайте jollyday.ru успешно совершен';
        public static $statisticSubject = 'Статистика на jollyday';
        public static $name = 'jollyday';
        public static $nameSupport = 'Служба поддержки Jollyday.ru';

        
       /**
         * Метод шлёт сформированное из параметров письмо
         * 
         * Если письмо полностью сформированно т.е. нет файла представление
         * необходимо выставить значения $viewfile="" и в $data необходимо передать значение string т.е. сформированный текст сообщения
         * в противном случае неообходимо в $data передать массив используемых переменных в представлении $viewfile
         * 
         * @param string $sender адрес отправителя
         * @param string $reciever адрес получаетля
         * @param string $subject тема
         * @param string $viewfile файл представления
         * @param array or string $data данные для файла представления
         * @param string $name_sender имя отправителя
         * @param integer $body_type формат письма
         * @param type $attachments вложения
         * @return boolean результат отправки
         */
        public static function sendCustomMail($mail_sender,$reciever,$subject,$viewfile="",$data = array(),$name_sender='jollyday',$body_type= self::TYPE_HTML,$attachments = '')
        {

                $message = new YiiMailMessage;

                if($viewfile!="")
                        $message->view = $viewfile;

                $message->setBody($data,$body_type==self::TYPE_HTML? 'text/html':' text/plain','utf-8');
                $message->addTo($reciever);
                $message->setSubject($subject);
                $message->setFrom(array($mail_sender => $name_sender));

                return Yii::app()->mail->send($message);

        }
        
       /**
        * Метод шлёт сформированное из параметров письмо без view
        * @param string $mail_sender адрес отправителя
        * @param string $mail_reciever адрес получаетля
        * @param string $subject тема
        * @param array or string $body письмо
        * @param string $name_sender имя отправителя
        * 
        * @return boolean результат отправки
        */
        public static function sendMail($mail_sender,$mail_reciever,$subject,$body,$name_sender=null)
        {
                if(empty($name_sender))
                    $name_sender = self::$name;
                $from = array($mail_sender => $name_sender);
                return Yii::app()->mail->sendSimple($from, $mail_reciever, $subject, $body);
        }

         /**
         * Метод отправляет письмо статистики
         * 
         * @param array $tables ID array([0]=>array('email'=>$mail_reciever,'data'=>$data))
         */
        public static function statisticMail($tables)
        {
                $deliveryMail = Yii::app()->params['mail']['no-reply']['address'];
                $subject= self::$statisticSubject;
                $nameFrom = self::$name;
                if(isset(Yii::app()->controller))
                    $controller = Yii::app()->controller;
                else
                    $controller = new CController('YiiMail');
                $sql = 'INSERT INTO mail (mail_from, mail_to, name_from, subject, body) VALUES';
                foreach ($tables as $row){
                    $body = $controller->renderInternal(WEBROOT.'/themes/mail/message_stat.php',$row['data'],true);
                    $email =$row['email'];
                    $sqls[]= " ('$deliveryMail', '$email', '$nameFrom', '$subject', '$body')";
                }
                $sql .= implode(',',$sqls);
                Yii::app()->db->createCommand($sql)->query();
        }

        /**
         * Метод отправляет письмо для активации почтового ящика
         * 
         * @param integer $userId ID пользователя, владеющего почтовым ящиком
         * @param string $code Код подтверждения
         * @param string $reciever почтовый адрес получателя письма (пользователя)
         */
        public static function activateMail($userID, $code, $email)
        {
               
                $deliveryMail = Yii::app()->params['mail']['no-reply']['address'];
                $user = Yii::app()->db->createCommand()
                        ->select('name')
                        ->from('user')
                        ->where('id ='.$userID)
                        ->queryRow();
                $body = Yii::app()->controller->renderPartial('webroot.themes.mail.message_activate', array(
                        'userID' => $userID,
                        'user'=>$user['name'],
                        'code' => $code,
                        'subject' => self::$activateSubject,
                ), true);
                $subject=self::$activateSubject;
                $nameFrom = self::$name;
                $bodyType = self::TYPE_HTML;
                $priority = 0;
                self::insertInTable($deliveryMail, $nameFrom, $email,$subject , $bodyType, $body, $priority);
                
        }  
        
        /**
         * Метод отправляет письмо информации о intellact money
         * 
         * @param string $email email пользователя
         * @param date $date дата отправеи
         * @param int $amount сумма пополнения баланса в рублях
         * @param string $serviceName имя оперции
         * @param int $coin сумма пополнения баланса в монетах
         * @param string $orderId номер действия
         * @param string $user имя пользователя
         * @return boolean результат отправки письма
         */
        public static function intellectMoneyMail($email, $date,$amount,$serviceName,$coin,$orderId,$user)
        {
                
                $deliveryMail = Yii::app()->params['mail']['payment']['address'];
                $subject=self::$intellectmoneySubject;
                $body = Yii::app()->controller->renderPartial('webroot.themes.mail.intellectmoney_info', array(
                        'subject'=>$subject,
                        'date' => $date,
                        'amount' => $amount,
                        'serviceName'=>$serviceName,
                        'coin'=>$coin,
                        'user'=>$user,
                        'orderId'=>$orderId,
                ), true);
                $nameFrom = self::$name;
                $bodyType = self::TYPE_HTML;
                $priority = 0;
                self::insertInTable($deliveryMail, $nameFrom, $email,$subject , $bodyType, $body, $priority);
                
        }
        
        /**
         * Метод отправляет письмо с ответом от техподдержки
         * 
         * @param string $mailTo адрес получателя
         * @param string $answer текст ответа
         * @param string $subject тема письма
         */
        public static function supportMail($mailTo, $answer, $subject)
        {
                $body = Yii::app()->controller->renderPartial('webroot.themes.mail.message_support_answer', array(
                        'answer' => $answer,
                        'subject' => $subject,
                ), true);
                
                $mailFrom = Yii::app()->params['mail']['support']['address'];
                $nameFrom = self::$nameSupport;
                $bodyType = self::TYPE_HTML;
                $priority = 0;
                
                self::insertInTable($mailFrom, $nameFrom, $mailTo, $subject, $bodyType, $body, $priority);
        }
        
        /**
         * Метод записи письма в базу данных
         * 
         * @param string $mail_from email отправителя
         * @param string $name_from имя отправителя
         * @param string $mail_to email получателя
         * @param string $subject тема письма
         * @param integer $body_type тип отправки
         * @param string $body сообщение
         * @param integer $priority приоритет
         * @return boolean результат записи письма
         */
        public static function insertInTable($mail_from=null, $name_from = "jollyday", $mail_to, $subject, $body_type = null, $body, $priority=0)
        {
                if(empty($mail_from))
                        $mail_from = Yii::app()->params['mail']['no-reply']['address'];
                
                if(empty($body_type))
                        $body_type = self::TYPE_TEXT;
            
                return Yii::app()->db->createCommand()->insert("mail", array(
                       'mail_from' => $mail_from, 
                       'mail_to' => $mail_to,
                       'name_from' => $name_from,
                       'subject' => $subject, 
                       'body_type' => $body_type, 
                       'body' => $body,
                       'priority' => $priority
                ));
        }
        
        /**
         * Метод получение данных из таблицы
         * 
         * @param integer $count кол-во возращаемых записей
         * @return array результат записи письма
         */
        public static function getFromTable($count)
        {
                 return Yii::app()->db->createCommand()
                        ->select('*')
                        ->from('mail')
                         ->where('fl_process=0')
                        ->order('priority')
                        ->limit($count)
                        ->queryAll();
        }
        
        /**
         * Метод помечает письмо в базе данных, как находящееся в отправке
         * @param integer $id Ид письма
         */
        public static function setProcessStatus($mails)
        {
                if(!empty($mails))
                {
                        $ids = array();
                    
                        foreach ($mails as $mail)
                                $ids[] = $mail['id'];
                        
                        $commaSeparatedIds = implode(',', $ids);
                    
                        if(!empty($ids))
                        {
                                $sql = "UPDATE mail SET fl_process = 1 WHERE id IN ($commaSeparatedIds)";
                                return Yii::app()->db->createCommand($sql)->query();
                        }
                        else
                                return false;                  
                }
        }
}