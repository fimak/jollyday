<h5> </h5>

<div class="photo-wrapper">
    <?php echo CHtml::image(User::getBlaclistPic('medium'), '', array(
                        'width' => Photo::SIZE_MEDIUM_X,
                        'height' => Photo::SIZE_MEDIUM_Y));?>
</div>

<?php if($blacklistStatus == Blacklist::STATUS_OWN) : ?>
    <div class="buttons-blacklist">
        <?php echo CHtml::link('Убрать из чёрного списка', 'javascript:void(0)', array(
                'class' => 'trLoadWhitelistForm',
                'data-link' => J::url('offer/loadwhitelistform' , array('id' => $userID)),
                'data-user-id' => $userID
        ))?>
    </div>
<?php endif;?>