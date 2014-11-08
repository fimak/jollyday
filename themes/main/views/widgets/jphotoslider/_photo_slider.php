<div class="photo-wrapper">
    <?php if(count($photos) > 1) : ?>
        <ul id="s<?php echo $userID?>" class="jcarousel-list">
            <?php $position = 1;?>
            <?php foreach($photos as $item) : ?>
                <li class="jcarousel-item">
                    <?php echo CHtml::tag('img', array(
                            'alt' => '',
                            'width' => Photo::SIZE_MEDIUM_X,
                            'height' => Photo::SIZE_MEDIUM_Y,
                            'src' => $position == 1 ? $item['filename_medium'] : '',
                            'class' => 'trIntmdRegister clickable-photo',
                            'data-user-id' => $item['id_user'],
                            'data-lazy-src' => $item['filename_medium'],
                            'data-link' => J::url('register/ajax/intmdregister'),
                    ), false, true); ?>
                </li>
            <?php $position++; ?>
            <?php endforeach; ?>
        </ul>
    <?php elseif(count($photos) == 1) : ?>
        <ul id="s<?php echo $userID?>">
            <?php foreach($photos as $item) : ?>
                <li>
                    <?php echo CHtml::tag('img', array(
                            'alt' => '',
                            'width' => 240,
                            'height' => 240,
                            'src' => $item['filename_medium'],
                            'class' => 'trIntmdRegister clickable-photo',
                            'data-user-id' => $item['id_user'],
                            'data-link' => J::url('register/ajax/intmdregister'),
                    ), false, true); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php elseif(count($photos) == 0) : ?>
        <?php echo CHtml::image(User::getNoPic('medium')); ?>
    <?php endif; ?>
</div>

<script>
    $(document).ready(function(){
        $('.jcarousel-list').jcarousel({
            'wrap': 'circular',
            'scroll': 1,
            'buttonPrevHTML': '<div class=\"slider-button-prev\">',
            'buttonNextHTML': '<div class=\"slider-button-next\">',
            'itemFallbackDimension' :  240,
            'setupCallback': function(instance){
                $(instance.container).parent().css('overflow', 'visible');
            },
            'itemFirstInCallback' : carouselLazyLoader
        });
    });
    
    
</script>