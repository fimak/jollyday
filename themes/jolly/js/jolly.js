// определяем ослов
var ie = $.browser.msie;
var ieV = $.browser.version;
var ie6 = ie&&(ieV == 6);
var ltie7 = ie&&(ieV <= 7);
var ltie8 = ie&&(ieV <= 8);

// для диалога - идёт ли загрузка сообщений
var load_in_process = false;

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

//Функция склонения слов по чеслительным
function plurals(number, titles){  
    cases = [2, 0, 1, 1, 1, 2];  
    return titles[ (number%100>4 && number%100<20)? 2 : cases[(number%10<5) ? number%10 : 5] ];  
}

function verb(number, titles){
    return titles[(number == 1) || (number % 10 == 1 && number != 11 && number != 111) ? 0 : 1];
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
    var nextNextIndex = normalizeIndex(index + 2, size);
    var prevPrevIndex = normalizeIndex(index - 2, size);
    
    $(object).children().attr('src', $(object).find('img').data('lazy-src'));

    var srcNext = $(object).siblings('.jcarousel-item-' + nextIndex).children().data('lazy-src');
    var srcPrev = $(object).siblings('.jcarousel-item-' + prevIndex).children().data('lazy-src');

    $(object).siblings('.jcarousel-item-' + nextIndex).children().attr('src', srcNext);
    $(object).siblings('.jcarousel-item-' + prevIndex).children().attr('src', srcPrev);
    
    var srcNextNext = $(object).siblings('.jcarousel-item-' + nextNextIndex).children().data('lazy-src');
    var srcPrevPrev = $(object).siblings('.jcarousel-item-' + prevPrevIndex).children().data('lazy-src');

    $(object).siblings('.jcarousel-item-' + nextNextIndex).children().attr('src', srcNextNext);
    $(object).siblings('.jcarousel-item-' + prevPrevIndex).children().attr('src', srcPrevPrev);  
}

// плагин notice
(function($){
    jQuery.fn.notice = function(status, message, delay, callback){
        
        if(!callback)
            callback = function(){};            
        
        var messageHtml = '<div class="' + status + '">' + message + '</div>';
              
        $(this).html(messageHtml).show().delay(delay).fadeOut('400', callback);
    };
})(jQuery);

// плагин notice
(function($){
    jQuery.fn.quickNotice = function(message, delay){
        $(this)
            .append('<div id="quick-notice-container">' + message + '</div>')
            .find('#quick-notice-container')
            .delay(delay)
            .fadeOut('400', function(){
                $(this).remove()
            })
            .center();
            
            $(window).scroll(function(){
                $('#quick-notice-container').center();
            });
    };
})(jQuery);


// плагин для центрирования блока посередине экрана
(function($){
    jQuery.fn.center = function () {
        this.css("position","absolute");
        this.css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) + 
                                                    $(window).scrollTop()) + "px");
        this.css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) + 
                                                    $(window).scrollLeft()) + "px");
        return this;
    };
})(jQuery);





/**
 * Триггер, вызывающий диалоговое окно предложения
 */
