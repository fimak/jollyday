<h5 class="color-blue">Выбери способ знакомста:</h5>

<div class="meetmethods-big">
    <?php foreach ($listMethods as $method) : ?> 
        <?php if(in_array($method['id'], $userMethods)) : ?>    
            <?php echo CHtml::tag('div', array(
                    'class' => 'trTooltipMeetmethodBig trIntmdRegister mm-icon-big active ' . $method['htmlClass'],
                    'data-link' => J::url('register/ajax/intmdregister'),
                    'data-user-id' => $userID,
                    'data-place' => 'profile',
                    'data-method-id' => $method['id'],
            ), '');?>
            <div class="tooltip-meetmethod">
                <?php echo $method['description']; ?>
            </div>
        <?php else : ?>            
            <?php echo CHtml::tag('div', array('class' => 'mm-icon-big unactive '.$method['htmlClass']), '')?>
        <?php endif; ?>     
    <?php endforeach; ?>     
</div>

<div class="link-how-to-meet">
    <?php echo CHtml::link('Как написать сообщение?', 'javascript:void()', array(
        'class' => 'trHowToMeet',
        'data-link' => J::url('/site/howtomeet'),
    ))?>
</div>