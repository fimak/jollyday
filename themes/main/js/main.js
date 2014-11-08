// определяем ослов
var ie = $.browser.msie;
var ieV = $.browser.version;
var ie6 = ie&&(ieV == 6);
var ltie7 = ie&&(ieV <= 7);
var ltie8 = ie&&(ieV <= 8);

function disableRegisterForm(){
        $('#register-request-form input').attr('disabled', 'disabled');
        $('#register-request-form').addClass('unactive-register-form');
        $('#register-captcha-wrapper').hide();
}

function enableRegisterForm(){
        $('#register-request-form input').removeAttr('disabled');
        $('#register-request-form').removeClass('unactive-register-form').trigger('reset');
        $('#register-captcha-wrapper').show();
}

// функции перезагрузки PIE
function setPie(selectors){
    $(selectors).css({
            'behavior' : 'url("/pie/PIE.htc")'
    });
}
function unsetPie(selectors){
    $(selectors).css("behavior", "none");
}
function resetPie(selectors){
    unsetPie(selectors);
    setPie(selectors);
}

function carouselLazyLoader(instance, object, index){
             
    function normalizeIndex(index, size){
        return index <= 0 
            ? size - ( Math.abs(index) % size) 
            : index % size == 0 
                ? size 
                : index % size;
    };

    var size = instance.options.size;
    var currentIndex = normalizeIndex(index, size);
    var prevIndex = normalizeIndex(index - 1, size);
    var nextIndex = normalizeIndex(index + 1, size);

    $(object).children().attr('src', $(object).find('img').data('lazy-src'));

    var srcNext = $(object).siblings('.jcarousel-item-' + nextIndex).children().data('lazy-src');
    var srcPrev = $(object).siblings('.jcarousel-item-' + prevIndex).children().data('lazy-src');


    $(object).siblings('.jcarousel-item-' + nextIndex).children().attr('src', srcNext);
    $(object).siblings('.jcarousel-item-' + prevIndex).children().attr('src', srcPrev);      
}

/**
 * Триггер, подгружающий форму регистрации
 */
$(document).on("click", ".trLoadRegisterForm", function(){
   var link = $(this).data('link');
   
    $.fancybox.close(true);
   
    $.ajax({
        url : link,
        type: 'post',
        dataType : 'html',
        success : function(data){                                                      
            $("#fancybox-container").html(data);
        },
        complete : function(){       
            $.fancybox({
                href : '#fancybox-container',
                scrolling : 'no', 
                autoSize: false,
                autoWidth : false,
                autoHeight: true,
                fitToView: false,
                width : 600,
                openSpeed: 0,
                closeSpeed: 0,
                autoCenter: false,
                padding: 0,
                afterClose: function(){ 
                    $('#fancybox-container').html(''); 
                },
                beforeShow : function(){
                    $(".fancybox-skin").addClass('registerform-icon');
                },
                afterShow : function(){
                    if(ltie8){
                        resetPie('.fancybox-skin');
                        resetPie('.fancybox-skin h2');
                        resetPie('.fancybox-skin button');
                        resetPie('.fancybox-skin input[type=\'submit\']');
                    }
                }
            });
        }
    });
});

/**
 * Триггер, кнопки регистрации
 */
$(document).on("click", "#trRegisterRequest", function(){
    var link = $(this).data('link');

    var $button = $(this);

    $button.attr('disabled','disabled');

    $.ajax({
        url : link,
        type: 'post',
        dataType : 'json',
        data : $('#register-request-form').serialize(),
        success : function(data){
            if(data.status == "success"){
                $("#register-confirm-form-wrapper").show();
                $("#register-confirm-buttons").show();
                $("#register-request-buttons").hide();
                                                             
                // обрабатываем ошибки валидации, если есть
                // кусок кода выдран из yiiactiveform
                var form = $('#register-request-form');
                var settings = form.data('settings');
                $.each(settings.attributes, function(){
                    $.fn.yiiactiveform.updateInput(this, data.errors, form);
                });
                
                disableRegisterForm();
           }
           else{
                $button.removeAttr('disabled');
               
                // обрабатываем ошибки валидации, если есть
                // кусок кода выдран из yiiactiveform
                var form = $('#register-request-form');
                var settings = form.data('settings');
               
                $.each(settings.attributes, function () {
                    $.fn.yiiactiveform.updateInput(this, data, form);
                });
           }
        },
        complete : function(){
            $('#register-captcha-refresh-link').trigger('click');
        }
    });
});

/**
 * Триггер ссылки повторного запроса на регистрацию
 */
