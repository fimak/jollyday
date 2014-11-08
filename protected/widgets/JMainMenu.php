<?php

// делаем иморт класса, т.к. zii не импортируется автоматически
Yii::import('zii.widgets.CMenu');

/**
 * Виджет главного меню
 */
class JMainMenu extends CMenu
{
        /**
         * @var integer счётчик новых сообщений
         */
        private $newMessageCount = null;
        
        /**
         * @var integer счётчик новых уведомлений
         */
        private $newNewsCount = null;
        
        /**
         * @var boolean отображать ли только пункт выхода с сайта
         */
        public $onlyLogout = false;
        
        /**
         * @var boolean отображать ли счётчики
         */
        public $enableCounters = true;
            
	/**
         * Инициализация виджета
         */
        public function init()
        {            
                if(!$this->onlyLogout && $this->enableCounters)
                {
                        // получаем количество новых сообщений пользователяsss
                        if(!Yii::app()->user->isGuest)
                                $this->newMessageCount = Yii::app()->db->createCommand()
                                        ->select('COUNT(*)')
                                        ->from('message')
                                        ->where('id_reciever = :userID AND status = :statusID', array(
                                                'userID' => Yii::app()->user->id,
                                                'statusID' => Message::STATUS_UNREAD
                                        ))
                                        ->queryScalar();

                        // получаем количество новых уведомлений пользователя
                        if(!Yii::app()->user->isGuest)
                                $this->newNewsCount = Yii::app()->db->createCommand()
                                        ->select('COUNT(*)')
                                        ->from('im_user_news')
                                        ->where('id_user = :userID AND status = :statusID', array(
                                                'userID' => Yii::app()->user->id,
                                                'statusID' => News::STATUS_UNREAD
                                        ))
                                        ->queryScalar();
                }                                  
                parent::init();
        }
        
        /**
         * Запуск виджета
         */
	public function run()
	{
                // если выставлена опция, то отображаем в меню только пункт выхода
                if($this->onlyLogout)
                        $this->items = array(
                                array('label'=>'Выход','url'=>array('/app/profile/logout'), 'active' => false)
                        );
            
		$this->renderMenu($this->items);
	}

        /**
	 * Renders the content of a menu item.
	 * Note that the container and the sub-menus are not rendered here.
	 * @param array $item the menu item to be rendered. Please see {@link items} on what data might be in the item.
	 * @return string
	 * @since 1.1.6
	 */
	protected function renderMenuItem($item)
	{
                $output = '';
            
		if(isset($item['url']))
		{
			$label=$this->linkLabelWrapper===null ? $item['label'] : '<'.$this->linkLabelWrapper.'>'.$item['label'].'</'.$this->linkLabelWrapper.'>';
			$output .= CHtml::link($label,$item['url'],isset($item['linkOptions']) ? $item['linkOptions'] : array());
		}
		else
			$output .= CHtml::tag('span',isset($item['linkOptions']) ? $item['linkOptions'] : array(), $item['label']);
                
                // отрисовываем пункт со счётчиком, если есть
                if(isset($item['counter']))
                {
                        if($this->{$item['counter']['countAttribute']} > 0)
                        {
                                $output .= $item['counter']['separator'];
                                $output .= CHtml::tag($item['counter']['container'], $item['counter']['htmlOptions'], $this->{$item['counter']['countAttribute']});
                        }
                }
                
                return $output;
	}        
}

?>
