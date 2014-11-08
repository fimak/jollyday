<div class="gift-icon-wrapper<?php if(count($gifts)> 0) echo ' gift-icon-wrapper-bg'?>">
    <h4 class="gift-header">
           Подарки (<?php echo count($gifts); ?>)
    </h4>  
    <?php echo CHtml::tag('button', array(
            'class' => 'trIntmdRegister button-present',
            'data-link' => J::url('register/ajax/intmdregister'),
            'data-user-id' => $userID,
    ), ' ');?> 
</div>
<div class="gifts clearfix">     
    <?php if(count($gifts) == 0) : ?>
        <div class="nogifts-notice">
            Вы можете <?php echo CHtml::link('сделать подарок первым', 'javascript:void(0)', array(
                            'class' => 'trIntmdRegister',
                            'data-link' => J::url('register/ajax/intmdregister'),
                            'data-user-id' => $userID,
                    ))?>!
        </div>
    <?php endif;?>
        <ul class="gift-list" id="g<?php echo $userID?>">
            <?php if (count($gifts) > 0) : ?> 


                <?php $i = 1?>

                <?php foreach ($gifts as $gift) : ?>
                        <?php $this->renderPartial('theme.views.profile._gift_single', array(
                                'gift' => $gift,
                                'i' => $i
                        ))?>

                        <?php $i++ ?>
                <?php endforeach; ?>

                <?php unset($i)?>
        <?php endif; ?>
    </ul>
</div>
    
<?php if(count($gifts) > Gift::COUNT_ON_PROFILE) : ?>
    <div class="gifts-more-wrapper">
        <?php echo CHtml::tag('button', array(
                'class' => 'trMoreGifts button-square azure',
                'data-user-id' => $userID
                ), 'Ещё'
        );?>
    </div>
<?php endif;?>