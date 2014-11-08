<h2><?php echo CHtml::image("/images/fancybox-icons/gift.png", "icon", array('width'=>28,'height'=>28));?>Сделать подарок</h2>
<div class="fancybox-content-gift-list">
    <table id="gift-list-wrapper">
        <tr>
            <td id="gift-list-column-left"><div class="gift-list-column-title">
                    <div id="gift-list-arrow"></div>
                    <span class="color-blue"><b><?php echo CHtml::encode($user['name']);?></b></span> очень ждет вашего подарка!
                </div>
                <div class="photo-wrapper">
                    <?php echo CHtml::image(empty($user['userpic']) ? User::getNoPic('medium') : Photo::getUploadFolderURL($user['id']) . $user['userpic'], '', array(
                        'width' => Photo::SIZE_MEDIUM_X,
                        'height' => Photo::SIZE_MEDIUM_Y))?>
                </div>   
                <div id="gift-list-tabs">
                    <div id="gift-list-tabs-title">Выбрать подарок за:</div>
                    <div id="gift-list-tabs-buttons-container">
                        <?php foreach($costs as $cost) : ?>               
                            <div class="gift-list-tab-button" data-cost="<?php echo $cost; ?>">
                                <span class="gift-list-tab-button-amount"><?php echo $cost; ?></span>
                                <?php echo Yii::t('jolly', 'money', (int)$cost); ?>
                            </div>                   
                        <?php endforeach; ?>
                    </div>
                </div>
                <ul id="gift-list" data-account="<?php echo $account; ?>" data-gift-id-field="<?php echo CHtml::activeId($model, 'id_gift'); ?>">
                    <?php foreach($costs as $cost) : ?>
                        <li class="gift-list-tab" id="group-cost-<?php echo $cost?>">
                           <ul>
                               <?php foreach($data[$cost] as $gift) : ?>
                                   <?php echo CHtml::tag(
                                           'li', 
                                           array(
                                                   'class' => 'gift-list-item trSelectGift',
                                                   'data-gift-id' => $gift->id,
                                                   'data-gift-cost' => floor($gift->cost),
                                                   'data-big-image-src' => $gift->imageURLBig
                                           ), 
                                           CHtml::image($gift->imageURL, '', array('width'=>82,'height'=>82))
                                   );?>
                               <?php endforeach; ?>
                           </ul>
                       </li>
                    <?php endforeach; ?>
                </ul>             
            </td>
            
            <td id="gift-list-column-right">
                <div class="gift-list-column-title">
                    <b>Вы выбрали подарок за <span id="gift-cost"></span> <span id="declNum">монет</span>:</b>
                </div>
                <div id="selected-gift">
                    <?php echo CHtml::image('', 'Выбранный подарок', array(
                        'class' => 'gift',
                        'width' => 240,
                        'height' => 240,
                    ));?>
                </div>
                <div id="gift-nomoney-message">У вас недостаточно средств на счёте,
                    вы можете
                    <?php echo CHtml::link('пополнить счёт прямо сейчас', array('/app/payment/account'), array(
                            'id' => 'nomoney-hint-link'
                    ))?>
                    или оплатить подарок:
                    <div id="gift-payment-selector">
                        <?php $this->widget('JPaySelector', array(
                                'name' => 'payMethod',
                                'operation' => JPayment::OPERATION_RATING,
                                'data' => array(
                                        J::url('payment/mcommerceform', array('op' => JPayment::OPERATION_GIFT)) => 'мобильным платежом',
                                        J::url('payment/sms', array('op' => JPayment::OPERATION_GIFT)) => 'по СМС',
                                        J::url('payment/merchant', array('op' => JPayment::OPERATION_GIFT)) => 'другим способом'
                                ),
                                'submitButtonID' => 'trConfirmGiftSending',
                                'defaultSubmitLink' => J::url('gift/confirm'),
                                'submitLinkAttribute' => 'data-link',
                                'isDefaultSelected' => false,
                                'radioButtonListOptions' => array(
                                        'separator' => '<br />'
                                ),
                        )); ?>
                    </div>
                </div>
                
                <?php $form=$this->beginWidget('CActiveForm', array(
                        'id'=>'gift-form'
                )); ?>
                    <div class="gift-list-form-row">
                        <input id="trShowPostcardForm" type="checkbox" value="1"> Прикрепить открытку
                    </div>
                
                    <div id="gift-form-hidden-fields">
                        <div class="gift-list-form-row">
                            <?php echo $form->textArea($model, 'postcard', array('class'=>'gift-postcard-textarea', 'placeholder'=>'Введите текст...', 'maxlength' => GiftForm::POSTCARD_LENGTH)); ?>
                        </div>
                        <?php echo $form->hiddenField($model, 'id_gift'); ?>
                        <?php echo $form->hiddenField($model, 'id_reciever'); ?>
                        <?php $this->widget('JCharCounter', array(
                                'inputID' => CHtml::activeId($model, 'postcard'),
                                'containerID' => 'postcard-charcount',
                                'defaultString' => 'Осталось 150 символов',
                                'containerOptions' => array(
                                        'class' => 'postcard-char-counter'
                                ),
                        ))?>
                        <div class="gift-list-form-row">
                            <?php echo $form->checkBox($model, 'is_private'); ?> Показать открытку только получателю
                        </div>         
                    </div>
                <?php $this->endWidget(); ?>
            </td>
        </tr>
    </table>
    <div class="fancybox-buttons">
            <?php echo CHtml::tag('button', array(
                'id' => 'trConfirmGiftSending',
                'class' => 'button-square orange',
                'type' => 'button',
                'data-link' => J::url('gift/confirm'),
            ), 'Далее'); ?>
        
        
        <?php echo CHtml::tag('button', array('type' => 'button', 'class' => 'button-square aquamarine' , 'onClick' => '$.fancybox.close()'), 'Отмена');?>
    </div>
