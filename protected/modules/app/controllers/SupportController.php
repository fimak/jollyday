<?php

/**
 * Контроллер технической поддержки пользователей
 */

class SupportController extends JAppController
{   
	/**
         * В методе описаны подключаемые типовые действия
         * 
	 * @return array массив с настройками действий
	 */
	public function actions()
	{
		return array(
                        'feedback' => 'JFeedbackAction',   
		);
	}
   
        public function actionHowtomeet()
        {
                $this->renderPartial('_how_to_meet');
        }
}
