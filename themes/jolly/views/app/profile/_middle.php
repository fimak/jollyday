<?php if($user->getOnlineStatus()) : ?>
    <h6 class="online">Online</h6>
<?php else : ?>
    <h6 class="offline"><?php echo Yii::t('gender', 'was', (int)$user->id_gender);?> <?php echo J::ago($user->getLastActionDate());?> </h6>      
<?php endif; ?>
            

<?php $this->widget('JPhotoSlider', array(
    'photos' => $photos,
    'isOwn' => $profileType == 'own',
    'userID' => $user->id,
))?>

<?php if($profileType == 'own') : ?>
    <div class="photo-counter">Фотографий в альбоме: <b class="own-photo-counter"><?php echo count($photos); ?></b></div>
<?php elseif($profileType != 'own') : ?>
    <div class="photo-counter">Фотографий в альбоме: <b><?php echo count($photos); ?></b></div>
<?php endif; ?>
