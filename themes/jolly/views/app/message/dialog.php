<?php $this->pageTitle = $offer->interlocutor->name . ', ' . $offer->interlocutor->getAge() . ' - ' . Yii::app()->name;?>

<div id="dialog-page-header">
        <h1 id="page-header" class="float-left">Переписка</h1>
        <?php echo CHtml::link('к сообщениям', array('message/index'), array('class' => 'backlink'));?>
</div>

<div id="dialog-wrapper">
    <a name="chat-top-anchor" id="chat-top-anchor"></a>
    <div id="dialog-meetmethod-icon" class="mm-icon-medium <?php echo JMeetmethod::getHtmlClass($offer->id_method);?>"></div>
    <div id="dialog-questionary-wrapper">
        <div id="dialog-userpic-wrapper">
            <div id="dialog-gender-icon-wrapper">
                <div class="trTooltipGender gender-icon<?php echo $offer->interlocutor->id_gender == JGender::FEMALE ? ' female' : ' male'?>"></div>
                <div class="tooltip-gender <?php if($offer->interlocutor->id_gender == JGender::FEMALE) :?>female<?php else :?>male<?php endif;?>">
                    <?php echo JGender::getDescription($offer->interlocutor->id_gender)?>
                </div>
            </div>
            <h5 class="color-blue"><?php echo CHtml::encode($offer->interlocutor->name); ?>, <?php echo $offer->interlocutor->getAge(); ?>, (<?php echo $offer->interlocutor->getZodiac();?>)</h5>

        <?php $this->widget('JPhotoSlider', array(
            'photos' => $photos,
            'isOwn' => false,
            'userID' => $offer->interlocutor->id,
        ))?>
            
        </div>
        <div class="photo-counter">Фотографий в альбоме: <b><?php echo count($photos); ?></b></div>
        <ul class="questionary">
            <?php if($offer->interlocutor->city) : ?>
                <li>
                    <div class="left"><span class="questionary-question">Город:</span></div>
                    <div class="right"><span class="questionary-answer"><?php echo $offer->interlocutor->city->name;?></span></div>
                </li>
            <?php endif; ?>

            <?php if(isset($offer->interlocutor->profile->height) || isset($offer->interlocutor->profile->weight)) :?>
                <li>
                    <?php if($offer->interlocutor->profile->height) :?>
                        <div class="left"><span class="questionary-question">Рост: </span><span class="questionary-answer"><?php echo $offer->interlocutor->profile->height;?> см</span></div>
                    <?php endif; ?>
                    <?php if($offer->interlocutor->profile->weight) :?>
                        <div class="right"><span class="questionary-question weight">Вес: </span><span class="questionary-answer"><?php echo $offer->interlocutor->profile->weight;?> кг</span></div>
                    <?php endif;?>
                </li>
            <?php endif;?>

            <?php if(isset($offer->interlocutor->profile->id_seeking)) : ?>
                <li>
                    <div class="left"><span class="questionary-question">Познакомлюсь с: </span></div>
                    <div class="right">
                        <span class="questionary-answer">
                            <?php echo Profile::formatSeeking($offer->interlocutor->profile->id_seeking, 'ablative', false); ?>
                        </span>
                    </div>
                </li>
            <?php endif; ?>

            <?php if(!empty($offer->interlocutor->profile->age_min) || !empty($offer->interlocutor->profile->age_max)) : ?>
                <li>
                    <div class="left"><span class="questionary-question">В возрасте: </span></div>
                    <div class="right"><span class="questionary-answer"><?php echo Profile::formatAgeInterval($offer->interlocutor->profile->age_min, $offer->interlocutor->profile->age_max); ?></span></div>
                </li>
            <?php endif; ?>               

            <?php if(isset($offer->interlocutor->profile->meetTargets) && !empty($offer->interlocutor->profile->meetTargets)) : ?>
                <li>
                    <div class="left"><span class="questionary-question">Цель знакомства: </span></div>
                    <div class="right">
                        <?php echo JHtml::unorderedList($offer->interlocutor->profile->targetList(), array('class' => 'a')); ?>
                    </div>
                </li>
            <?php endif; ?>

            <?php if(isset($offer->interlocutor->profile->id_orientation) && !empty($offer->interlocutor->profile->id_orientation)) : ?>
                <li>
                    <div class="left"><span class="questionary-question">Ориентация: </span></div>
                    <div class="right"><span class="questionary-answer"><?php echo JOrientation::getDescription($offer->interlocutor->profile->id_orientation); ?></span></div>
                </li>
            <?php endif; ?>

            <?php if(isset($offer->interlocutor->profile->id_status) && !empty($offer->interlocutor->profile->id_status)) : ?>
                <li>
                    <div class="left"><span class="questionary-question">Отношения: </span></div>
                    <div class="right"><span class="questionary-answer"><?php echo JStatus::getDescription($offer->interlocutor->profile->id_status); ?></span></div>
                </li>
            <?php endif; ?> 

            <?php if(isset($offer->interlocutor->profile->id_welfare) && !empty($offer->interlocutor->profile->id_welfare)) : ?>
                <li>
                    <div class="left"><span class="questionary-question">Мат. положение: </span></div>
                    <div class="right"><span class="questionary-answer"><?php echo JWelfare::getDescription($offer->interlocutor->profile->id_welfare); ?></span></div>
                </li>
            <?php endif; ?>

            <?php if(isset($offer->interlocutor->profile->id_housing) && !empty($offer->interlocutor->profile->id_housing)) : ?>
                <li>
                    <div class="left"><span class="questionary-question">Наличие жилья: </span></div>
                    <div class="right"><span class="questionary-answer"><?php echo JHousing::getDescription($offer->interlocutor->profile->id_housing); ?></span></div>
                </li>
            <?php endif; ?>

            <?php if(isset($offer->interlocutor->profile->id_children) && !empty($offer->interlocutor->profile->id_children)) : ?>
                <li>
                    <div class="left"><span class="questionary-question">Дети:</span></div>
                    <div class="right"><span class="questionary-answer"><?php echo JChildren::getDescription($offer->interlocutor->profile->id_children); ?></span></div>
                </li>
            <?php endif; ?>

            <?php if(isset($offer->interlocutor->profile->iHave) && !empty($offer->interlocutor->profile->iHave)): ?>
                <li>
                    <div class="left"><span class="questionary-question">У меня есть: </span></div>
                    <div class="right">
                        <?php echo JHtml::unorderedList($offer->interlocutor->profile->ihaveList(), array('class' => 'a')); ?>
                    </div>
                </li>
            <?php endif; ?>    
        </ul>
    </div>
    <div id="chat-wrapper">        
                <?php if($offer->interlocutor->getOnlineStatus()) : ?>
                    <h6 class="online">Online</h6>
                <?php else : ?>
                    <h6 class="offline"><?php echo Yii::t('gender', 'was', (int)$offer->interlocutor->id_gender);?> <?php echo J::ago($offer->interlocutor->getLastActionDate());?></h6>      
                <?php endif; ?>
        <div id="chat-window">
            <div id="chat"></div>
            <div id="chat-controls" class="clearfix">
                <?php if($offer->status == Offer::ACCEPTED) : ?>       
                        <?php $form = $this->beginWidget('CActiveForm', array(
                                'id' => 'chat-form'
                        ));?>
                            <div id="row-chat-textarea">
                                <?php echo $form->textArea($chatForm, 'text', array('id' => 'chat-textarea', 'cols' => 80));?>
                                <?php echo $form->hiddenField($chatForm, 'recieverID');?>
                                <div id="dialog-blacklist-link-wrapper">       
                                    <?php echo CHtml::link('Отправить в черный список', 'javascript:void(0)', array(
                                            'class' => 'trLoadIgnoreForm',
                                            'data-user-id' => $offer->interlocutor->id,
                                            'data-link' => J::url('offer/loadignoreform'),
                                            'data-redirect' => J::url('message/index'),
                                            'data-reasons' => true,
                                            'data-place' => 'dialog',
                                      ))?>            
                                </div>
                                <div id="chat-send-mode-selected"></div>
                            </div>
                            <div class="row float-right">
                                    <?php echo CHtml::tag('button', array('class' => 'trChatSendMode button-square aquamarine', 'type' => 'submit'), 'Отправить')?>
                                    <div id="chat-send-mode">
                                        <div id="chat-send-mode-title">Способ отправки:</div>
                                        <div>
                                            <?php echo $form->radioButtonList($chatForm, 'sendMode', $chatForm->getSendModes(), array(
                                                'template' => '<div>{input} {label}</div>'
                                            ))?>
                                        </div>
                                    </div>
                            </div>
                        <?php $this->endWidget();?>
                                              
                        <?php $this->widget('application.widgets.JDialog.JDialog', array(
                                'formID' => 'chat-form',
                                'updateInterval' => 6000,
                                'messageSelector' => '.chat-message',
                                'chatContainerSelector' => '#chat',
                                'submitUrl' => J::url('message/send'),
                                'loadUrl' => J::url('message/load'),
                                'senderName' => CHtml::encode($offer->interlocutor->name),
                                'recieverName' => CHtml::encode(Yii::app()->user->getRealname()),
                                'onLoadMessages' => "function(data){
                                    if(data != ''){
                                        for(var i = 0; i < data.length; i++){
                                            if($('#m' + data[i].id).length > 0)
                                                continue;

                                            var html  = '<div id = \'m' + data[i].id + '\' class=\'chat-message\' data-message-id=\'' + data[i].id + '\'>';
                                                html += renderMessageHead(data[i]);
                                                html += renderMessageBody(data[i]);
                                                html += '</div>';

                                            $('#chat').append(html);
                                            $('#chat').scrollTop($('#chat').get(0).scrollHeight);
                                        }                                 
                                    }
                                }"
                        ));?>               
                <?php elseif($offer->status == Offer::NOT_ACCEPTED && !$offer->isOwn()) : ?>
                    <div id="chat-control-buttons">
                        
                        <?php $form = $this->beginWidget('CActiveForm', array(
                                'id' => 'chat-form'
                        ));?>
                            <?php echo $form->hiddenField($chatForm, 'recieverID');?>
                        <?php $this->endWidget();?>
                              
                        <?php echo CHtml::tag('button', array(
                                'class' => 'trFormAcceptOffer button-square orange',
                                'data-link' => J::url(('offer/loadacceptform')),
                                'data-place' => 'dialog',
                                'data-offer-id' => $offer->id
                        ), 'Принять')?> 
                        <?php echo CHtml::tag('button', array(
                                'class' => 'trLoadIgnoreForm button-square aquamarine',
                                'data-link' => J::url('offer/loadignoreform'),
                                'data-redirect' => J::url('message/index'),
                                'data-user-id' => $offer->interlocutor->id,
                                'data-place' => 'dialog',
                                'data-reasons'
                        ), 'Отказать')?>

                        <?php if(count($offer->interlocutor->meetmethodIds) != 1) : ?>
                            <?php echo CHtml::tag('button', array(
                                    'class' => 'trLoadOfferForm button-square  aquamarine',
                                    'data-link' => J::url('offer/offermethods', array('uid' => $offer->interlocutor->id)),
                                    'data-place' => 'dialog',
                                    'data-method-id' => $offer->id_method
                            ), 'Другой вариант')?>
                    </div>
                
                        <?php $this->widget('application.widgets.JDialog.JDialog', array(
                                'formID' => 'chat-form',
                                'updateInterval' => 20000,
                                'messageSelector' => '.chat-message',
                                'chatContainerSelector' => '#chat',
                                'submitUrl' => J::url('message/send'),
                                'loadUrl' => J::url('message/load'),
                                'senderName' => CHtml::encode($offer->interlocutor->name),
                                'recieverName' => CHtml::encode(Yii::app()->user->getRealname()),
                                'onLoadMessages' => "function(data){
                                    if(data != ''){
                                        for(var i = 0; i < data.length; i++){
                                            if($('m' + data[i].id).length > 0)
                                                return;

                                            var html  = '<div id = \'m' + data[i].id + '\' class=\'chat-message\' data-message-id=\'' + data[i].id + '\'>';
                                                html += renderMessageHead(data[i]);
                                                html += renderMessageBody(data[i]);
                                                html += '</div>';

                                            $('#chat').append(html);
                                            $('#chat').scrollTop($('#chat').get(0).scrollHeight);
                                        }                                 
                                    }
                                }"
                        ));?> 
                    <?php endif; ?>
                
                <?php elseif($offer->status == Offer::NOT_ACCEPTED && $offer->isOwn()) : ?>
                <div id="chat-waiting-answer">
                    Вы отправили пользователю предложение, дождитесь его ответа
                        <?php $form = $this->beginWidget('CActiveForm', array(
                                'id' => 'chat-form'
                        ));?>
                            <?php echo $form->hiddenField($chatForm, 'recieverID');?>
                        <?php $this->endWidget();?>
                </div>
                        <?php $this->widget('application.widgets.JDialog.JDialog', array(
                                'formID' => 'chat-form',
                                'updateInterval' => 20000,
                                'messageSelector' => '.chat-message',
                                'chatContainerSelector' => '#chat',
                                'submitUrl' => J::url('message/send'),
                                'loadUrl' => J::url('message/load'),
                                'senderName' => CHtml::encode($offer->interlocutor->name),
                                'recieverName' => CHtml::encode(Yii::app()->user->getRealname()),
                                'onLoadMessages' => "function(data){
                                    if(data != ''){
                                        for(var i = 0; i < data.length; i++){
                                            if($('m' + data[i].id).length > 0)
                                                return;

                                            var html  = '<div id = \'m' + data[i].id + '\' class=\'chat-message\' data-message-id=\'' + data[i].id + '\'>';
                                                html += renderMessageHead(data[i]);
                                                html += renderMessageBody(data[i]);
                                                html += '</div>';

                                            $('#chat').append(html);
                                            $('#chat').scrollTop($('#chat').get(0).scrollHeight);
                                        }                                 
                                    }
                                }"
                        ));?> 
                <?php endif;?>
                <div id="dialog-gift-wrapper">
                    <?php echo CHtml::tag('button', array(
                            'class' => 'trGiftList button-present',
                            'data-link' => J::url('gift/loadgifts' , array('uid' => $offer->interlocutor->id)),
                    ), ' ');?> 
                    <div id="dialog-gift-link">
                        Сделать подарок
                    </div>  
                </div>
            </div>
        </div>
    </div>
</div>