$(document).on("click", "#trRegisterResend", function(){
    var link = $(this).data('link');

    $(this).removeAttr('href');

    $.ajax({
        url : link,
        type: 'post',
        dataType : 'json',
        data : $('#register-request-form').serialize(),
        success: function(data){
            if(data.status == 'success')
                $('#resend-message').removeClass('hide');
        }
    });
});


/**
 * Триггер, подтверждения телефона
 */
$(document).on("click", "#trRegisterConfirm", function(){
   var link = $(this).data('link');
   
    $.ajax({
        url : link,
        type: 'post',
        dataType : 'json',
        data : $('#register-confirm-form').serialize(),
        success : function(data){
            if(data.result=="success"){
                window.location.href = data.redirect
            }
            else{
                // обрабатываем ошибки валидации, если есть
                // кусок кода выдран из yiiactiveform
                var form = $('#register-confirm-form');
                var settings = form.data('settings');
               
                $.each(settings.attributes, function () {
                    $.fn.yiiactiveform.updateInput(this, data, form);
                });
            }
        }       
    });
});

/**
 * Триггер, регистрации другого номера телефона
 */
$(document).on("click", "#trRegisterRefresh", function(){
    var link = $(this).data('link');
   
    $('#trRegisterRequest').removeAttr('disabled');
   
    $.ajax({
        url : link,
        type: 'post',
        dataType : 'json',
        success : function(data){
            if(data.status=="success"){
                enableRegisterForm();
                
                $("#register-confirm-form-wrapper, #register-confirm-buttons").hide();
                $("#register-request-buttons").show();
                $("#register-confirm-form, #register-request-form").trigger('reset');        
                $("#register-request-form .success, #register-request-form .error, #register-confirm-form .success, #register-confirm-form .error,").removeClass('success error');
                $(".errorMessage").hide().html();
            }
        },
        complete : function(){
            $('#register-captcha-refresh-link').trigger('click');
            $('#RegisterForm_phone').focus();
        }
    });
});

/**
 * Триггер, тригер подгружает форму восстановления пароля
 */
$(document).on("click", "#trLoadRecoveryForm", function(){
   var link = $(this).data('link');
   
    $.ajax({
        url : link,
        type: 'post',
        dataType : 'html',
        success : function(data){                                                      
            $("#fancybox-container").html(data);
        },
        complete : function(){ 
            $.fancybox({
                href : '#fancybox-container',
                scrolling : 'no', 
                autoSize: false,
                autoWidth : false,
                autoHeight: true,
                fitToView: false,
                width : 600,
                openSpeed: 0,
                closeSpeed: 0,
                autoCenter: false,
                padding: 0,
                afterClose: function(){ 
                    $('#fancybox-container').html(''); 
                },
                afterShow : function(){
                    if(ltie8){
                        resetPie('.fancybox-skin');
                        resetPie('.fancybox-skin h2');
                        resetPie('.fancybox-skin button');
                        resetPie('.fancybox-skin input[type=\'submit\']');
                    }
                }
            });
        }
    });
});

/**
 * Триггер кнопки восстановления пароля
 */
$(document).on("click", "#trRecoveryPassword", function(){
    var link = $(this).data('link');
   
    $(this).attr('disabled', 'disabled');
   
    $.ajax({
        url : link,
        type: 'post',
        dataType : 'json',
        data: $('#recovery-form').serialize(),
        success : function(data){
            $("#recovery-form-message").html(data.message).show();
            
            if(data.errors)
                $('#trRecoveryPassword').removeAttr('disabled');
                    
            // обрабатываем ошибки валидации, если есть
            // кусок кода выдран из yiiactiveform
            var form = $('#recovery-form');
            var settings = form.data('settings');

            $.each(settings.attributes, function () {
                $.fn.yiiactiveform.updateInput(this, data.errors, form);
            });
            
            if(data.status == 'success'){
                $('.fancybox-buttons button:first').remove();
                $('.fancybox-buttons button:last').removeClass('aquamarine').addClass('orange').html('Готово');
            }
        },
        complete : function(){
            $('#recoverypass-capthcha-button').trigger('click');
        }
    });
});


$(document).on('mouseover', '.trTooltipGender', function(){
    $(this).tooltip({
        offset : [-2,22],
        relative: true,
        delay : 0
    });
    $(this).tooltip().show()
});


$(document).on('mouseover', '.trTooltipMeetmethodBig', function(){
    $(this).tooltip({
        relative: true,
        delay: 0,
        position: 'top left',
        offset : [-2,80]
    });
    $(this).tooltip().show()
});

$(document).on('mouseover', '.trTooltipGift', function(){
    $(this).tooltip({
        position: 'bottom right',
        offset : [-5,-45],
        relative: true,
        delay : 15
    });
    $(this).tooltip().show()
});

