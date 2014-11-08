<?php


/**
 * Команда для сбора суточной статистики и отпарки по пользователям.
 *
 */
class DailystatCommand extends CConsoleCommand
{
        /**
         * Действие рассылки статистики
         * 
         * @param integer $limit количество пользователей, которым за раз отправляется статистика
         */
        public function actionIndex($limit = 10)
        {
            $last_user = Yii::app()->settings->get('system','id_last_user', 0);

            if($last_user > 1)
            {
                $date = new DateTime(date('Y-m-d H:i:s'));
                $date->modify('- 24 hour');
                $date = $date->format('Y-m-d H:i:s');
                
                $sql = "SELECT user.id, user.email, user.name,
                        (SELECT COUNT(im_user_gift.id) 
                            FROM im_user_gift 
                            WHERE im_user_gift.id_reciever = user.id
                                AND im_user_gift.date > STR_TO_DATE('$date','%Y-%m-%d %H:%i:%s')
                        ) AS count_gift,
                        (SELECT COUNT(message.id) 
                            FROM message 
                            WHERE message.id_reciever = user.id 
                                AND message.status = 0
                                AND message.date > STR_TO_DATE('$date','%Y-%m-%d %H:%i:%s')
                            ) AS count_message,
                        (SELECT COUNT(offer.id) 
                            FROM offer 
                            WHERE offer.id_reciever = user.id 
                                AND offer.status = 0
                                AND offer.date_offer > STR_TO_DATE('$date','%Y-%m-%d %H:%i:%s')
                            ) AS count_offer
                    FROM user
                    WHERE user.email != '' AND 
                            user.id < $last_user 
                            AND register_step = 0 
                            AND fl_deleted = 0 
                            AND role = 'user'
                    ORDER BY user.id DESC
                    LIMIT $limit" ;

                $users = Yii::app()->db->createCommand($sql)->queryAll();
                         
                foreach ($users as $user)
                {     
                        if($user['count_message'] || $user['count_offer'] || $user['count_gift'])
                        {
                            if($user['count_message']){
                                    $sql= "SELECT user.id,user.name,user.birthday, city.name AS sity,photo.filename_medium AS photo FROM message 
                                            LEFT JOIN user ON message.id_sender = user.id
                                            LEFT JOIN city ON user.id_city = city.id
                                            LEFT JOIN photo ON user.id_userpic = photo.id
                                            WHERE message.id_reciever = 3
                                            AND message.status = 0
                                            AND message.date > STR_TO_DATE('$date','%Y-%m-%d %H:%i:%s')
                                            ORDER BY  message.date DESC LIMIT 2";
                                    $message = Yii::app()->db->createCommand($sql)->query();
                            }
                            if($user['count_offer']){
                                    $sql = "SELECT offer.id_method
                                        FROM offer 
                                        WHERE offer.id_reciever = ".$user['id']."
                                            AND offer.status = 0
                                            AND offer.date_offer > STR_TO_DATE('$date','%Y-%m-%d %H:%i:%s')
                                            ORDER BY  offer.date_offer DESC LIMIT 5";
                                    $offer = Yii::app()->db->createCommand($sql)->query();

                            }
                            if($user['count_gift']){
                                    $sql = "SELECT `image` FROM `im_user_gift` 
                                        LEFT JOIN gift ON im_user_gift.id_gift=gift.id
                                        WHERE `id_reciever` = ".$user['id']."
                                        AND im_user_gift.date > STR_TO_DATE('$date','%Y-%m-%d %H:%i:%s')
                                            ORDER BY  im_user_gift.date DESC LIMIT 5";
                                    $gift = Yii::app()->db->createCommand($sql)->query();
                            }
                           
                            $body =  array( 'subject' => JMail::$statisticSubject, 
                                     'user'=>$user['name'],
                                     'new_message'=>$user['count_message'],
                                     'message'=>($user['count_message'])?$message:0,
                                     'new_offers'=>$user['count_offer'],
                                     'offer'=>($user['count_offer'])?$offer:0,
                                     'new_gift'=>$user['count_gift'],
                                     'gift'=>($user['count_gift'])?$gift:0
                            );
                            
                
                            $mailMessages[] = array('email'=>$user['email'], 'data'=>$body); 
                            
                        }

                }
                if(isset($user['id']))
                {
                        Yii::app()->settings->set('system', 'id_last_user', $user['id']);
                        JMail::statisticMail($mailMessages);
                }
                else
                {
                        Yii::app()->settings->set('system', 'id_last_user',  0);
                }
            }
        }
        
        /**
         * Действие сброса последнего id пользователя, для возобновления расылки статистики
         */
        public function actionReset() 
        { 
                $id = Yii::app()->db->createCommand()->select('max(id) AS id')->from('user')->queryScalar();
                Yii::app()->settings->set('system', 'id_last_user', $id);
        }
        
}

?>
