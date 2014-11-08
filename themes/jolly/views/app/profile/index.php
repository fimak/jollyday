<?php $this->pageTitle = 'Мой профиль' . ' - ' . Yii::app()->name;?>

<script type="text/javascript">
  function moveToAnchor(anchorName, duration)
  {
      destination = $(anchorName).offset().top;
      if($.browser.safari){
        $('body').animate( { scrollTop: destination }, duration );
      }else{
        $('html').animate( { scrollTop: destination }, duration );
      }
      return false;      
  }
</script> 

<?php
   $this->renderPartial('_profile', array(
        'user' => $user,
        'profileType' => $profileType,
        'gifts' => User::getGifts($user->id),
   ));
?>

<div id="gift-profile-container"></div>

<a id="ajax-block"></a>
<div id="ajax-container">
    <?php $this->renderPartial('theme.views.app.message._compact_metamessages', array(
            'metaMessages' => $metaMessages,
    )); ?>
</div>