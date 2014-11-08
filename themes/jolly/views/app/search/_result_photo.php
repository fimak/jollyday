
<?php echo CHtml::tag('div', array(
        'class' => 'trShowCompactProfile search-column'.$columnNumber,
        'data-user-id' => $user->id,
        'data-link' => J::url('profile/loadprofile'),
        'data-current-user' => Yii::app()->user->id,
), false, false);?>
    <div class="search-result-photo-wrapper">
        <div class="photo-wrapper">
             <?php echo CHtml::image($user->getUserpic('medium'), '', array(
                        'width' => Photo::SIZE_MEDIUM_X,
                        'height' => Photo::SIZE_MEDIUM_Y)); ?>
         </div>
         <div class="search-photo-description">
             <?php echo CHtml::encode($user->name); ?>, <?php echo $user->getAge(); ?>, (<?php echo $user->getZodiac(); ?>) 
         </div>
    </div>
<?php echo CHtml::closeTag('div'); ?>
