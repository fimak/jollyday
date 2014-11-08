<div class="profile-column-right-offered-info-top">
    <?php if(Yii::app()->user->isMyId($offerData['id_sender'])) : ?>
        <div class="color-dark-grey">Вы уже сделали предложение:</div>
    <?php else : ?>
        <div class="color-blue">Вам сделано предложение:</div>
    <?php endif; ?>
</div>
<div class="profile-column-right-offered<?php echo $offerData['status'] == Offer::ACCEPTED ? ' trDialogRedirect' : ''?>" 
     data-dialog-url="<?php echo J::url('message/dialog', array('id' => Yii::app()->user->isMyId($offerData['id_sender']) ? $offerData['id_reciever'] : $offerData['id_sender']))?>">
    <?php echo CHtml::tag('span', array(
            'class' => 'mm-icon-giant ' . $method['htmlClass']
    ), ' '); ?>   
</div>
<div class="profile-column-right-offered-info">
    <div class="color-blue"><?php echo $method['description'];?></div>
</div>
<div class="profile-column-right-offered-info">        
    <?php if($offerData['status'] == Offer::ACCEPTED) : ?>
        <div class="color-blue">Согласие получено</div>
    <?php else :?>
        <?php if(!Yii::app()->user->isMyId($offerData['id_sender'])) : ?>
            <div class="color-orange">Ожидается ваш ответ</div>
        <?php endif; ?>
    <?php endif; ?>
</div>