/**
 * Создание тултипов для подгруженного контента
 */
$(document).ajaxSuccess(function(){
    $('.trRegisterFormTooltip').tooltip({
        position: 'right center',
        offset : [45,180],
        relative: true,
        delay: 500,
        effect: 'fade',
        fadeInSpeed : 300,
        fadeOutSpeed: 300
    });
});

/**
 * Триггер, всплывающего промежуточного окна регистрации
 */
$(document).on("click", ".trIntmdRegister", function(){
    var link = $(this).data('link');
    var userID = $(this).data('user-id');
    
    $.ajax({
        url : link,
        type: 'get',
        dataType: 'html',
        data : {
            id : userID
        },
        success : function(data){
            $.fancybox.close(true);
            
            $("#fancybox-container").html(data);
            
            $.fancybox({
                href : '#fancybox-container',
                scrolling : 'no', 
                autoSize: false,
                autoWidth : false,
                autoHeight: false,
                fitToView: false,
                width : 843,
                height : 506,
                openSpeed: 0,
                closeSpeed: 0,
                autoCenter: false,
                padding: 0,
                afterClose: function(){ 
                    $('#fancybox-container').html(''); 
                },
                beforeShow : function(){
                    $("#fancybox-overlay").css({"position":"fixed"});
                    $(".fancybox-skin").addClass('intmdreg');
                    $(".fancybox-content").addClass('clearfix');
                },
                afterShow : function(){
                    if(ltie8){
                        resetPie('.fancybox-skin');
                        resetPie('.fancybox-skin h2');
                        resetPie('.fancybox-skin button');
                        resetPie('.fancybox-skin input[type=\'submit\']');
                    }
                }
            });
        }
    });
});

/**
 * Триггер, пролистывающий мордоленту вправо
 */
$(document).on("click", ".trFaceribbonNav", function(){
    var currentPage = parseInt($('#faceribbon-faces-wrapper').data('page'));
    var isLastPage = parseInt($('#faceribbon-faces-wrapper').data('lastpage'));
    var link = $(this).data('link');
    var direction = $(this).data('direction');
    
    var loadPage, ajaxData;
           
    switch(direction){
        case 'prev' : 
            if(currentPage == 1)
                return;
            loadPage = currentPage - 1;
            ajaxData = {page : loadPage};
            break;
            
        case 'next' : 
            if(isLastPage == 1)
                return;
            loadPage = currentPage + 1;
            ajaxData = {page : loadPage};
            break;
            
        default : 
            return;
    }
    
    $.ajax({
        url : link,
        type: 'get',
        dataType : 'json',
        data : ajaxData,
        success : function(data){                                                      
            $('#faceribbon-faces-wrapper').html(data.html);
            $('#faceribbon-faces-wrapper').data('page', data.page).attr('data-page', data.page);
            $('#faceribbon-faces-wrapper').data('lastpage', data.isLastPage).attr('data-page', data.isLastPage);
         }
    });
});

$(document).on('click', '.trLoadFaceribbonProfile', function(){
    $('.faceribbon-position').removeClass('faceribbon-active');
    $(this).parent().addClass('faceribbon-active');
    var userId =$(this).data('user-id');
    var link = $(this).data('link');
    
    $.ajax({
        url : link,
        type: 'get',
        dataType : 'html',
        data : {
            id: userId
        },
        success : function(data){
            $('#faceribbon-profile-container').html(data);
            $('#faceribbon-profile-container').data('page', data.page).attr('data-page', data.page);
            $('#faceribbon-profile-container').data('lastpage', data.isLastPage).attr('data-page', data.isLastPage);
            
            $('#faceribbon-profile-container .profile-inner-shadow-bottom').css('background' , 'none');
            $('#faceribbon-profile-container .profile').addClass('profile-highlight');
            $('#faceribbon-profile-container').addClass('highlight-container');
            
            var destination = $('#faceribbon').offset().top;
            if($.browser.safari){
              $('body').animate( { scrollTop: destination }, 200);
            }else{
              $('html').animate( { scrollTop: destination }, 200);
            }
        }
     }) 
});

/**
 * Триггер, всплывающего окна авторизации
 */
