<h2><?php echo CHtml::image("/images/fancybox-icons/blacklist.png", "icon", array('width'=>28,'height'=>28));?>Отправить в чёрный список</h2>
<div class="fancybox-content-padding-thin">
    <div id="fancybox-textured-wrapper">
        <div id="offer-request-text">
            Вы уверены, что хотите отправить пользователя <b><span class="color-blue"><?php echo CHtml::encode($user->name); ?></span></b>
            в <b>Чёрный список</b>?<br />
            <?php echo Yii::t('gender', 'He', (int)$user->id_gender)?> не сможет вам написать, 
            не будет отображаться в результатах поиска.
            
        </div>
    </div>    
    
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
            <td id="fancybox-table-column-arrow-grey">

            </td>
            <td class="fancybox-table-column">
                <div id="trash"></div>
            </td>
        </tr>
    </table>
    
    
    <?php if($reasons) : ?>
            <form id="fancybox-ignore-reasons">
                Укажите причину, по которой вы хотите игнорировать пользователя: <br/>
                <?php echo CHtml::radioButtonList('reason', 0, Offer::getIgnoreReasonsList(), array(
                        'separator' => false,      
                ))?>
            </form>
    <?php endif; ?>
    <div class="fancybox-content-padding-thick">
        <div class="fancybox-buttons">
            <?php echo CHtml::tag(
                    'button',
                    array(
                            'type' => 'button', 
                            'class' => 'button-square orange trIgnore',
                            'data-link' => J::url('offer/ignore'),
                            'data-redirect' => isset($redirect) ? $redirect : '0',
                            'data-user-id' => $userID,
                            'data-place' => $place
                    ),
                    'Да, '.Yii::t('gender', 'sure', (int)Yii::app()->user->getGender()));?>
            <?php echo CHtml::tag('button', array('type' => 'button', 'class' => 'button-square aquamarine' , 'onClick' => '$.fancybox.close()'), 'Отменить');?>
        </div>
    </div>
</div>