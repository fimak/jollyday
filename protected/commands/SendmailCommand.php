<?php

/**
 * Команда для отправки писем из таблицы по CRON
 *
 * @author gbespyatykh
 */
class SendmailCommand extends CConsoleCommand
{
        public function actionIndex($count = 100)
        {
   
                $mails = JMail::getFromTable($count);
                
                if(empty($mails))
                        return;
     
                JMail::setProcessStatus($mails);
                
                foreach ($mails as $mail)
                {
                    if(!$mail['mail_from'] || !$mail['mail_to'] || !$mail['subject'])
                    {
                            Yii::app()->db->createCommand()->delete("mail",'id = :idmail', array('idmail' => $mail['id']));
                            continue;
                    }
                    
                    if(JMail::sendMail($mail['mail_from'], $mail['mail_to'],$mail['subject'], $mail['body'], $mail['name_from']))
                    {
                            Yii::app()->db->createCommand()->delete("mail",'id = :idmail', array('idmail' => $mail['id']));
                            echo "\n Successfull sending email to " . $mail['mail_to'];         
                    }
                    else
                            echo "\n Error sending email to " . $mail['mail_to']; 
                }

        }
}

?>
