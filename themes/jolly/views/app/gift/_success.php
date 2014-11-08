<h2><?php echo CHtml::image("/images/fancybox-icons/gift.png", "icon", array('width'=>28,'height'=>28));?>Подарок сделан</h2>
<div class="fancybox-content-padding-thin">
    <div id="fancybox-textured-wrapper-orange">
        <div id="fancybox-congratulations">
            Поздравляем!
        </div>
    </div>
    <div class="fancybox-content-padding-thick">
        <table id="fancybox-table">
            <tr>
                <td class="fancybox-table-column">
                    <?php echo CHtml::image($gift->imageURLBig, '', array('width'=>240,'height'=>240));?>
                </td>
                <td id="fancybox-table-column-arrow">
                </td>
                <td class="fancybox-table-column">
                    <div class="photo-wrapper">
                         <?php echo CHtml::image($user->getUserpic('medium'), '', array(
                        'width' => Photo::SIZE_MEDIUM_X,
                        'height' => Photo::SIZE_MEDIUM_Y)); ?>
                    </div>
                    <h5 class="color-blue"><b><?php echo CHtml::encode($user->name); ?>, <?php echo $user->getAge() ;?> (<?php echo User::getZodiacDescription($user->birthday)?>)</b></h5>
                </td>
            </tr>
        </table>
        <div id="fancybox-congratulations-info">
            Вы сделали подарок!
        </div>
        <?php if(!empty($postcard)) : ?>
            <div id="gift-postcard-wrapper-wrapper">
                <div id="gift-postcard-wrapper">
                    <?php echo Yii::app()->format->formatText($postcard); ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if(!$offerStatus && !Blacklist::getBlacklistStatus(Yii::app()->user->id, $user->id)) : ?>
            <div id="offer-meet">
                <div id="offer-meet-title"><b>Кажется вы еще не знакомы? Вы можете это исправить!</b></div>
                <?php echo CHtml::tag('button', array(
                    'class' => 'trShowOfferMethods big-button yellow',
                    'type' => 'button',
                    'data-link' => J::url('/app/offer/offermethods'),
                    'data-user-id' => $user->id,
                ), 'Познакомиться сейчас!'); ?>
            </div>
        <?php endif; ?>
        <div class="fancybox-buttons">
            <?php echo CHtml::tag('button', array(
                'class' => 'trGiftList button-square orange',
                'type' => 'button',
                'data-link' => J::url('gift/loadgifts' , array('uid' => $user->id)),
            ), 'Сделать еще подарок'); ?>
            <?php echo CHtml::tag('button', array(
                'type' => 'button',
                'class' => 'button-square aquamarine',
                'onClick' => '$.fancybox.close()'
            ), 'Готово'); ?>
        </div>
    </div>
</div>