<h2><?php echo CHtml::image("/images/fancybox-icons/offer.png", "icon", array('width'=>28,'height'=>28));?>Предложение</h2>
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
                    <div class="smsnotice-pic"></div>
                </td>
                <td id="fancybox-table-column-arrow">
                    
                </td>
                <td class="fancybox-table-column">
                    <div class="photo-wrapper">
                        <?php echo CHtml::image($offer->interlocutor->getUserpic('medium'), '', array(
                        'width' => Photo::SIZE_MEDIUM_X,
                        'height' => Photo::SIZE_MEDIUM_Y)); ?>    
                    </div>
                    <h5 class="color-blue"><b><?php echo CHtml::encode($offer->interlocutor->name); ?>, <?php echo $offer->interlocutor->getAge() ;?> (<?php echo User::getZodiacDescription($offer->interlocutor->birthday)?>)</b></h5>
                </td>
            </tr>
        </table>
        <div id="fancybox-congratulations-info">
            Ваше СМС-уведомление успешно отправлено!
        </div>
        <div class="fancybox-buttons">
            <?php echo CHtml::tag('button', array(
                    'type' => 'button',
                    'class' => 'button-square aquamarine',
                    'onClick' => '$.fancybox.close()'),
                    'Готово '
            );?>
        </div>
    </div>
</div>