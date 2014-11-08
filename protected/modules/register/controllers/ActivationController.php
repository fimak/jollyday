<?php
/**
 * Контроллер для активации почтового ящика
 *
 * @author hash
 */
class ActivationController extends JRegisterController
{       
        public $layout ='//layouts/activemail';
        /**
         * Действие активации почтового ящика
         * 
         * @param type $code
         * @param type $userid
         * @throws CHttpException
         */
        public function actionMail($code, $userid)
        {
                if(JConfirm::isEmailChecked($userid))
                {
                        if(Yii::app()->user->isGuest)
                                $this->render('activation', array('message' => 'Ваш почтовый ящик уже был активирован ранее'));
                        else
                        {
                                Yii::app()->user->setFlash('success', 'Ваш почтовый ящик уже был активирован ранее');
                                $this->redirect (array('/app/settings/index'));
                        }
                }
                else
                {
                        if(JConfirm::checkEmail($code, $userid))
                        {
                                if(Yii::app()->user->isGuest)
                                        $this->render('activation', array('message' => 'Адрес электронной почты успешно изменён'));
                                else
                                {
                                        Yii::app()->user->setFlash('success', 'Адрес электронной почты успешно изменён');
                                        $this->redirect (array('/app/settings/index'));
                                }
                        }
                        else
                                throw new CHttpException('404','Страница не найдена'); 
                }                
        }        
}

?>
