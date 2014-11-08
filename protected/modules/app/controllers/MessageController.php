<?php
/**
 * Контроллер сообщений
 */
class MessageController extends JAppController
{   
        /**
         * Действие выводит компактные профили по фильтру
         */
	public function actionIndex()
	{                   
                $filter = new MessageFilterForm;
                
                $commandBuilder = Yii::app()->getDb()->getCommandBuilder();
                
                $pageSize = Yii::app()->settings->get('Pagination','profileMessages');
                
                if(isset($_POST['MessageFilterForm']))
                {
                        $filter->attributes = $_POST['MessageFilterForm'];
                        
                        if(Yii::app()->request->isAjaxRequest)
                                $this->layout = '//layouts/clear'; // выводим только результаты
                }
                
                switch($filter->type)
                {
                        default :
                        case 'offers' :
                        case 'correspondence' :
                        case 'all' : 
                                // строим критерий
                                $criteria = $filter->buildMetaMessageCriteria(Yii::app()->user->id);
                                           
                                // настриваем бесконечный пейджер
                                $count = $commandBuilder->createCountCommand('message', $criteria)->queryScalar();
                                $pages = new CPagination($count);
                                $pages->pageSize = $pageSize;
                                $pages->applyLimit($criteria);
                                
                                // получаем сообщения по критерию
                                $models = Metamessage::model()->findAll($criteria);
                                                   
                                break;
                        case 'blacklist' : 
                                $criteria = $filter->buildBlacklistCriteria(Yii::app()->user->id);
                            
                                // настраиваем пагинатор
                                $count = $commandBuilder->createCountCommand('blacklist', $criteria)->queryScalar();
                                $pages = new CPagination($count);
                                $pages->pageSize = $pageSize;
                                $pages->applyLimit($criteria);
                             
                                // получаем ID записей из чёрного списка
                                $ids = $commandBuilder->createFindCommand('blacklist', $criteria)->queryColumn();
                                
                                // при использовании AR и передаче в метод поиска массива ID, 
                                // этот метод выбирает записи по ASC порядку, считая ID не числами, а
                                // строками. Для исправления вводим специальный критерий
                                $userCriteria = new CDbCriteria;
                                if($ids)
                                {                 
                                        $idCommaSeparated = implode(',',$ids);
                                        $userCriteria->order = "FIELD(t.id, $idCommaSeparated)";                
                                }
                                // ищем модели
                                $models = User::model()->with(array(
                                        'lastAction',
                                        'userpic',
                                        'city',
                                        'region',
                                        'profile'
                                ))->findAllByPk($ids, $userCriteria);    
                                break;
                }
                              
                $this->render('index', array(
                        'models' => $models,
                        'filter' => $filter,
                        'pages' => $pages,
                        'methodList' => JMeetmethod::getData(),
                        'filterType' => $filter->type,
                        'profileType' => $filter->type == 'blacklist' ? 'blacklist' : 'message'
                ));
	}
        
        /**
         * Действие вывода диалога между пользователями
         * 
         * @param type $id ID пользователя с которым происходит диалог
         */
        public function actionDialog($id)
        {       
                // находим диалог с пользователем с указанным id
                $offer = Offer::model()->find('(id_reciever = :id_reciever AND id_sender = :id_sender) OR (id_reciever = :id_sender AND id_sender = :id_reciever)', array(
                        ':id_reciever' => Yii::app()->user->id,
                        ':id_sender' => $id                    
                ));
                
                if($offer->interlocutor->fl_deleted == 1)
                        throw new CHttpException('404', 'Страница не существует');
                
                // модель формы чата
                $chatForm = new Chat;
                $chatForm->recieverID = $id;
                
                if($offer == null)
                        throw new CHttpException('404', 'Страница не существует');
                $this->render('dialog', array(
                        'offer' => $offer,
                        'chatForm' => $chatForm,
                        'photos' => User::getPhotos($offer->interlocutor->id, $offer->interlocutor->id_userpic),
                ));
        }
        
