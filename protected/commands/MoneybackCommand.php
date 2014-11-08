<?php

/**
 * Команада для начисления пользователям бонусынх монет по бонусной программе
 *
 * @author gbespyatykh
 */
class MoneybackCommand extends CConsoleCommand
{
        public function actionIndex()
        {
                $now = new DateTime();
            
                $weekAgo = $now->sub(new DateInterval('P1W'))->format('Y-m-d H:i:s');
            
                $data = Yii::app()->db->createCommand()
                        ->select('id_user')
                        ->from('bonus_money_return')
                        ->where('date <= :weekAgo', array('weekAgo' => $weekAgo))
                        ->queryColumn();
                
                if(!empty($data))
                {
                        foreach($data as $id)
                        {
                                // подсчитываем подтверждённые предложения
                                $acceptedOffers = Yii::app()->db->createCommand()
                                        ->select('COUNT(*)')
                                        ->from('offer')
                                        ->where('(id_sender = :userID || id_reciever = :userID) AND status = :offerStatus', array(
                                                'userID' => $id,
                                                'offerStatus' => Offer::ACCEPTED
                                        ))
                                        ->queryScalar();
                                
                                // если меньше 10, то начисляем бонус и удаляем запись
                                if($acceptedOffers < 10)
                                {
                                        JPayment::addMoney($id, 100);
                                        
                                        Yii::app()->db->createCommand()
                                                ->delete('bonus_money_return', 'id_user = :userID', array('userID' => $id));         
                                }
                        }
                }
        }
}

?>
