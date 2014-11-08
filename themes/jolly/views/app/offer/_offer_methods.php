<h2><?php echo CHtml::image("/images/fancybox-icons/offer.png", "icon", array('width'=>28,'height'=>28));?>Сделать предложение</h2>
<div class="fancybox-content-padding-thin">
    <div id="fancybox-textured-wrapper">
        <?php if($currentMethod == 0) :?>
            <span class="color-blue">Вы можете познакомиться прямо сейчас!</span>
        <?php else: ?>         
            <span class="color-blue">Предложите другой вариант знакомства</span>
        <?php endif;?>
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
                    <h5 class="color-blue"><b><?php echo CHtml::encode($user->name);?>, <?php echo(J::age($user->birthday));?>, (<?php echo($user->getZodiac());?>)</b></h5>
                </td>
                <td id="fancybox-table-column-arrow">
                </td>
                <td class="fancybox-table-column">
                    <?php $listMethods = JMeetmethod::getData(); ?>
                    <div class="meetmethods-big">
                        <?php foreach ($listMethods as $method) : ?> 
                            <?php if(in_array($method['id'], $user->meetmethodIds) && $method['id'] != $currentMethod) : ?>    
                                <?php echo CHtml::tag('div', array(
                                        'class' => 'trTooltipMeetmethodBig trLoadOfferForm mm-icon-big active ' . $method['htmlClass'],
                                        'data-link' => J::url('offer/loadofferform'),
                                        'data-user-id' => $user->id,
                                        'data-place' => $place,
                                        'data-method-id' => $method['id'],
                                        'data-offer-id' => $offerID,
                                ), '');?>
                                <div class="tooltip-meetmethod">
                                    <?php echo $method['description']; ?>
                                </div>
                            <?php else : ?>            
                                <?php echo CHtml::tag('div', array('class' => 'mm-icon-big unactive '.$method['htmlClass']), '')?>
                            <?php endif; ?>     
                        <?php endforeach; ?>     
                    </div>
                </td>
            </tr>
        </table>

        <div class="fancybox-buttons">
            <?php echo CHtml::tag('button', array(
                    'type' => 'button', 
                    'class' => 'button-square aquamarine',
                    'onClick' => '$.fancybox.close()'
            ), 'Отмена');?>
        </div>
    </div>
</div>