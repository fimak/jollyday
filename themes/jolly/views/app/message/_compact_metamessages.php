<h4>Мои собщения</h4>
    <div id="compact-offer-container" class="clearfix">
        <?php $i = 1; ?>  
        <?php foreach($metaMessages as $metamessage) : ?>
            <?php if($i == 4) $i = 1; ?>
            <?php if($metamessage->type == Metamessage::TYPE_MESSAGE) : ?>
                <div class="profile-offer<?php echo isset($i) ? ($i % 3 == 0 ? ' third' : '') : ''?>" id="o<?php echo $metamessage->relatedEntity->id?>">
                    <?php $this->renderPartial('theme.views.app.message._offer_compact', array(
                            'offer' => $metamessage->relatedEntity,
                    ))?>
                </div>
            <?php elseif($metamessage->type == Message::TYPE_GIFT) : ?>
                Подарок!
            <?php endif;?>

            <?php $i++;?>
        <?php endforeach;?>
    </div>
<div class="alone-button">
    <?php echo CHtml::link('Посмотреть все мои сообщения', array('/app/message/index')); ?>
</div>