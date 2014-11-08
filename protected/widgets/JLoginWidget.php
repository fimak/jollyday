<?php
/**
 * Класс виджета формы входа на сайт
 */
class JLoginWidget extends CWidget
{
        /** 
         * @var LoginForm модель формы входа 
         */
        public $formModel;
        
        /** 
         * @var string селектор кнопки обработки формы 
         */
        public $submitButtonSelector;
    
	/**
         * Инициализация виджета
         * 
         * @throws CException
         */
        public function init() 
        {
                parent::init();
            
                // проверка модели формы
                if(!$this->formModel instanceof LoginForm || $this->formModel === null)
                        throw new CException('JLoginWidget: в параметр "formModel" должна быть передана модель LoginForm');
            
                // id полей ввода формы
                $phoneInputID = CHtml::activeId($this->formModel, 'phone');
                $passwordInputID = CHtml::activeId($this->formModel, 'password');
                
                // скрипт, проверяющий, заполнены ли минимально необходимые данные для входа в систему,
                // и если да, то активирует кнопку входа на сайт
                // 
                // второй обработчик отправляет данные формы на сервер и обрабатывает результат:
                // выводит сообщение об ошибке или перенаправляет на страницу профиля
                Yii::app()->getClientScript()->registerScript('login-form-init', "
                    $(document).ready(function(){
                        $(document).on('keyup keypress blur', '#$phoneInputID, #$passwordInputID', function(){
                            var button = $('$this->submitButtonSelector');
                            var digitCount = $('#$phoneInputID').val().replace(/\D+/g,'').length;
                            
                            if(digitCount == 11 && $('#$passwordInputID').val().length >= 6){
                                    $(button).removeClass('disabled-login-button').removeAttr('disabled');
                                    $('#login-form input').keypress(function(e){
                                        if(e.keyCode==13){
                                            $(button).trigger('click');
                                        }
                                    });
                            }
                            else
                                $(button).addClass('disabled-login-button').attr('disabled','disabled');
                                    
                            return true;
                        });
                        
                        $(document).on('click', '$this->submitButtonSelector', function(){
                            var formData = $('#login-form').serialize();
                            var loginUrl = $(this).data('login-url');
                            var redirectUrl = $(this).data('redirect-url');

                            $.ajax({
                                url: loginUrl,
                                data : formData,
                                type : 'post',
                                dataType: 'json',
                                success : function(data){
                                    if(data['$passwordInputID'] != ''){                                       
                                        $('#{$passwordInputID}_em_').html(data['$passwordInputID']).show();
                                    }
                                    if(data.status == 'success')
                                            window.location.href = redirectUrl;
                                }
                            });
                        });

                    });
                ", CClientScript::POS_READY);
        }
        
        /**
         * Запуск виджета
         */
        public function run() 
        {
                $this->render('theme.views.widgets.jloginwidget._loginform', array('model' => $this->formModel));
        }
    
}

?>
