<h2><?php echo CHtml::image("/images/fancybox-icons/payment.png", "icon", array('width'=>28,'height'=>28));?>Ваш счет успешно пополнен</h2>
<div class="fancybox-content-padding-thin">
    <div id="fancybox-textured-wrapper">
        <div id="fancybox-congratulations-orange">
            Поздравляем!
        </div>
    </div>
    <div class="fancybox-content-padding-thick">
        <table id="fancybox-table">
            <tr>
                <td class="fancybox-table-column">
                    <div class="money-couch"></div>
                </td>
                <td id="fancybox-table-column-arrow">
                </td>
                <td class="fancybox-table-column">
                    <div class="photo-wrapper">
                         <?php echo CHtml::image($user->getUserpic('medium'), '', array(
                        'width' => Photo::SIZE_MEDIUM_X,
                        'height' => Photo::SIZE_MEDIUM_Y)); ?>
                    </div>
                    <h5 class="color-orange"><?php echo CHtml::encode($user->name); ?>, <?php echo $user->getAge() ;?> (<?php echo User::getZodiacDescription($user->birthday)?>)</h5>
                </td>
            </tr>
        </table>
        <div id="fancybox-congratulations-info">Ваш счёт успешно пополнен на 
            <span class="color-orange big-text"><b><?php echo JPayment::formatAmount($amount); ?></b></span> 
        <?php echo JPayment::formatMoneyWord($amount); ?>
        </div>
        <div class="fancybox-buttons">
            <?php echo CHtml::tag('button', array(
                'type' => 'button',
                'class' => 'button-square aquamarine',
                'onClick' => '$.fancybox.close()'
            ), 'Закрыть'); ?>
        </div>
    </div>
</div>