        /**
         * Действие отправки сообщения на сайт
         */
        public function actionSend()
        {         
                if(isset($_POST['Chat']))
                {
                        $form = new Chat;
                        $form->attributes = $_POST['Chat']; 
                    
                        if(!$form->validate())
                                return false;
                    
                        // получаем необходимые данные
                        $senderID = Yii::app()->user->id;
                        $recieverID = $form->recieverID;
                        $text = $form->text;
                        
                        // нельзя переписываться пользователям из чёрного списка
                        if(Blacklist::getBlacklistStatus($senderID, $recieverID) > Blacklist::STATUS_NO)
                                throw new CHttpException('404', 'Пользователи в чёрном списке!');
                                               
                        // предложение должно существовать
                        if(!$offer = Offer::getOfferData($senderID, $recieverID))
                                throw new CHttpException('404','Страница не существует');
                        
                        // пробуем добавить сообщение в базу                      
                        $result = Message::add($senderID, $recieverID, $offer['id'], $text) ? 'success' : 'error';
                       
                        echo CJSON::encode(array('result' => $result));
                }          
        }
        
        /**
         * Действие подгрузки новых сообщений в чат.
         * 
         * @param integer $lid ID последнего сообщения в чате
         * @param type $rid ID собеседника
         */
        public function actionLoad($lid = 0, $rid)
        {
                $offerID = Offer::getOfferId(Yii::app()->user->id, $rid);
                
                if(!$offerID)
                        throw new CHttpException('403','Запрещено');
                
                // получаем сообщения   
                $messages = Message::getChatMessages($offerID, $lid);

                // массив для ID сообщений
                $messageIds = array();
                
                // кодируем HTML-сущности в сообщениях перед выводом, дабы избежать херни,
                // локализуем время.
                foreach($messages as $key => $value)    
                {
                        $messages[$key]['sender_name'] = CHtml::encode($messages[$key]['sender_name']);
                        $messages[$key]['timestamp'] = $messages[$key]['date'];
                        $messages[$key]['date'] = J::ago($messages[$key]['date']);     
                        $messages[$key]['text'] = Yii::app()->format->formatText($messages[$key]['text']);
                        $messageIds[] = $messages[$key]['id'];
                }
                
                // помечаем сообщения как прочитаннные
                Metamessage::markAsRead($rid);
                
                echo CJSON::encode($messages);
        }
        
        /**
         * Действие вывода уведомлений
         */
        public function actionNews()
        {
                // строим критерий
                $criteria = new CDbCriteria;
                $criteria->select = '
                        news_template.title AS t_title, 
                        news_template.text AS t_text, 
                        news_template.image AS t_image, 
                        t.date AS date, 
                        t.status AS status, 
                        t.id AS id,
                        t.type AS type,
                        t.title AS title,
                        t.text AS text,
                        t.std_image AS image
                ';
                        
                $criteria->compare('t.id_user', Yii::app()->user->id);
                $criteria->join = 'LEFT JOIN news_template news_template ON t.id_news = news_template.id' ;
                $criteria->order = 't.date DESC';
                
                $commandBuilder = Yii::app()->getDb()->getCommandBuilder();
                      
                // настриваем бесконечный пейджер
                $count = $commandBuilder->createCountCommand('im_user_news', $criteria)->queryScalar();
                
                // если подгрузка с помощью AJAX, то выставляем соответствующий
                // лейаут (для бесконечной пагинации)
                if(Yii::app()->request->isAjaxRequest)
                        $this->layout = '//layouts/clear';
                
                // определяем страницу для пагинатора
                $pages = new CPagination($count);  
                $pages->pageSize = Yii::app()->settings->get('Pagination','news');
                $pages->applyLimit($criteria);
                
                // ищем новости по критерию (в виде массива)
                $news = $commandBuilder->createFindCommand('im_user_news', $criteria)->queryAll();
                
                News::process($news);
                           
                // помечаем полученные сообщения как прочитанные
                News::markAsRead();
                
                $this->render('news', array(
                        'news' => $news,
                        'pages' => $pages,
                ));   
        }


        /**
        * Метод загружает модель предложения
        * 
        * @param integer $id ID предложения
        * @return Offer Модель предложения
        * @throws CHttpException 404, Страница не существует
        */
        private function loadOffer($id)
        {
		$model = Offer::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'Страница не существует.');
		return $model;            
        }
        
        /**
         * Событие выполняемое перед запуском действия
         * 
         * @param CAction $action
         * @return boolean
         */
        public function beforeAction($action)
        {
                // ставим дату последнего посещения у пользователя 
                //if(in_array($action->id, array('index', 'dialog', 'news')))
                        //Yii::app()->user->setActionDate();
                                             
                return parent::beforeAction($action);
        }
}