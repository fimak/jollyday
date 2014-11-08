<?php
/**
 * Класс модуля настроек сайта
 */
class StatisticsModule extends CWebModule
{
        /**
         * @var string ID контроллёра по умолчанию
         */
        public $defaultController = 'default';    
    
        /**
         * Инициализация модуля
         */
	public function init()
	{
		$this->setImport(array(
			'statistics.models.*',
			'statistics.components.*',
                        'statistics.controllers.*',
		));
	}
}