</div>

<?php Yii::app()->clientScript->registerScript('giftlist-init', "
    $(document).ready(function(){
        $('#gift-form-hidden-fields').hide();
        $('#trShowPostcardForm').on('change', function(){
            if($(this).is(':checked'))
                $('#gift-form-hidden-fields').show(); 
            else
                $('#gift-form-hidden-fields').hide();
        });
        
        $('.gift-list-tab-button').on('click', function(){
           var cost = $(this).data('cost');
            
            $('.gift-list-tab').css('display','none');
            $('#group-cost-' + cost).css('display','block');
            $('.gift-list-tab-button').removeClass('active');
            $(this).addClass('active');
        });
        
        var userAccount = parseFloat($('#gift-list').data('account'));
        var maxCost = parseFloat($('.gift-list-tab-button:last').data('cost'));
        
        var button = false;

        $.each($('.gift-list-tab-button'), function(){
            if(parseInt($(this).data('cost')) < userAccount + 0.000001)
                button = $(this);
        });
        
        if(!button){
            button = $('.gift-list-tab-button:last');
        }
        
        var availableCost = $(button).data('cost');
        button.trigger('click');
        $('#group-cost-' + availableCost + ' .trSelectGift:first').trigger('click');
        
        $(document).on('change, keyup', '.gift-postcard-textarea', function(){
                var text = $(this).val();
                if(text.length > 150) {
                        text = text.substring(0, 150);
                        $(this).val(text);
                }
        })
    });
", CClientScript::POS_READY)?>

<?php if($isChoiceFix) : ?>

<?php Yii::app()->clientScript->registerScript('choice-fix', "
    if( $('.gift-postcard-textarea').val().length > 0 )
        $('#trShowPostcardForm').trigger('click');
        
    $('.gift-list-item[data-gift-id=$model->id_gift]').trigger('click');
    var cost =  $('.gift-list-item[data-gift-id=$model->id_gift]').data('gift-cost');
        
    $('.gift-list-tab-button[data-cost=' + cost + ']').trigger('click');
", CClientScript::POS_READY)?>

<?php endif;?>