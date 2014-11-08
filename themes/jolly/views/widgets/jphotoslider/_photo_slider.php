<div class="photo-wrapper<?php if($isOwn) echo ' userpic-own'?>">
    <?php if(count($photos) > 1) : ?>
        <ul id="s<?php echo $userID?>" class="jcarousel-list" data-count="<?php echo count($photos)?>">
            <?php $position = 1;?>
            <?php foreach($photos as $item) : ?>
                <li class="jcarousel-item">
                    <?php echo CHtml::tag('img', array(
                            'alt' => '',
                            'width' => Photo::SIZE_MEDIUM_X,
                            'height' => Photo::SIZE_MEDIUM_Y,
                            'src' => $position == 1 ? $item['filename_medium'] : '',
                            'class' => 'trShowPhotoAlbum clickable-photo',
                            'data-photo-id' => $item['id'],
                            'data-user-id' => $item['id_user'],
                            'data-image-big-url' => $item['filename_big'],
                            'data-position' => $position,
                            'data-lazy-src' => $item['filename_medium'],
                    ), false, true); ?>
                </li>
            <?php $position++; ?>
            <?php endforeach; ?>
        </ul>
    <?php elseif(count($photos) == 1) : ?>
        <ul id="s<?php echo $userID?>" data-count="1">
            <?php foreach($photos as $item) : ?>
                <li>
                    <?php echo CHtml::tag('img', array(
                            'alt' => '',
                            'width' => 240,
                            'height' => 240,
                            'src' => $item['filename_medium'],
                            'class' => 'trShowPhotoAlbum clickable-photo',
                            'data-photo-id' => $item['id'],
                            'data-user-id' => $item['id_user'],
                            'data-image-big-url' => $item['filename_big'],
                            'data-position' => 1,
                    ), false, true); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php elseif(count($photos) == 0) : ?>
        <?php echo CHtml::image(User::getNoPic('medium'),'',array(
            'width' => Photo::SIZE_MEDIUM_X,
            'height' => Photo::SIZE_MEDIUM_Y,)); ?>
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