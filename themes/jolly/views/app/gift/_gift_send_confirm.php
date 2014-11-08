<h2><?php echo CHtml::image("/images/fancybox-icons/gift.png", "icon", array('width'=>28,'height'=>28));?>Подарок</h2>
<div class="fancybox-content-padding-thin">
    <div id="fancybox-textured-wrapper">
        <div id="fancybox-congratulations">
            Вы уверены, что хотите сделать подарок?
        </div>
    </div>
    <div class="fancybox-content-padding-thick">
        <table id="fancybox-table">
            <tr>
                <td class="fancybox-table-column">
                    <?php echo CHtml::image($gift->imageURLBig);?>
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
        
        <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'hidden-gift-form'
        )); ?>
            <?php echo $form->hiddenField($giftForm, 'postcard');?>
            <?php echo $form->hiddenField($giftForm, 'id_gift'); ?>
            <?php echo $form->hiddenField($giftForm, 'id_reciever'); ?>
            <?php echo $form->hiddenField($giftForm, 'is_private'); ?>
        <?php $this->endWidget(); ?>
        
        <?php if(!empty($giftForm->postcard)) : ?>
            <div id="gift-postcard-wrapper-wrapper">
                <div id="gift-postcard-wrapper">
                    <?php echo Yii::app()->format->formatText($giftForm->postcard); ?>
                </div>
            </div>
        <?php endif; ?>
        <div class="fancybox-buttons">
            <?php echo CHtml::tag('button', array(
                'id' => 'trSubmitGiftForm',
                'class' => 'button-square orange button-fixed-width',
                'type' => 'button',
                'data-link' => J::url('gift/process'),
                'data-gift-row-count' => Gift::COUNT_ON_PROFILE,
            ), 'Да'); ?>
            <?php echo CHtml::tag('button', array(
                'class' => 'trGiftList button-square aquamarine button-fixed-width',
                'type' => 'button',
                'data-link' => J::url('gift/loadgifts' , array('uid' => $user->id)),
            ), 'Нет'); ?>
        </div>
    </div>
</div>
