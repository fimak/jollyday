<?php
/**
 * Контроллер подарков
 */
class GiftController extends JAppController
{
        /**
         * В методе описаны фильтры контроллера
         * 
         * @return array массив с описанием фильтров
         */
        public function filters()
        {
                return array(
                    'ajaxOnly 
                            + LoadGifts
                            + LoadForm
                            + Process
                            + LoadUserGifts
                    ',
                );
        }
        /**
         * Вывод списка подарков которые можно подарить
         * 
         * @param type $id
         */
        public function actionLoadGifts($uid = null)
        {       
                $user = User::getBaseInfo($uid);
                          
                if($user === null)
                        throw new CHttpException('404', 'Страница не существует'); 


                $data = Gift::getList();
                $model = new GiftForm;
                $isChoiceFix = false;
                
                if(isset($_POST['GiftForm']))
                {
                        $model->attributes = $_POST['GiftForm'];
                        $isChoiceFix = true;
                }
                
                $model->id_reciever = $uid;

                $costs = array_keys($data);
                
                asort($costs);
                
                $this->renderPartial('_list', array(
                    'data'=> $data,
                    'model'=> $model,
                    'costs' => $costs,
                    'account' => Yii::app()->user->getAccount(),
                    'user' => $user,
                    'isChoiceFix' => $isChoiceFix,
                ), false, true);
        }
        
        /**
         * Действие обработки формы для подарка
         */
        public function actionProcess()
        {
                if(isset($_POST['GiftForm']))
                {
                        $model = new GiftForm;
                        $model->attributes = $_POST['GiftForm'];
                        $model->id_sender = Yii::app()->user->id;
                    
                        $result = array();
                        
                        if($model->validate())
                        {                      
                                $gift = Gift::model()->findByPk($model->id_gift);

                                if(!$model->checkAccount($gift->cost))
                                {   
                                        if(JPayment::subMoney($model->id_sender, $gift->cost)  && $model->sendGift())
                                        {
                                                $user = User::model()->with(array('city', 'userpic'))->findByPk($model->id_reciever);                            
                                                $offerStatus = Offer::isUsersInOfferList($model->id_sender, $model->id_reciever);
                                                $userAccount = Yii::app()->user->getAccount();
                                            
                                                $result['status'] = 'success';
                                                $result['gift'] = $this->renderPartial('_gift_single', array(
                                                        'gift' => User::getLastGift($model->id_sender, $model->id_reciever),
                                                        'profileType' => '',
                                                ), true);
                                                $result['html'] = $this->renderPartial('_success', array(
                                                        'gift' => $gift,
                                                        'postcard' => $model->postcard,
                                                        'user' => $user,
                                                        'offerStatus' => $offerStatus,
                                                ), true);
                                                $result['id_user'] = $user->id;
                                                $result['account'] = JPayment::formatAmount($userAccount - $gift->cost);
                                                $result['word_money'] = Yii::t('jolly', 'money', ($userAccount - $gift->cost));
                                        }     
                                }
                                else
                                {
                                        $result['status'] = 'highcost';
                                }                                        
                        }
                        else
                        {
                                $result['status'] = 'error';
                                $result['errors'] = $model->getErrors();
                        }
                          
                        echo CJSON::encode($result);
                }       
        }
        
        public function actionConfirm()
        {
                if(isset($_POST['GiftForm']))
                {
                        $giftForm = new GiftForm;
                        $giftForm->attributes = $_POST['GiftForm'];
                        $giftForm->id_sender = Yii::app()->user->id;
                    
                        $result = array();
                        
                        if($giftForm->validate())
                        {                      
                                $gift = Gift::model()->findByPk($giftForm->id_gift);
                                $user = User::model()->with('userpic')->findByPk($giftForm->id_reciever);
                                
                                $result['status'] = 'success';
                                $result['html'] = $this->renderPartial('_gift_send_confirm', array(
                                        'user' => $user,
                                        'gift' => $gift,
                                        'giftForm' => $giftForm,
                                ), true);
                        }
                        else
                        {
                                $result['status'] = 'error';
                                $result['errors'] = $giftForm->getErrors();
                        }
                          
                        echo CJSON::encode($result);
                }
                else
                        echo CJSON::encode(array('result' => 'error'));
        }
        
              
        /**
         * Метод удаляет открытку к подарку по его ID
         * 
         * @param integer $id ID подарка
         */
        public function actionDeletePostcard($id)
        {
                if(Gift::deletePostcard($id, Yii::app()->user->id))
                {
                        $result = 'success';
                        
                        // удалить можно только собственную открытку, поэтому
                        // сбрасываем кеш подарков текущего пользователя
                        Yii::app()->cache->delete('gifts_'.Yii::app()->user->id);
                }
                else
                        $result = 'error';
                           
                echo CJSON::encode(array('result' => $result));   
        }
}