<h5 class="color-orange">Меня интересует:</h5>

<div class="meetmethods-big">
    <?php foreach ($listMethods as $method) : ?>    
        <?php if(in_array($method['id'], $userMethods)) : ?>
            <?php echo CHtml::tag('div', array('class' => 'trTooltipMeetmethodBig mm-icon-big active mm-own '.$method['htmlClass']), false, false)?><?php echo CHtml::closeTag('div')?>            
            <div class="tooltip-meetmethod"><?php echo $method['description']?></div>        
        <?php else: ?>
            <?php echo CHtml::tag('div', array('class' => 'mm-icon-big unactive '.$method['htmlClass']), false, false)?><?php echo CHtml::closeTag('div')?>
        <?php endif;?>
    <?php endforeach; ?>                  
</div>
