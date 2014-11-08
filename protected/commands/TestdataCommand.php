<?php

/*
 * Консольная команда очистки фотографий
 */
class TestdataCommand extends CConsoleCommand
{        
        /**
         * Команда работы очистки базы от пользовательских данных
         */
        public function actionClear()
        {                   
                echo "\n";              
                
                // очищаем таблицы записей о попытках регистрации и восстановления пароля
                Yii::app()->db->createCommand()->truncateTable('attempt_recovery');
                Yii::app()->db->createCommand()->truncateTable('attempt_register');
                
                // очищаем таблицы изменения пользовательских данных
                Yii::app()->db->createCommand()->truncateTable('new_email');
                Yii::app()->db->createCommand()->truncateTable('new_user');
                Yii::app()->db->createCommand()->truncateTable('new_phone');
                Yii::app()->db->createCommand()->truncateTable('history');
                
                // удаляем анкеты пользователей
                Yii::app()->db->createCommand()->truncateTable('profile');
                Yii::app()->db->createCommand()->truncateTable('im_profile_ihave');
                Yii::app()->db->createCommand()->truncateTable('im_profile_target');
                
                // удаляем данные обратной связи
                Yii::app()->db->createCommand()->truncateTable('feedback');
                Yii::app()->db->createCommand()->truncateTable('spam');
                
                // удаляем предложения и чёрный список
                Yii::app()->db->createCommand()->truncateTable('offer');
                Yii::app()->db->createCommand()->truncateTable('blacklist');
                Yii::app()->db->createCommand()->truncateTable('blacklist_toprated');
                
                // удаляем подарки
                Yii::app()->db->createCommand()->truncateTable('im_user_gift');
                
                // удаляем новости
                Yii::app()->db->createCommand()->truncateTable('im_user_news');
                
                // удаляем фотографии пользователей
                Yii::app()->db->createCommand()->truncateTable('photo');  
                
                // удаляем переписку
                Yii::app()->db->createCommand()->truncateTable('message');
                
                // удаляем пользователей и связанные данные
                Yii::app()->db->createCommand()->truncateTable('im_user_meetmethod');
                Yii::app()->db->createCommand()->truncateTable('_action');
                
                // удаляем данные платёжных систем
                Yii::app()->db->createCommand()->truncateTable('pay_intellectmoney');
                Yii::app()->db->createCommand()->truncateTable('pay_sms_code');
                
                Yii::app()->db->createCommand()->truncateTable('sms_log');
                
                //Yii::app()->db->createCommand()->truncateTable('bonus');
                Yii::app()->db->createCommand()->truncateTable('mail');
                      
                $count = Yii::app()->db->createCommand()->delete(
                        'user',
                        'role <> :userRole',
                        array(
                                'userRole' => User::ROLE_ADMIN
                        )
                );
                echo "\n]  Database is cleared";
                
                // удаляем файлы (фото)
                Yii::app()->file->purge(Yii::app()->getBasePath().DS.'..'.DS.Photo::UPLOAD_FOLDER);
                echo "\n]  User files removed";
                
                // сбрасываем кеш
                Yii::app()->cache->flush();
                echo "\n]  Cache flushed";
                
                // чистим папку runtime (кеш в ней же)
                Yii::app()->file->purge(Yii::app()->runtimePath);
                echo "\n]  Runtime folder is clear";
                
                // чистим папку assets
                Yii::app()->file->purge(WEBROOT . DS . 'assets'); 
                echo "\n]  Assets folder is clear";
                
                echo "\n]  Database clean up of userdata complete : {$count} users was deleted";
                echo "\n";        
                return 1;
        }
        
        /**
         * Команда заполнения сайта тестовыми пользовательски данными (база и фото)
         */
        public function actionFill()
        {
               // определяем все необходимые пути
               $dump =  Yii::app()->getBasePath() . DS . 'data' . DS . 'testdata' . DS . 'dump.sql';
               $photos = Yii::app()->getBasePath() . DS . 'data' . DS . 'testdata' . DS . 'photo.zip';
               $photosOutputFolder = WEBROOT . DS .'photo' . DS;
               
               // импортируем данные базы
               $this->importDump($dump);
               echo "\n]  The database filled with testdata";
               
               // распаковываем архив с фото
               Yii::app()->zip->extractZip($photos, $photosOutputFolder);
               echo "\n]  Testdata photos extracted";
               
               echo "\n]  Filling the site with test data was successfully completed";
               echo "\n"; 
               return 1;
        }
         
        /**
         * Метод, реализующий импорт БД из файла
         * 
         * @param string $path путь до файла с дампом БД
         */
        private function importDump($path)
        {
                $dump=file_get_contents($path);
                $query='';
                $state=0;
                for($i=0;$i<strlen($dump);$i++)
                {
                        switch($dump{$i})
                        {
                                case '"':
                                        if($state==0) 
                                                $state=1;
                                        elseif($state==1) 
                                                $state=0;
                                        break;
                                case "'":
                                        if($state==0) 
                                                $state=2;
                                        elseif($state==2) 
                                                $state=0;
                                        break;
                                case "`":
                                        if($state==0) 
                                                $state=3;
                                        elseif($state==3) 
                                                $state=0;
                                        break;
                                case ";":
                                    if($state==0) 
                                    {
                                            Yii::app()->db->createCommand($query)->execute();
                                            $query='';
                                            $state=4;
                                    }
                                    break;
                        }
                        if($state==4) 
                                $state=0;
                        else 
                                $query .= $dump{$i};
                }
        }
        
        /**
         * Команда приведения тестовых данных к первоначальному состоянию.
         */
        public function actionRefresh()
        {
                $this->actionClear();
                $this->actionFill();
                echo "\n\n]  Site testdata successfully refreshed\n";
                echo "\n"; 
        }
        
        /**
         * Команда выставляет всем пользователям указанное количество монет
         */
        public function actionMoney($amount = 0)
        {
                $result = Yii::app()->db->createCommand()->update('user', array(
                        'account' => $amount
                ));       
                
                if($result)
                        echo "\n\n]  Account of each of user is $amount";
                else
                        echo "\n\n]  No accounts updated\n";
                
                echo "\n";
                
                return 1;
        }
}

?>

                        

