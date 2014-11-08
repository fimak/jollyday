<?php if($user->getOnlineStatus()) : ?>
    <h6 class="online">Online</h6>
<?php else : ?>
    <h6 class="offline"><?php echo Yii::t('gender', 'was', (int)$user->id_gender);?> <?php echo J::ago($user->getLastActionDate());?> </h6>      
<?php endif; ?>

<?php $this->widget('JPhotoSlider', array(
    'photos' => $photos,
    'isOwn' => false,
    'userID' => $user->id,
))?>    
    
<div class="photo-counter">Фотографий в альбоме: <b><?php echo count($photos); ?></b></div>