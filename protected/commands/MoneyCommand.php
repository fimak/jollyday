<?php

/*
 * Консольная команда управления счётом пользователя
 */

class MoneyCommand extends CConsoleCommand
{        
        /**
         * Команда выставляет всем пользователям указанное количество монет
         */
        public function actionSet($account = 0, $bonus = 0)
        {
                $result = Yii::app()->db->createCommand()->update('user', array(
                        'account' => $account,
                        'account_bonus' => $bonus,
                ));       
                
                if($result)
                        echo "\n\n]  Account of each of user is $account, bonus is $bonus";
                else
                        echo "\n\n]  No accounts updated\n";
                
                echo "\n";
                
                return 1;
        }
        
        /**
         * Команда добавляет пользователям указанное количество монет
         */
        public function actionAdd($amount = 0, $bonusAmount = 0)
        {
                $userIds = Yii::app()->db->createCommand()
                        ->select('id')
                        ->from('user')
                        ->where('role = :userRole', array('userRole' => User::ROLE_USER))
                        ->queryColumn();
                
                foreach($userIds as $id)
                        JPayment::addMoney($id, $amount, $bonusAmount);
                
                $count = count($userIds);
                
                echo "\n\n]  $amount coins and $bonusAmount bonus coins are added to $count users";
                
                echo "\n";
                
                return 1;
        }
        
        /**
         * Команда вычитает со счетов пользователя указаннное количество монет
         */
        public function actionSub($amount = 0)
        {
                $userIds = Yii::app()->db->createCommand()
                        ->select('id')
                        ->from('user')
                        ->where('role = :userRole', array('userRole' => User::ROLE_USER))
                        ->queryColumn();
                
                foreach($userIds as $id)
                        JPayment::subMoney($id, $amount);
                
                $count = count($userIds);
                
                echo "\n\n]  $amount coins are subtracted from $count users";
                
                echo "\n";
                
                return 1;
        }
}

?>

                        

