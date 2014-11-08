<h2><?php echo CHtml::image("/images/fancybox-icons/offer.png", "icon", array('width'=>28,'height'=>28));?>Предложение</h2>
<div class="fancybox-content-padding-thin">
    <div id="fancybox-textured-wrapper">
        <div id="offer-request-text">
            Вы уверены, что хотите предложить <?php echo Yii::t('gender', 'him', (int)$user->id_gender)?>
            <b><?php echo $meetmethod['accusative']; ?></b>?
        </div>
    </div>
    <div class="fancybox-content-padding-thick">
        <table id="fancybox-table">
            <tr>
                <td class="fancybox-table-column">
                    <div class="photo-wrapper">
                        <?php echo CHtml::image($user->getUserpic('medium'), '', array(
                        'width' => Photo::SIZE_MEDIUM_X,
                        'height' => Photo::SIZE_MEDIUM_Y)); ?>    
                    </div>
                    <h5 class="color-blue"><b><?php echo CHtml::encode($user->name); ?>, <?php echo $user->getAge() ;?> (<?php echo $user->getZodiacDescription($user->birthday)?>)</b></h5>
                </td>
                <td id="fancybox-table-column-arrow">
                    
                </td>
                <td class="fancybox-table-column">
                    <div class="mm-icon-giant <?php echo $meetmethod['htmlClass']; ?>"></div>
                    <h5 class="color-blue"><b><?php echo $meetmethod['description']; ?></b></h5>
                </td>
            </tr>
        </table>
        
        <div class="fancybox-buttons">
            <?php echo CHtml::tag(
                    'button',
                    array(
                            'class' => 'trIntmdRegister button-square orange button-fixed-width',
                            'data-link' => J::url('register/ajax/intmdregister'),
                            'data-user-id' => $user->id
                    ),
                    'Да');?>
            <?php echo CHtml::tag('button', array(
                    'type' => 'button',
                    'class' => 'button-square aquamarine button-fixed-width',
                    'onClick' => '$.fancybox.close()'),
                    'Нет'
            );?>
        </div>
    </div>
</div>