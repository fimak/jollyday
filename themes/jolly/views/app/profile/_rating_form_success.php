<h2><?php echo CHtml::image("/images/fancybox-icons/up.png", "icon", array('width'=>28,'height'=>28));?>Подняться наверх</h2>
<div class="fancybox-content-thin">
    <div id="fancybox-textured-wrapper-orange">
        <div id="fancybox-congratulations">
            Поздравляем!
        </div>
    </div>
    <div id="rateup-wrapper-top">
        <div class="photo-wrapper">
            <?php echo CHtml::image($user->getUserpic('medium'), '', array(
                        'width' => Photo::SIZE_MEDIUM_X,
                        'height' => Photo::SIZE_MEDIUM_Y)); ?>
        </div>
        <div id="rate-ribbon"></div>
        <div id="fancybox-congratulations-info">
            Вы поднялись в рейтинге!
        </div>
    </div>
    <div class="fancybox-content-padding-thick">
        <div class="fancybox-buttons">
            <?php echo CHtml::tag('button', array(
                    'type' => 'button', 
                    'class' => 'button-square aquamarine' , 
                    'onClick' => '$.fancybox.close()'), 
            'Готово');?>
        </div>
    </div>
</div>