$(document).on("click", ".trShowLoginForm", function(){
    var link = $(this).data('link');
    
    $.fancybox.close(true);
    
    $.ajax({
        url : link,
        type: 'get',
        dataType: 'html',
        success : function(data){                                                      
            $("#fancybox-container").html(data);
        },
        complete : function(){ 
            $.fancybox({
                href : '#fancybox-container',
                scrolling : 'no', 
                autoSize: false,
                autoWidth : false,
                autoHeight: true,
                fitToView: false,
                width : 500,
                openSpeed: 0,
                closeSpeed: 0,
                autoCenter: false,
                padding: 0,
                afterClose: function(){ 
                    $('#fancybox-container').html(''); 
                },
                afterShow : function(){
                    if(ltie8){
                        resetPie('.fancybox-skin');
                        resetPie('.fancybox-skin h2');
                        resetPie('.fancybox-skin button');
                        resetPie('.fancybox-skin input[type=\'submit\']');
                    }
                }
            });
        }
    });
});

$(document).on('click', '#trSubmitModalLoginForm', function(){
    var formData = $('#login-form-modal').serialize();
    var loginUrl = $(this).data('login-url');
    var redirectUrl = $(this).data('redirect-url');

    $.ajax({
        url: loginUrl,
        data : formData,
        type : 'post',
        dataType: 'json',
        success : function(data){
            if(data['LoginForm_password'] != ''){                                       
                $('#error-modal-login').html(data['LoginForm_password']).show();
            }
            if(data.status == 'success')
                    window.location.href = redirectUrl;
        }
    });
});

$(document).on('keyup keypress blur', '#modal-input-phone, #modal-input-password', function(){
    var $button = $('#trSubmitModalLoginForm');

    if($('#modal-input-phone').val().length >= 18 && $('#modal-input-password').val().length >= 6)
        $button.removeAttr('disabled');
    else
        $button.attr('disabled','disabled');

    return true;
});

$(document).on('keypress', '#login-form-modal input', function(e){
    if(e.keyCode==13){
        $('#trSubmitModalLoginForm').trigger('click');
    }
});

/**
 * Триггер, при срабатывании которго раскрывается или закрывается список подарков
 * пользователя
 */
$(document).on("click", ".trMoreGifts", function(){
    var element = this;
    var containerID = $(this).data('user-id');
    
    var hiddenElements = $('#g' + containerID + ' li.overhide');
    var allElements = $('#g' + containerID + ' li');
    
    var countHidden = $(hiddenElements).length;
    var countTotal = $(allElements).length;
    
    if(countHidden > 0){
        $.each($(hiddenElements), function(){
                $(this).removeClass('overhide');
        });
        $(element).html('Свернуть');
        return;
    }
    else{
        var i = 0;
        $.each($(allElements), function(){
            i++; 
            if(i > 9){
                    $(this).addClass('overhide');
            }
        });
        $(element).html('Ещё');
    }
});

$(document).on('click', '.trHowToMeet', function(){
        var link = $(this).data('link');
    
        $.ajax({
            url : link,
            dataType : 'html',
            type : 'get',
            success : function(data){                                                      
                $("#fancybox-container").html(data);
                $.fancybox({
                    href : '#fancybox-container',
                    scrolling : 'no', 
                    autoSize: false,
                    autoWidth : false,
                    autoHeight: true,
                    fitToView: false,
                    width : 730,
                    openSpeed: 0,
                    closeSpeed: 0,
                    autoCenter: false,
                    padding: 0,
                    afterClose: function(){ 
                        $('#fancybox-container').html(''); 
                    },
                    beforeShow : function(){
                        $("#fancybox-overlay").css({"position":"fixed"});
                    },
                    afterShow : function(){
                        if(ltie8){
                            resetPie('.fancybox-skin');
                            resetPie('.fancybox-skin h2');
                            resetPie('.fancybox-skin button');
                            resetPie('.fancybox-skin input[type=\'submit\']');
                        }
                    }
                });
            }
        });
});

$(document).on('click', '#trBoss', function(){
        var link = $(this).data('link');
    
        $.ajax({
            url : link,
            dataType : 'html',
            type : 'get',
            success : function(data){                                                      
                $("#fancybox-container").html(data);
                $.fancybox({
                    href : '#fancybox-container',
                    scrolling : 'no', 
                    autoSize: false,
                    autoWidth : false,
                    autoHeight: true,
                    fitToView: false,
                    width : 730,
                    openSpeed: 0,
                    closeSpeed: 0,
                    autoCenter: false,
                    padding: 0,
                    afterClose: function(){ 
                        $('#fancybox-container').html(''); 
                    },
                    beforeShow : function(){
                        $("#fancybox-overlay").css({"position":"fixed"});
                    },
                    afterShow : function(){
                        if(ltie8){
                            resetPie('.fancybox-skin');
                            resetPie('.fancybox-skin h2');
                            resetPie('.fancybox-skin button');
                            resetPie('.fancybox-skin input[type=\'submit\']');
                        }
                    }
                });
            }
        });
});