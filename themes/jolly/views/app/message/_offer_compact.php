<div class="compact-offer-head<?php echo $offer->status == Offer::ACCEPTED ? ' trDialogRedirect' : ' trClickRedirect'?>" data-dialog-url="<?php echo J::url('message/dialog', array('id' => $offer->interlocutor->id))?>"
     data-redirect-url="<?php echo J::url('message/index', array('#' => 'u'.$offer->interlocutor->id))?>">
    <?php echo CHtml::image($offer->interlocutor->getUserpic('small'), '', array(
        'width' => Photo::SIZE_SMALL_X,
        'height' => Photo::SIZE_SMALL_Y,
    )); ?>
    <?php if(!$offer->newMessagesCount) : ?>
        <div class="mm-icon-medium <?php echo JMeetmethod::getHtmlClass($offer->id_method);?>"></div>
    <?php else : ?>
        <div class="new-message-alert"></div>
    <?php endif;?>
    <div>
        <span class="username"><?php echo CHtml::encode($offer->interlocutor->name); ?></span><span class="compact-offer-head-info">,
         <?php echo $offer->interlocutor->getAge(); ?>,
         <?php if(isset($offer->interlocutor->city)) : ?>
            <?php echo $offer->interlocutor->city->name; ?>
         <?php endif; ?>
        </span>
    </div>
    <div>
        <?php if($offer->interlocutor->getOnlineStatus()) : ?>
            <span class="offer-online">online</span>
        <?php else : ?>
            <div class="timestamp"><?php echo Yii::t('gender', 'was', (int)$offer->interlocutor->id_gender);?> <?php echo J::ago($offer->interlocutor->getLastActionDate());?></div>      
        <?php endif; ?>              
    </div>
</div>

<div class="compact-offer-body<?php echo $offer->status == Offer::ACCEPTED ? ' trDialogRedirect' : ' trClickRedirect'?>" 
     data-dialog-url="<?php echo J::url('message/dialog', array('id' => $offer->interlocutor->id))?>"
     data-redirect-url ="<?php echo J::url('message/index', array('#' => 'u'.$offer->interlocutor->id))?>">
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
                             'data-place' => 'compact',
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
                                    'data-place' => 'compact',
                            ),
                            'Отказать'
                    ); ?> 
                    <?php if(count($offer->interlocutor->meetmethodIds) != 1 || ($offer->interlocutor->meetmethodIds[0] != $offer->id_method && !empty($offer->id_method))) : ?>
                    <?php echo CHtml::tag('button', array(
                           'class' => 'trLoadOfferForm button-square azure',
                           'data-link' => J::url('offer/offermethods', array('uid' => $offer->interlocutor->id)),
                           'data-place' => 'compact',
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
                                    'data-place' => 'compact',
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
