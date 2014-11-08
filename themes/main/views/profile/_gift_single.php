<li<?php echo isset($i) ? ($i > Gift::COUNT_ON_PROFILE ? " class='overhide'" : '') : '' ?>>
    <div class="gift trTooltipGift">    
        <?php echo CHtml::image(Gift::getImageBaseUrl().$gift['image'])?>
    </div>
    <div class="tooltip-gift">
        <div class="tooltip-gift-header">
            <?php echo CHtml::image($gift['userpic'] != null ? Photo::getUploadFolderURL($gift['id_sender']) .$gift['userpic'] : User::getNoPic('small'))?>
            <span class="username"><?php echo CHtml::encode($gift['name'])?></span>, <?php echo J::age($gift['birthday']); ?>, <?php echo User::getZodiacDescription($gift['birthday'])?><br /><?php echo $gift['city']?>
        </div>
        <?php if(!empty($gift['postcard'])) : ?>
            <?php if(Yii::app()->user->isMyId($gift['id_reciever']) || Yii::app()->user->isMyId($gift['id_sender']) || !$gift['is_private']) : ?>
                <div class="gift-postcard"> 
                    <?php echo Yii::app()->format->formatText($gift['postcard']); ?>             
                    <?php if(Yii::app()->user->isMyId($gift['id_reciever'])): ?>
                        <div>
                            <?php echo CHtml::link('Удалить открытку', 'javascript:void(0)', array(
                                    'class' => 'trDeletePostcard',
                                    'data-link' => J::url('gift/deletepostcard' , array('id' => $gift['id'])),
                            ))?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif;?>      
    </div>  
</li>