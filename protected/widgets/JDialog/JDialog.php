<?php

/**
 * Виджет чата на странице диалога
 *
 * @author hash
 */
class JDialog extends JWidget
{        
        /**
         * @var integer интервал обновления чата
         */
        public $updateInterval;
              
        /**
         * @var string ID формы чата
         */
        public $formID;
             
        /**
         * @var string селектор контейнера каждого сообщения.
         * 
         * Внутри тега, содержащего данный селектор должны быть объявлены 
         * microdata-атрибуты:
         * - data-message-id: id сообщения
         * - id: вида m123, где 123 это ID сообщения
         */
        public $messageSelector;
                     
        /**
         * @var string ссылка для отправки сообщения
         */
        public $submitUrl;
        
        /**
         * @var string ссылка для загрузки сообщения
         */
        public $loadUrl;
        
        /**
         * @var string функция обратного вызова для обработки AJAX-запроса загрузки сообщений
         */
        public $onLoadMessages = "function(data){}";
        
        /**
         * @var string имя отправителя сообщения
         */
        public $senderName;
        
        /**
         * @var string имя получатетеля сообщения
         */
        public $recieverName;
        
        /**
         * @var string селектор контейнера чата
         */
        public $chatContainerSelector;
        
        /**
         * Инициализация виджета
         */  
        public function init()
        {
                $this->publishAssets();   
        }

        /**
         * Запуск виджета
         */
        public function run()
        {
                $this->registerFormScript();
                $this->registerChatCoreScript();
                $this->registerInitScript();
        }
        
