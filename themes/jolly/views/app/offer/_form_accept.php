<h2><?php echo CHtml::image("/images/fancybox-icons/offer.png", "icon", array('width'=>28,'height'=>28));?>Принять предложение</h2>

<div class="fancybox-content-padding-thin">
    <div id="fancybox-textured-wrapper" class="fancybox-offer-accept">
Вы действительно хотите принять предложение <br /> <span class="color-blue"><b><?php echo JMeetmethod::getDescription($offer->id_method)?></span></b>
от пользователя <span class="color-blue"><b><?php echo CHtml::encode($offer->interlocutor->name)?></b></span>?
    </div>
        <ul class="accept-actions-list">
            <?php if($offer->id_method != 1) : ?>
                <li>
                    <?php echo CHtml::link('Да, и сообщить '.Yii::t('gender', 'him', (int)$offer->interlocutor->id_gender).' мой номер телефона', 'javascript:void(0)', array(
                            'class' => 'offer-accept-phone',
                            'onClick' => '$("#form-phone-lastdigits").show()',
                    ))?>
                    
                    <div id="form-phone-lastdigits">
                        <div id="form-phone-lastdigits-hint">
                            Для потдтверждения действия необходимо ввести последние 3 цифры<br />
                            номера Вашего мобильного телефона
                        </div>
                        <?php $form=$this->beginWidget('CActiveForm', array(
                                'id'=>'offer-accept-phone-form',
                                'htmlOptions' => array(
                                        'class' => 'form-light'
                                ),
                        )); ?>
                            <div class="row">
                                <span id="form-phone-lastdigits-number"><?php echo substr($phone, 0, strlen($phone)-3);?></span>
                                <?php echo $form->textField($lastDigitsForm, 'digits', array(
                                        'maxlength' => 3,
                                        'id' => 'digits-input'
                                )); ?>
                                <?php echo CHtml::tag('button', array(
                                    'type' => 'button',
                                    'class' => 'button-square orange trAcceptOffer',
                                    'data-link' => J::url('offer/accept'),
                                    'data-offer-id' => $offer->id,
                                    'data-user-id' => $offer->interlocutor->id,
                                    'data-accept-type' => 'number',
                                    'data-place' => $place
                                ), 'Отправить')?>
                            </div>
                            <div id="last-digits-error">
                                Номер телефона введён неверно
                            </div>
                        <?php $this->endWidget(); ?>
                        
                    </div>
                    
                </li>
            <?php endif; ?>
            <li>
                <?php echo CHtml::link('Да, и написать '.Yii::t('gender', 'him', (int)$offer->interlocutor->id_gender).' сообщение', 'javascript:void(0)', array(
                        'class' => 'offer-accept-dialog trAcceptOffer',
                        'data-link' => J::url('offer/accept'),
                        'data-dialog-url' => J::url('message/dialog', array('id' => $offer->interlocutor->id)),
                        'data-offer-id' => $offer->id,
                        'data-user-id' => $offer->interlocutor->id,
                        'data-accept-type' => 'dialog',
                        'data-place' => $place
                ))?>
            </li>            
            <li>
                <?php echo CHtml::link('Да, но пусть '.Yii::t('gender', 'he', (int)$offer->interlocutor->id_gender).' напишет мне '.Yii::t('gender', 'first', (int)$offer->interlocutor->id_gender), 'javascript:void(0)', array(
                        'class' => 'offer-accept-message trAcceptOffer',
                        'data-link' => J::url('offer/accept'),
                        'data-offer-id' => $offer->id,
                        'data-user-id' => $offer->interlocutor->id,
                        'data-accept-type' => 'message',
                        'data-place' => $place
                ))?>
            </li>            
            <li>
                <?php echo CHtml::link('Нет, это ошибка', 'javascript:void(0)', array(
                        'class' => 'offer-decline',
                        'onClick' => '$.fancybox.close()'
                ))?>                    
            </li>           
        </ul>
<div class="fancybox-content-padding-thick">
    <div class="fancybox-buttons">
        <?php echo CHtml::tag('button', array('type' => 'button', 'class' => 'button-square aquamarine' , 'onClick' => '$.fancybox.close()'), 'Отмена');?>
    </div>
</div>
</div>
