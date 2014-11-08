<div id="fancybox-textured-wrapper">
    <div id="offer-request-text">
        Хотите, чтобы <?php echo Yii::t('gender', 'he', (int)$viewData['offer']->interlocutor->id_gender)?> мгоновенно <?php echo Yii::t('gender', 'recognize', (int)$viewData['offer']->interlocutor->id_gender)?> о предложении?
    </div>
</div>
<table id="fancybox-table">
    <tr>
        <td class="fancybox-table-column">
            <div class="smsnotice-pic"></div>
        </td>
        <td id="fancybox-table-column-arrow">

        </td>
        <td class="fancybox-table-column">
            <div class="photo-wrapper">
                    <?php echo CHtml::image($viewData['offer']->interlocutor->getUserpic('medium'), '', array(
                            'width' => Photo::SIZE_MEDIUM_X,
                            'height' => Photo::SIZE_MEDIUM_Y)
                    ); ?>
            </div>
            <h5 class="color-blue"><b><?php echo CHtml::encode($viewData['offer']->interlocutor->name); ?>, <?php echo $viewData['offer']->interlocutor->getAge() ;?> (<?php echo $viewData['offer']->interlocutor->getZodiacDescription($viewData['offer']->interlocutor->birthday)?>)</b></h5>
        </td>
    </tr>
</table> 

<div id="offernotice">
    Всего за <span class="cost-offernotice">10 рублей</span> 
    <?php echo Yii::t('gender', 'he', (int)$viewData['offer']->interlocutor->id_gender)?> получит СМС уведомление о твоем предложении ПРЯМО СЕЙЧАС!
    <div id="offernotice-bonus">
        БОНУС: Ваше предложение будет первым в списке, пока <?php echo Yii::t('gender', 'he', (int)$viewData['offer']->interlocutor->id_gender)?> не ответит на него! 
    </div>
</div>
