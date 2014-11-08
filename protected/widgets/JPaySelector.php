<?php

/**
 * Виджет для выбора способа оплаты (хитрожопый)
 *
 * @author hash
 */
class JPaySelector extends CWidget
{
        /**
         * @var string имя контейнера листа
         */
        public $name;
    
        /**
         * @var string тип платёжной операции
         */
        public $operation;
        
        /**
         * @var array данные для листа ('ссылка на обработчик' => 'Описание')
         */
        public $data = array();
        
        /**
         * @var string HTML-опции листа
         */
        public $radioButtonListOptions = array();
        
        /**
         * @var string ID кнопки обработки формы
         */
        public $submitButtonID;
        
        /**
         * @var string атрибут кнопки, содержащий ссылку на обработчик
         */
        public $submitLinkAttribute;
        
        /**
         * @var string ссылка на обработчик по-умолчанию (если ни одно из значений не выбрано)
         */
        public $defaultSubmitLink;
        
        /**
         * @var boolean выбирать ли по-умолчанию первый пункт листа
         */
        public $isDefaultSelected;
        
        /**
         * Инициализация виджета
         */
        public function init()
        {
                parent::init();
                
                // ссылка на обработчик по-умолчанию записывается в каждую радиокнопку
                $this->radioButtonListOptions['data-default-link'] = $this->defaultSubmitLink;
                
                $this->registerScript();
        }
        
        /**
         * Запуск виджета
         */
        public function run()
        {
                echo CHtml::radioButtonList($this->name, false, $this->data, $this->radioButtonListOptions);
        }
        
        /**
         * Регистрация скриптов виджета
         */
        private function registerScript()
        {           
                // скрипт подменяет урл обработчика формы в кнопке сабмита в зависимости
                // от выбранного значения листа. Также ставит метку в кнопку (data-payment), что
                // скрипт была произведена замена ссылки (значение 1).
                // если ни одно значение листа не выбрано, то ставится ссылка по-умолчанию
                // и метка получает значение 0
                // в зависимости от метки обработчик формы должен выпонить необходимые действия
                $script = <<<EOD
$(document).on('change', '#$this->name :radio', function(){
    if($('#$this->name :radio:checked').length == 0){
        $('#$this->submitButtonID').attr('$this->submitLinkAttribute', $('#$this->name :radio:first').data('default-link'));
        $('#$this->submitButtonID').attr('data-payment', 0);
        return;
    }

    $('#$this->submitButtonID').attr('$this->submitLinkAttribute', $(this).val());
    $('#$this->submitButtonID').attr('data-payment', 1);
});
EOD;
            
                Yii::app()->clientScript->registerCoreScript('jquery');
                Yii::app()->clientScript->registerScript('jpayselector', $script, CClientScript::POS_READY);
                
                // если выставлена соответствующая опция виджета, то выбираем первый пункт списка
                if($this->isDefaultSelected)
                        Yii::app()->clientScript->registerScript('jpayselector-init',"
                            $('#$this->name :radio:first').trigger('click');
                        ", CClientScript::POS_READY);
        }
}