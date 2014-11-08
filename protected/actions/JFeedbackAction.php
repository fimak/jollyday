<?php

/**
 * Действие вывода и обработки формы службы поддержки
 *
 * @author gbespyatykh
 */
class JFeedbackAction extends CAction
{
    
        /**
         * Запуск действия
         */
        public function run()
        {
                $model = new Feedback('send');
                
                if(isset($_POST['Feedback']))
                {
                        $model->attributes = $_POST['Feedback'];
                    
                        if($model->save())
                        {
                                Yii::app()->user->setFlash('success', $model->getSuccessMessage());
                                $this->controller->refresh();
                        }                         
                }
                else
                {
                        $model->name = Yii::app()->user->getRealname();
                        $model->email = Yii::app()->user->getEmail();
                }
            
                $this->controller->render('feedback', array(
                        'model' => $model,
                ));
        }
}

?>
