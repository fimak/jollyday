<?php
/**
 * Базовый контроллер приложения
 */
class JController extends CController
{          
        /**
         * Метод выполняет AJAX-валидацию
         * 
         * @param CActiveRecord $model модель 
         * @param string $formName ID формы
         */
	protected function performAjaxValidation($model, $formName, $scenario = false, $ajaxVar = 'ajax')
	{
		if(isset($_POST[$ajaxVar]) && $_POST[$ajaxVar] === $formName)
		{       
                        if($scenario)
                                $model->setScenario($scenario);
                                              
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
        /**
         * Метод получает ошибки валидации модели в формате JSON
         * 
         * @param CModel $model
         * @return string JSON-массив, содержащий ошибки валидации сайта
         */
        protected function getValidationErrors($model)
        {
                // выводим ошибки валидации
                $result = array();
                foreach($model->getErrors() as $attribute => $errors)
                        $result[CHtml::activeId($model,$attribute)] = $errors;

                return CJSON::encode($result);
        }


        /**
         * Метод выводит на страницу javascript с редиректом на указанную страницу
         * 
         * @param string $url целевой URL
         * @param boolean $isOnlyAjax выполнять только ajax-запросы
         */
        public static function jsRedirect($url, $isOnlyAjax = true)
        {
                if(!$isOnlyAjax || ($isOnlyAjax && Yii::app()->request->isAjaxRequest))
                                echo '<script>window.location.href = '.CJavaScript::encode($url).'</script>';
                        
                Yii::app()->end();
        }
}