        /**
         * Подключение скриптов виджета
         */
        private function registerChatCoreScript()
        {
                $senderID = Yii::app()->user->id;
            
                // подключение функций чата
                Yii::app()->getClientScript()->registerScript('chat-core', "                  
                    function sendChatMessage(){
                        $('.trChatSendMode').attr('disabled', 'disabled');

                        $.ajax({
                            url : '{$this->submitUrl}',
                            type : 'post',
                            dataType : 'json',
                            data : $('#{$this->formID}').serialize(),
                            success : function(){
                                $('#{$this->formID} textarea').val('').focus();
                                loadChatMessages();
                            },
                            beforeSend: function(){
                                $('#chat-textarea').attr('disabled', 'disabled');
                            },
                            complete: function(){
                                $('.trChatSendMode, #chat-textarea').removeAttr('disabled');
                            }
                        });                                    
                    }

                    function loadChatMessages(){
                        var lastID = $('{$this->messageSelector}').last().data('message-id');
                        var recieverID = $('#{$this->formID} input[type=\"hidden\"]').val();

                        if(lastID == null || lastID == undefined)
                                lastID = 0;

                        if(!load_in_process)
                        {
                            load_in_process = true;

                            $.ajax({
                                type : 'get',
                                dataType : 'json',
                                url : '{$this->loadUrl}',
                                data : {
                                    lid : lastID,
                                    rid : recieverID,
                                },
                                success : {$this->onLoadMessages}
                            });

                            load_in_process = false;
                        }
                    }
                    
                    function renderMessageHead(data)
                    {
                            var html = '';
                            var senderID = {$senderID};
                            var lastClass = $('{$this->messageSelector} .chat-message-username').last().attr('class');
                            var currentClass = 'chat-message-username' + ' ' + (data.sender_id == senderID ? 'color-orange' : 'color-blue');
                            
                            var lastTimestamp = $('.chat-message-text').last().data('timestamp');
                            var curTimestamp = data.timestamp;

                            var dateLast = new Date(lastTimestamp);
                            var dateCur = new Date(curTimestamp);

                            var delta = Math.ceil((dateCur.getTime() - dateLast.getTime()) / 1000);
                       
                            if(lastClass != currentClass || delta > 3600)
                            {
                                    html += '<div class=\'chat-message-header\'>'
                                    html += '<span class=\'chat-message-username ' + (data.sender_id == senderID ? 'color-orange' : 'color-blue') + '\'>' + data.sender_name + '</span>';
                                    html += '<span class=\'chat-message-timestamp\' datetime=\'' + data.date + '\'>' + data.date + '</span>';
                                    html += '</div>';
                            }
                            else
                            {
                                    $('.chat-message-timestamp').last().html(data.date);
                            }
                            
                            return html;
                    }
                    
                    function renderMessageBody(data){
                            var html = '<div class=\'chat-message-text\' data-timestamp=\'' + data.timestamp + '\'>' + data.text + '</div>';
                            
                            return html;
                    }
                    
                ", CClientScript::POS_HEAD);
                
        }
        
        private function registerFormScript()
        {
                // загрузка сообщений при загрузке окна чата
                Yii::app()->getClientScript()->registerScript('chat-form',"
                    $(document).ready(function () {
                        // отправка формы
                        $('#{$this->formID} button').click(function(){
                            if($.trim($('#{$this->formID} textarea').val()).length != 0)
                                sendChatMessage(); 
                            return false;
                        });

                        // ресайз поля ввода
                        $('#{$this->formID} textarea').autoResize({
                            minHeight : 54,
                            maxHeight : 320,
                            extraSpace : 18,
                        });
                        
                        // обработка изменения метода ввода
                        $('#{$this->formID} input:radio').change(function(){
                            $.cookie('chatmode', $(this).val());
                            var currentMode = ($.cookie('chatmode') == 1 ? 1 : 0);
                            $('#chat-send-mode-selected').html(currentMode == 1 ? 'Перевод строки: Ctrl+Enter' : 'Перевод строки: Enter');
                        });

                        // установка начального значения настроек отправки
                        var mode = ($.cookie('chatmode') == 1 ? 1 : 0);
                        $('#{$this->formID} input:radio').filter('[value=' + mode + ']').attr('checked', 'checked').trigger('refresh');
                            
                        $('#chat-send-mode-selected').html(mode == 1 ? 'Перевод строки: Ctrl+Enter' : 'Перевод строки: Enter');
                            
                        // перенос только по нужным клавишам
                        $('#{$this->formID} textarea').keydown(function(eventObject){
                            var isCtrlEnter = (eventObject.ctrlKey) && ((eventObject.keyCode == 0xA)||(eventObject.keyCode == 0xD));
                            var isEnter = (!eventObject.ctrlKey) && ((eventObject.keyCode == 0xA)||(eventObject.keyCode == 0xD));
                            var mode = $('#{$this->formID} input[type=\"radio\"]:checked').val();
                                
                            if((mode == 0 && isCtrlEnter) || (mode == 1 && isEnter)){
                                    sendChatMessage();
                                    return false;
                            }
                            if(mode == 0 && isEnter){
                                    return true;
                            }
                            if(mode == 1 && isCtrlEnter){
                                    $('#{$this->formID} textarea').val($('#{$this->formID} textarea:focus').val() + \"\\n\");
                                    return true;
                            }
                        });
                    });
                ",CClientScript::POS_READY);
        }
        
        private function registerInitScript()
        {
                // загрузка сообщений при загрузке окна чата
                Yii::app()->getClientScript()->registerScript('chat-init',"
                        $(document).ready(function(){  
                            // прокрутка окна чата вниз
                            $('#chat').scrollTop($('{$this->chatContainerSelector}').get(0).scrollHeight);
                            // загрузка сообщений
                            loadChatMessages();
                            // интервал обновления
                            setInterval('loadChatMessages()', {$this->updateInterval});
                        });
                ",CClientScript::POS_READY);
        }
        
        
        private function publishAssets()
        {
		$assets = dirname(__FILE__).'/assets';
		$baseUrl = Yii::app()->assetManager->publish($assets);
                
                Yii::app()->clientScript->registerCoreScript('jquery');
                Yii::app()->clientScript->registerCoreScript('cookie');
                
		if(is_dir($assets))
			Yii::app()->clientScript->registerScriptFile($baseUrl . '/jquery.autoresize.js', CClientScript::POS_HEAD);
                else
			throw new Exception('JDialog - Error: Не найдны ресурсы.');                
        }
}

?>