$(document).on("click", ".trLoadOfferForm", function(){
    var link = $(this).data('link');
    var userID = $(this).data('user-id');
    var methodID = $(this).data('method-id');
    var place = $(this).data('place');
    var offerID = $(this).data('offer-id');
    
    $.fancybox.close(true);
    
    $.ajax({
        url : link,
        data : {
            id_user : userID,
            id_method : methodID,
            place : place,
            id_offer: offerID
        },
        type: 'post',
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
                width : 730,
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
 * Триггер, запускающий отправку предложения пользователю
 */
$(document).on("click", ".trOffer", function(){
    var link = $(this).data('link');
    var userID = $(this).data('user-id');
    var methodID = $(this).data('method-id');
    var offerID = $(this).data('offer-id');
    var place = $(this).data('place');
    var name = $(this).data('user-name');
        
    $.ajax({
        url : link,
        data : {
            uid: userID,
            mid: methodID,
            place: place,
            username: name,
            id_offer: offerID
        },
        type : 'post',
        dataType : 'json',
        success : function(data){
            if(data.status == 'success'){
                if(userID == 2){
                    $('#mainmenu-list .trLoadOfferForm').parent().remove();
                }
                
                switch(place){
                    case 'profile':
                        $("#u" + userID + ' .profile-column-right').html(data.html);
                        break;
                    case 'compact':
                        $('#o' + offerID).html(data.html);
                        break;
                    case 'messages':
                        $('#u' + userID + ' .profile-column-right').html(data.html);
                        break;
                    case 'dialog':
                        window.location.reload(true);
                    default:
                        break;
                } 
            }
            if(data.notice_available == true){
                $.ajax({
                    type : 'post',
                    dataType : 'html',
                    url : data.notice_url,
                    data : {
                        id_offer : data.id_offer,
                        id_user : data.id_user
                    },
                    success : function(data){
                        $('#fancybox-container').html(data);
                    }
                });
            }
            else{
                $.fancybox.close(true);
            }
        }
    });
});

/**
 * Триггер, вызывающий диалоговое окно отправки пользователя в чёрный список
 */
$(document).on("click", ".trLoadIgnoreForm", function(){
    var link = $(this).data('link');
    var userID = $(this).data('user-id');
    var redirect = $(this).data('redirect');
    var reasons = $(this).data('reasons');
    var place = $(this).data('place');
    
    if(reasons == undefined)
        reasons = 0;
    
    $.ajax({
        url : link,
        data : {
            id_user : userID,
            redirect : redirect,
            reasons : reasons,
            place : place
        },
        type: 'post',
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
                width : 730,
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
 * Триггер, запускающий отправку пользователя в чёрный список
 */
$(document).on("click", ".trIgnore", function(){
    var link = $(this).data('link');
    var userID = $(this).data('user-id');
    var redirect = $(this).data('redirect');
    var place = $(this).data('place');
    var offerID = $(this).data('offer-id');
    
    var spam = $('form input[name="reason"]:checked').length > 0 ? $('form input[name="reason"]:checked').val() : 0;
     
    $.ajax({
        url : link,
        data : {
            id_user : userID,
            place : place,
            spam : spam
        },
        type: 'post',
        dataType : 'json',
        success : function(data){
            if(data.result == 'success'){
                $.fancybox.close();
                
                switch(place){
                    case 'dialog':
                        window.location.href = redirect;
                        break;
                    case 'messages':
                        $('#u' + userID).parent().fadeOut(300);
                        break;
                    case 'compact':
                        $('#ajax-container').html(data.html);
                        break;
                    default:
                        break;
                }
            }
        }       
    });
});


/**
 * Триггер, вызывающий диалоговое окно для подтверждения исключения
 * пользователя из чёрного списка
 */
$(document).on("click", ".trLoadWhitelistForm", function(){
    var link = $(this).data('link');
    var userID = $(this).data('user-id');
    
    $.ajax({
        url : link,
        data : {
            id_user : userID
        },
        type: 'post',
        success : function(data){                                                      
            $("#fancybox-container").html(data)
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

/**
 * Триггер, запускающий удаление пользователя из чёрного списка
 */
$(document).on("click", ".trWhitelist", function(){
    var link = $(this).data('link');
    var userID = $(this).data('user-id');
    
    $.ajax({
        url : link,
        data : {
            id_user : userID
        },
        type : 'post',
        success : function(data){
            $.fancybox.close();
            if(data == '')
                $("#u" + userID).parent().fadeOut(300);
            else
                $("#u" + userID + ' .profile-column-right').html(data);
        }
    });
});

/**
 * Триггер, подгружающий во всплывающее окно форму для подарка
 * при нажатии на подарок из списка
 */
$(document).on("click", ".trSelectGift", function(){
    var giftID = $(this).data('gift-id');
    var src = $(this).data('big-image-src');
    
    var giftFieldId = $('#gift-list').data('gift-id-field');
    
    var giftCost = parseFloat($(this).data('gift-cost'));
    var userAccount = parseFloat($('#gift-list').data('account'));
         
    $('#' + giftFieldId).val(giftID);  
    
    $('#selected-gift img').attr('src' ,src);
    $('#gift-cost').html(giftCost);
    
    if(userAccount >= giftCost){
        $('#gift-nomoney-message').hide();
        $('#payMethod :radio').removeAttr('checked').trigger('refresh');
        $('#payMethod :radio:first').trigger('change').trigger('refresh');
        $('#payMethod .radio').removeClass('checked');
    }else{
        $('#payMethod :radio:first').attr('checked', 'checked').trigger('change').trigger('refresh');
        $('#gift-nomoney-message').show();
    }

    $('#declNum').html(plurals(giftCost, ['монету', 'монеты', 'монет']));
});

/**
 * Триггер, отправляющий форму с подарком
 */
$(document).on("click", "#trSubmitGiftForm", function(){
    var link = $(this).data('link');
    var lineCount = $(this).data('gift-row-count');
    var isPayment = $(this).data('payment');
    var formData = $("#hidden-gift-form").serialize();
    
    $(this).attr('disabled','disabled');
    
    $.ajax({
        url : link,
        type: "post",
        dataType : isPayment == 1 ? 'html' : 'json',
        data: formData,
        success : function(data){
            // если денег нет, то переходим на страницу платежа
            if(isPayment){
                $.fancybox.close(true);
                
                $('#fancybox-container').html(data);
                
                $.fancybox({
                    href : '#fancybox-container',
                    scrolling : 'no', 
                    autoSize: false,
                    autoWidth : false,
                    autoHeight: true,
                    fitToView: false,
                    width : 760,
                    openSpeed: 0,
                    closeSpeed: 0,
                    autoCenter: false,
                    padding: 0,
                    afterClose: function(){ 
                        $('#fancybox-container').html(''); 
                    },
                    beforeShow : function(){
                        $("#fancybox-overlay").css({"position":"fixed"});
                        $(".fancybox-skin").addClass('giftlist');
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
                return;
            }
            
            // если деньги есть, то всё ровно, обрабатываем подарок
            $("#fancybox-container").html(data.html);
            
            // изменяем счёт
            $("#account").html(data.account);
            $("#word-money").html(data.word_money);

            // если первый подарок, то удаляем уведомление
            if($("#g" + data.id_user + " li").length == 0){
                $("#u" + data.id_user + " .nogifts-notice").remove();
                $("#u" + data.id_user + " .gift-icon-wrapper").addClass('gift-icon-wrapper-bg');
            }
        
            $("#g" + data.id_user).prepend(data.gift);
      
            // считаем отбражаемы и неотображаемые подарки
            var giftCount = $("#g" + data.id_user + " li").length;
            var visibleGiftCount = $("#g" + data.id_user + " li:visible").length;
            // проверяем, раскрыт ли список подарков
            var isGiftsExpanded = (giftCount == visibleGiftCount);
      
            if(giftCount <= lineCount){
                ;
            }
            if(giftCount == (lineCount + 1)){
                $("#g" + data.id_user + " li:visible:last").addClass('overhide');
                var button = '<div class="gifts-more-wrapper"><button class="trMoreGifts button-square azure" data-user-id="' + data.id_user + '">Ещё</button></div>';
                $("#u" + data.id_user + " .gift-container").append(button);
            }
            if(giftCount > (lineCount + 1)){
                if(!isGiftsExpanded){
                    $("#g" + data.id_user + " li:visible:last").addClass('overhide');
                }
            }    
            
            // увеличиваем счётчик подарков
            $("#u" + data.id_user + " .gift-header").html("Подарки (" + $("#g" + data.id_user + " li").length + ")");
            
            $.fancybox.update();
        } 
    });
});

$(document).on("click", "#trConfirmGiftSending", function(){
    var link = $(this).data('link');
    var formData = $("#gift-form").serialize();
    var isPayment = $(this).data('payment');
    
    
    $.ajax({
        url : link,
        type: "post",
        dataType : isPayment == 1 ? 'html' : 'json',
        data: formData,
        success : function(data){
            // если денег нет, то переходим на страницу платежа

            $.fancybox.close(true);

            $('#fancybox-container').html(isPayment == 1 ? data : data.html);

            $.fancybox({
                href : '#fancybox-container',
                scrolling : 'no', 
                autoSize: false,
                autoWidth : false,
                autoHeight: true,
                fitToView: false,
                width : 760,
                openSpeed: 0,
                closeSpeed: 0,
                autoCenter: false,
                padding: 0,
                afterClose: function(){ 
                    $('#fancybox-container').html(''); 
                },
                beforeShow : function(){
                    $("#fancybox-overlay").css({"position":"fixed"});
                    $(".fancybox-skin").addClass('giftlist');
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
 * Триггер, вызывающий всплывающее окно со списком подарков для дарения
 * при нажатии на соответствующую кнопку
 */
$(document).on("click", ".trGiftList", function(){
    var link = $(this).data('link');
    
    $.ajax({
        url : link,
        type: 'post',
        data: $('#hidden-gift-form').serialize(),
        success : function(data){         
            $.fancybox.close(true);
            
            $("#fancybox-container").html(data);
            
            $.fancybox({
                href : '#fancybox-container',
                scrolling : 'no', 
                autoSize: false,
                autoWidth : false,
                autoHeight: true,
                fitToView: false,
                width : 760,
                openSpeed: 0,
                closeSpeed: 0,
                autoCenter: false,
                padding: 0,
                afterClose: function(){ 
                    $('#fancybox-container').html(''); 
                },
                beforeShow : function(){
                    $("#fancybox-overlay").css({"position":"fixed"});
                    $(".fancybox-skin").addClass('giftlist');
                },
                afterShow : function(){
                    if(ltie8){
                        resetPie('.fancybox-skin');
                        resetPie('.fancybox-skin h2');
                        resetPie('.fancybox-skin button');
                        resetPie('.fancybox-skin input[type=\'submit\']');
                        resetPie('.gift-list-tab-button');
                    }
                }
            });
        }
    });
});

/**
 * Триггер, загружающий в уже активное всплывающее окно форму для подарка
 * при нажатии на подарок из списка 
 */
$(document).on("click", ".trGiftListBack", function(){
    var giftID = $(this).data('gift-id');
    var userID = $(this).data('user-id');
    var link = $(this).data('link');

    $.ajax({
        url : link,
        type: 'get',
        success : function(data){                                                      
            $("#fancybox-container").html(data);
        }
    });       
});

/**
 * Триггер, загружающий компактный профиль поиске по фотографиям
 */
$(document).on("click", ".trShowCompactProfile", function(){

    // если анкета по выбранной фото уже развёрнута,
    // то по нажатии удаляем анкету и удаляем класс
    if($(this).hasClass('photo-selected')){
        $(this).removeClass('photo-selected');
        $('.profile').remove();
        return;
    }
     
    // перед загрузкой анкеты удаляем существующие на странице анкеты
    $('.trShowCompactProfile').removeClass('photo-selected');
    $(this).addClass('photo-selected');
    $('.profile').remove();

    var element = this;
    var selector;

    var link = $(this).data('link');
    var userID = $(this).data('user-id');
    $.ajax({
        url : link,
        type: 'post',
        data : {
            id: userID
        },        
        success: function(data){
            if( $(element).hasClass('search-column1') && !$(element).parent().next().hasClass('search-result')){
                $(element).parent().after(data); 
            }        
            else if( $(element).hasClass('search-column1')){
                $(element).parent().next().next().after(data); 
            }
            else if( $(element).hasClass('search-column2')){
                $(element).parent().next().after(data); 
            }
            else if( $(element).hasClass('search-column3')){
                $(element).parent().after(data);        
            }
            
            $('#u' + userID).addClass('profile-highlight');
            
            $('#u' + userID + ' .profile-inner-shadow-bottom').css('background' , 'none');
            $('#u' + userID).prepend('<div class="profile-close-button trCloseLoadedProfile"></div>')
                .find('.mm-icon-medium, .new-message-alert').css('right', '32px');;
        }
    });    
});

/**
 * Тригггер, запускающий действие установки выбранной фотографии в качестве аватарки
 */
$(document).on("click", ".trUseAsUserpic", function(){
    var photoID = $(this).data('photo-id');
    var link = $(this).data('link');
    var photoURL = $('#p' + photoID + ' img').attr('src');
      
    $.ajax({
        url : link,
        data : {
            id : photoID
        },
        type: 'get',
        success: function(){
            $('.userpic-own img').attr('src', photoURL);
            
            if($('#photo-uploader-wrapper').length > 0){                     
                $('#photo-uploader-message-wrapper').notice('uploader-message', 'Фото выбранно как аватарка', 3000);
            }
            else{
                $('#p' + photoID + ' .photo-wrapper-message').remove();
                $('#p' + photoID + ' .photo-wrapper').prepend('<div class="photo-wrapper-message"></div>');
                
                $('#p' + photoID + ' .photo-wrapper-message').html('Фото выбрана как аватарка').hide().fadeIn().delay(1000).fadeOut();
            }
        }
    });      
});

/**
 * Триггер, запускающий действие удаления выбранной фотографии
 */
$(document).on("click", ".trDeletePhoto", function(){
    var photoID = $(this).data('photo-id');
    var userID = $(this).data('user-id');
    var link = $(this).data('link');
    var element = $(this);

    $.ajax({
        url : link,
        dataType : 'json',
        data : {
            id : photoID
        },
        type: 'get',
        success : function(data){
            $('.own-photo-counter').html(parseInt($('.own-photo-counter').html()) - 1);
            $('#p' + photoID).animate({opacity: 1.0}, 0).fadeOut('fast');
            /*
            if($('#photo-uploader-wrapper').length == 0){          
                var $photo = $('#s' + userID).find('img[data-photo-id='  + photoID + ']');
                var carousel = $('#s' + userID).data("jcarousel");
                var itemsCount = parseInt($('#s' + userID).data('count')) - 1;
                
                //carousel.setup();
                
                $photo.each(function(){
                    var index = parseInt($(this).parent().attr('jcarouselindex'));
                    carousel.removeAndAnimate(index);
                })
                 
                
                var index = parseInt($photo.parent().attr('jcarouselindex'));
                carousel.removeAndAnimate(index);
                
                $('#s' + userID).find('img').each(function(){
                    if($(this).data('position') > index)
                            $(this).data('position', parseInt($(this).data('position')) - 1);
                })

                $('#s' + userID).data('count', itemsCount);

                if(itemsCount <= 1){
                    carousel.buttonNext.remove();
                    carousel.buttonPrev.remove();
                    carousel.lock();
                }

                if(data.userpic_id == 0 && data.userpic == true){
                    $('.userpic-own').html('<img src="/images/nopic_medium.jpg" />');
                    $('#faceribbon-left-column').html('<div id="faceribbon-nophoto-alert">НЕТ ФОТО</div><div id="faceribbon-nophoto-description"><a href="/photo/uploader">Загрузить</a><br></div>');
                } 
            }
            */       
            if($('#photo-uploader-wrapper').length > 0){                       
                $('#photo-uploader-message-wrapper').notice('uploader-message', 'Фото удалено', 3000);
            }
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
 * Окно для выбора способа отправки сообщения в чате
 */
$(document).ready(function(){
    $('.trChatSendMode').tooltip({
        position: 'top center',
        offset : [-6,-65],
        relative: true,
        predelay : 1200,
        delay: 500,
        effect: 'fade',
        fadeInSpeed : 300,
        fadeOutSpeed: 300
    });
});


/**
 * Триггер, открывающий сообщения в компактном сообщении
 */
$(document).on("click", ".trShowMessages", function(){
    var offerID = $(this).data('offer-id');
    
    $('#o' + offerID + ' .compact-messages').show();
    $('#o' + offerID + ' .accept-actions').hide();
    $('#o' + offerID + ' .another-methods').hide();
});



/**
 * Триггер, запускающий действие подтверждение предложения
 */
$(document).on("click", ".trAcceptOffer", function(){
    var link = $(this).data('link');
    var offerID = $(this).data('offer-id');
    var acceptType = $(this).data('accept-type');
    var dialogURL = $(this).data('dialog-url');
    var place = $(this).data('place');
    var userID = $(this).data('user-id');
    var lastDigits = $('#digits-input').val();

    $.ajax({
        url : link,
        data : {
            oid : offerID,
            type : acceptType,
            place : place,
            digits : lastDigits
        },
        type: 'post',
        dataType: 'json',
        success : function(data){
            if(data.status == 'error' && data.digitsError == true){
                $('#last-digits-error').show();
                return;
            }
              
            if(data.status == 'success'){
                $.fancybox.close(true);
                if(acceptType == 'dialog')
                        window.location.href = dialogURL;
                switch(place){
                    case 'dialog':
                        window.location.reload(true);
                        break;
                    case 'compact':
                        $('#o' + offerID).html(data.html);
                        break;
                    case 'messages':
                        $('#u' + userID + ' .profile-column-right').html(data.html);
                    default:
                        break;
                }
            }
        }    
    });      
});

/**
 * Триггер, который перенаправляет на страницу с диалогом
 */
$(document).on("click", ".trDialogRedirect", function(){
    window.location.href = $(this).data('dialog-url');
});

$(document).on("click", ".trClickRedirect", function(){
    window.location.href = $(this).data('redirect-url');
});
/**
 * Триггер, удаляющий открытку с подарка
 */
$(document).on("click", ".trDeletePostcard", function(){
    var link = $(this).data('link');
    var element = $(this);

    $.ajax({
        url : link,
        type: 'get',
        dataType: 'json',
        success : function(data){
            if(data.result == 'success')
                    element.parent().parent().remove();
        }
    });
});

/**
 * Триггер, вызвающий всплывающее окно c подтверждением предложения
 * на странице диалога
 */
$(document).on("click", ".trFormAcceptOffer", function(){
    var link = $(this).data('link');
    var offerID = $(this).data('offer-id');
    var place = $(this).data('place');
 
    $.ajax({
        url : link,
        data : {
            id_offer : offerID,
            place : place
        },
        type: 'post',
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

/**
 * Триггер, который подгружает форму для редактирования анкеты
 */
$(document).on("click", ".trLoadQuestionaryUpdate", function(){
    var link = $(this).data('link');

    $.ajax({
        dataType : 'html',
        url : link,
        type: 'get',
        success : function(data){
                $('#ajax-container').html(data);
                $(document).scrollTop($("#ajax-block").offset().top);
                moveToAnchor("#ajax-block",300);
        }
    });
});

/**
 * Триггер, который подгружает фотоальбом пользователя
 */
$(document).on("click", ".trLoadPhotoAlbum", function(){
    var link = $(this).data('link');

    $.ajax({
        dataType : 'html',
        url : link,
        type: 'get',
        success : function(data){              
                $('#ajax-container').html(data);
                $(document).scrollTop($("#ajax-block").offset().top);
                moveToAnchor("#ajax-block",300);
        }
    });
});

/**
 * Триггер, который подгружает форму редактирования интересов
 */
$(document).on("click", ".trLoadMethodsForm", function(){
    var link = $(this).data('link');

    $.ajax({
        dataType : 'html',
        url : link,
        type: 'get',
        success : function(data){              
                $('#ajax-container').html(data);
                $(document).scrollTop($("#ajax-block").offset().top);
                moveToAnchor("#ajax-block",300);
        }
    });
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

/**
 * Триггер, подгружающий компактные сообщения на страницу профиля
 */
$(document).on("click", ".trLoadCompactMessages", function(){
    var link = $(this).data('link');
    $.ajax({
        url : link,
        success : function(data){                                                      
            $('#ajax-container').html(data);
        }
    });
});




$(document).on("click", ".trLoadFaceribbonProfile", function(){
    var link = $(this).data('link');
    var userID = $(this).data('user-id');
    var currentUserID = $(this).data('user-current-id');
    var loadedProfileID = $('#faceribbon-profile-container .profile').attr('id');
    
    if(('u' + userID) == loadedProfileID){
        // если кликнуто при загруженном профиле и загружен тот же самый профиль,
        // который хотели подгрузить, то он закрывается (очищается)
        $('#faceribbon-profile-container').empty();
    }
    else{
        // иначе подгружаем профиль
        $.ajax({
            url : link,
            type: 'post',
            data : {
                id: userID
            },        
            success: function(data){                  
                $('#faceribbon-profile-container').html(data);

                $('#faceribbon-profile-container .profile-inner-shadow-bottom').css('background' , 'none');
                $('#faceribbon-profile-container .profile').addClass('profile-highlight');
                $('#faceribbon-profile-container .profile').prepend('<div class="profile-close-button trCloseLoadedProfile"></div>')
                    .find('.mm-icon-medium, .new-message-alert').css('right', '32px');
            }
        }); 
    }
});

$(document).on("click", ".trLoadGiftProfile", function(){
    var link = $(this).data('link');
    var userID = $(this).data('user-id');

    $.ajax({
        url : link,
        type: 'post',
        data : {
            id: userID
        },        
        success: function(data){                  
            $('#gift-profile-container').html(data);

            $('#gift-profile-container .profile-inner-shadow-bottom').css('background' , 'none');
            $('#gift-profile-container .profile').addClass('profile-highlight');
            $('#gift-profile-container .profile').prepend('<div class="profile-close-button trCloseLoadedProfile"></div>')
                .find('.mm-icon-medium, .new-message-alert').css('right', '32px');
        }
    }); 

});

/**
 * Триггер, который закрывает подгруженный профиль
 * (под мордолентой или в поиске в виде фото)
 */
$(document).on("click", ".trCloseLoadedProfile", function(){
    $(this).parent().fadeOut('fast');
    $('.trShowCompactProfile').removeClass('photo-selected');
});

/**
 * Триггер, вызывающий фенсибокс с фотоальбомом пользователя
 */

$(document).on('click', '.trShowPhotoAlbum', function(){
    var selectedPhoto = $(this).data('photo-id');
    var userID = $(this).data('user-id');
    var bigPhotoUrl = new Array();
    var fancyBoxLinks = new Array();
    var count = $('#s' + userID).data('count');
    var position = $(this).data('position');
    var dopArray = new Array();
    
    $('#s' + userID + ':first img').each(function(){
        if(!($(this).data('position') in bigPhotoUrl)){           
            bigPhotoUrl[$(this).data('position')] = '<img src="' + $(this).data('image-big-url') + '" data-position="' + $(this).data('position') + '"/>';
        }
        if(!($(this).data('position') in dopArray)){           
            dopArray[$(this).data('position')] = '<img src="' + $(this).data('image-big-url') + '" data-position="' + $(this).data('position') + '"/>';
        }
    });

    bigPhotoUrl.splice(1,0, ('<img src="' + $(this).data('image-big-url') + '" data-position="' + $(this).data('position') + '"/>'));
    bigPhotoUrl.splice($(this).data('position')+1,1);

    //индусское волшебство
    bigPhotoUrl.splice(2,$(this).data('position')-1);
    dopArray.splice($(this).data('position'));
    for(var key in dopArray){
        bigPhotoUrl.push(dopArray[key]);
    }
    //тут вверху мы отсортировали массив

    for(var key in bigPhotoUrl){
        fancyBoxLinks.push({
            href : bigPhotoUrl[key],
            title: ' '
        });
    }
    $.fancybox(fancyBoxLinks, {
        type: 'html',
        closeBtn : true,
        nextClick : $('#s' + userID + ':first img').length != 1,
        width: 800,
        minWidth : 800,
        maxWidth: 800,
        height: 605,
        minHeight: 605,
        maxHeight: 605,
        aspectRatio: false,
        autoSize: false,
        autoWidth: false,
        autoHeight: false,
        mouseWheel: false,
        autoCenter : false,
        fitToView: false,
        padding : 40,
        prevEffect : 'none',
        nextEffect : 'none',
        beforeShow : function(){
            $(".fancybox-skin").addClass('alternative');
        },
        afterLoad : function(current, previous){
            photoIndex = $(this.content).data('position');
            this.title = 'Фото ' + photoIndex + ' из ' + count;
        }
    });
});


/**
 * Триггер, вызвающий всплывающее окно c диалогом повышения рейтинга
 */
$(document).on("click", "#trShowRateForm", function(){
    var link = $(this).data('link');
    
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
                    $(".fancybox-skin").addClass('rateform');
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
 * Триггер, послыющий запрос на повышение рейтинга
 */
$(document).on("click", "#trIncreaseRating", function(){
    var link = $(this).data('link');
    var isPayment = $(this).data('payment');
    
    $.ajax({
        url : link,
        type: 'get',
        dataType: isPayment == 1 ? 'html' : 'json',
        success : function(data){
            $.fancybox.close(true);
            
            
            var firstplace_text = '<div id="faceribbon-first-place-wrapper"><div class="faceribbon-first-place-text">Ваша анкета на</div>' + 
                            '<div data-direction="curr" data-link="/app/profile/fr.loadFaces" class="faceribbon-first-place trFaceribbonNav" id="rating">1</div>' + 
                            '<div class="faceribbon-first-place-text">месте</div>' + 
                            '<div id="faceribbon-first-place-congratulations">Поздравляем!</div></div>';
            
            if(isPayment == 1){
                $('#fancybox-container').html(data);
            }else{
                $('#faceribbon-left-column').html(firstplace_text).addClass('faceribbon-first-place');
                $('#rating').trigger('click');
                $('#account').html(data.account).trigger('click');
                $('#fancybox-container').html(data.html);
            }

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
            
        case 'curr' : 
            ajaxData = {position : parseInt($(this).html())};
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
            
            if(data.page == 1)
                $('#faceribbon-nav-prev').addClass('unactive');  
            else
                $('#faceribbon-nav-prev').removeClass('unactive');
            
            if(data.isLastPage == 1)
                $('#faceribbon-nav-next').addClass('unactive');
            else
                $('#faceribbon-nav-next').removeClass('unactive');
            
        }
    });
});

/**
 * Триггер, вызвающий всплывающее окно c формой пополнения баланса
 */
$(document).on("click", ".trShowMerchantForm", function(){
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


/**
 * Тригер вызова окна предложения знакомства
 */
$(document).on("click", ".trShowOfferMethods", function(){
    var link = $(this).data('link');
    var method = $(this).data('method');
    var place = $(this).data('place');
    var userID = $(this).data('user-id');
     
    if(!method)
        method = 0;
    
    if(!place)
        place = 'profile';
    
    $.fancybox.close(true);
    
    $.ajax({
        url : link,
        type: 'post',
        data : {
            id_user : userID,
            place : place,
            method : method
        },
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
                    $(".fancybox-skin").addClass("offer-methods");
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

$(document).on('click', '#trSubmitPhoneSet', function(){
        var link = $(this).data('link');
        var $button = $(this);
    
        $.ajax({
            url : link,
            dataType : 'json',
            type : 'post',
            data : $('#set-phone-form').serialize(),
            success : function(data){                 
                if(data.status == 'error'){
                    var form = $('#set-phone-form');

                    var settings = form.data('settings');
                    $.each(settings.attributes, function () {
                        $.fn.yiiactiveform.updateInput(this, data.errors, form);
                    });
                }
                if(data.status == 'success' && data.stage == 'request'){
                    $('#newphone-code-row').show('fast');
                    $('[name=\'set-email-submit\']').val('Отправить код');
                    $('.errorMessage').empty();
                    $('#SetPhoneForm_phone').attr('disabled', 'disabled').parent().removeClass('error').addClass('success');
                    $('#json-response-questionary').notice(data.status, data.message, 3000);
                    $button.data('link', data.confirmurl).html('Подтвердить номер');
                } 
                if(data.status == 'success' && data.stage == 'confirm'){
                    $.fancybox.close(true);
                    $('body').quickNotice(data.message, 5000);
                    $('#settings-phone-number').html(data.newphone);
                }
                if(data.newphone != null){
                    $('#settings-phone-number').html(data.newphone);
                }    
            }
        });
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

$(document).on('click', '.trAccountPayMethod', function(){
    var link = $(this).data('link');
    
    if($(this).hasClass('unactive'))
            return;
    $('.active').removeClass('active');
    $(this).addClass('active');
    $('.content-inner', this).addClass('active');
    
    $.ajax({
        url : link,
        dataType : 'html',
        type : 'get',
        beforeSend: function(){
            $('#payment-method-account-container').append('<div class="loading-screen"></div>');
        },
        success : function(data){                                                      
            $('.method-inner-right').html(data);
            $('.pay-row-wrapper:first').trigger('click');
        },
        complete: function() {
            $('.loading-screen').remove();
        }
    });
});

$(document).on('click', '.trSubmitMcommerceForm', function(){
    var link = $(this).data('link');
    var button = $(this);
    var bonus = $('#page-payment-form-bonus').html();
       
    $.ajax({
        url : link,
        dataType : 'json',
        type : 'post',
        data : $('#page-payment-mcommerce-form').serialize() + '&bonus=' + bonus,
        beforeSend: function(){
            $('#payment-method-account-container').append('<div class="loading-screen"></div>');
        },
        success : function(data){
            if(data.status == 'success')
                $(button).attr('disabled', 'disabled');
            
            if(data.status == 'error' || $('#payment-method-account-container').length == 0)
                $('#mcommerce-order-status').notice(data.status, data.message, 300000);
            else{
                $('#mcommerce-order-status').hide();
                $('#fancybox-container').html(data.html);
                
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
                        $(button).removeAttr('disabled');
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
        },
        complete: function() {
            $('.loading-screen').remove();
        }
    });
});
