<div class="right-messages-wrapper">

<div class="profile-offer-head">
        <?php if($offer->status == Offer::NOT_ACCEPTED) : ?>
            <?php if(Yii::app()->user->isMyId($offer->id_sender)) : ?>
                <h5 class="color-blue">Я <?php echo Yii::t('gender', 'offer', (int)Yii::app()->user->getGender());?>:</h5>
            <?php else : ?>
                <h5 class="color-orange">Новое предложение:</h5>
            <?php endif; ?>
        <?php elseif($offer->newMessagesCount > 0) : ?>
                <h5 class="color-orange">Новое сообщение:</h5>
        <?php else : ?>
                <h5 class="color-blue">Последние сообщения:</h5>
        <?php endif; ?>
                
        <?php if(!$offer->newMessagesCount) : ?>
            <div class="mm-icon-medium <?php echo JMeetmethod::getHtmlClass($offer->id_method);?>"></div>
        <?php else : ?>
            <div class="new-message-alert"></div>
        <?php endif;?>
</div>

<div class="profile-offer" id="o<?php echo $offer->id?>">
    <div class="compact-offer-body<?php echo $offer->status == Offer::ACCEPTED ? ' trDialogRedirect' : ''?>" data-dialog-url="<?php echo J::url('message/dialog', array('id' => $offer->interlocutor->id))?>">       
            <div class="compact-messages">
                <?php if($offer->status == Offer::NOT_ACCEPTED) : ?>
                    <div class="mm-icon-large-wrapper">
                        <?php echo CHtml::tag('span', array(
                                'class' => 'mm-icon-large ' . JMeetmethod::getHtmlClass($offer->id_method)
                        ), ''); ?>
                    </div>
                    <div class="color-blue mm-icon-large-description"><?php echo JMeetmethod::getDescription($offer->id_method)?></div>
                <?php else : ?>   
                    <?php foreach($offer->lastMessages as $message) : ?>
                        <div class="compact-offer-message <?php echo $message->status == Message::STATUS_UNREAD && !$message->isOwn ? ' new' : ''?>">
                             <div class="compact-offer-message-header clearfix">
                                 <span class="compact-offer-message-username <?php echo $message->isOwn ? 'color-orange' : 'color-blue'?>"><?php echo CHtml::encode($message->senderName).':'?></span>
                                 <span class="compact-offer-message-timestamp"><?php echo J::ago($message->date)?></span>
                             </div>
                             <div class="compact-offer-message-text"><?php echo Yii::app()->format->formatNText(Yii::app()->format->formatCrop($message->text, 80))?></div>
                         </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
    </div>
    <div class="compact-offer-buttons-container">
        <div class="compact-offer-buttons-wrapper">
            <div class="compact-offer-buttons">
                <?php if($offer->status == Offer::NOT_ACCEPTED && !$offer->isOwn()) : ?>
                        <?php echo CHtml::tag('button', array(
                                'class' => 'trFormAcceptOffer button-square orange',
                                'data-link' => J::url(('offer/loadacceptform')),
                                'data-place' => 'messages',
                                'data-offer-id' => $offer->id
                        ), 'Принять')?> 
                        <?php echo CHtml::tag(
                                'button',
                                array(
                                        'type' => 'button',
                                        'class' => 'button-square azure trLoadIgnoreForm',
                                        'data-user-id' => $offer->interlocutor->id,
                                        'data-link' => J::url('offer/loadignoreform'),
                                        'data-reasons' => false,
                                        'data-place' => 'messages',
                                ),
                                'Отказать'
                        ); ?>  
                        <?php if(count($offer->interlocutor->meetmethodIds) != 1 || ($offer->interlocutor->meetmethodIds[0] != $offer->id_method && !empty($offer->id_method))) : ?>
                            <?php echo CHtml::tag('button', array(
                                    'class' => 'trLoadOfferForm button-square azure',
                                    'data-link' => J::url('offer/offermethods', array('uid' => $offer->interlocutor->id)),
                                    'data-place' => 'messages',
                                    'data-method-id' => $offer->id_method,
                                    'data-user-id' => $offer->interlocutor->id,
                            ), 'Другой вариант')?>
                        <?php endif; ?>
                <?php elseif($offer->status == Offer::ACCEPTED) :?>
                        <button class="button-square azure trDialogRedirect" data-dialog-url="<?php echo J::url('message/dialog', array('id' => $offer->interlocutor->id))?>">Написать сообщение</button>
                        <?php echo CHtml::tag(
                                'button',
                                array(
                                        'type' => 'button',
                                        'class' => 'button-square azure trLoadIgnoreForm',
                                        'data-user-id' => $offer->interlocutor->id,
                                        'data-link' => J::url('offer/loadignoreform'),
                                        'data-reasons' => true,
                                        'data-place' => 'messages',
                                ),
                                'Игнорировать'
                        ); ?>  
                <?php else :?>
                    <div class="compact-offer-buttons">
                        <div class="compact-offer-i-suggest">
                            Я <?php echo Yii::t('gender', 'offer', (int)Yii::app()->user->getGender());?>
                        </div>
                    </div>
                <?php endif;?>
            </div>
            <div class="button-back-wrapper hide">
                <button class="button-square azure trCmBtnBack" data-offer-id="<?php echo $offer->id; ?>">Назад</button>
            </div>
        </div>
    </div>
</div> 
</div>