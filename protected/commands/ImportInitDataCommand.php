<?php

/**
 * Description of DumpInitdataCommand
 *
 * @author gbespyatykh
 */
class ImportInitDataCommand extends CConsoleCommand
{
        /**
         * Развёртывание проекта
         */
        public function actionIndex()
        {
               // определяем все необходимые пути
               $dump =  Yii::app()->getBasePath() . DS . 'data' . DS . 'initdata' . DS . 'dump.sql';
               $images = Yii::app()->getBasePath() . DS . 'data' . DS . 'initdata' . DS . 'images.zip';
               $imagesOutputFolder = WEBROOT . DS .'images' . DS;
                          
               // импортируем данные базы
               $this->importDump($dump);
               
               // распаковываем архив с фото
               Yii::app()->zip->extractZip($images, $imagesOutputFolder);
  
               $sharkeevPhotos = Yii::app()->getBasePath() . DS . 'data' . DS . 'initdata' . DS . 'sharkeev_photo.zip';
               $sharkeevPhotosOutputFolder = $imagesOutputFolder = WEBROOT . DS .'photo' . DS;
               Yii::app()->zip->extractZip($sharkeevPhotos, $sharkeevPhotosOutputFolder);
               
               echo "\n]  Filling the site with initial data was successfully completed";
               echo "\n"; 
               return 1;
        }
        
        /**
         * Импорт дампа БД для развёртывания
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
}

?>

