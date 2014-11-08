<h2><?php echo CHtml::image("/images/fancybox-icons/blacklist.png", "icon", array('width'=>28,'height'=>28));?>Убрать из чёрного списка</h2>
<div class="fancybox-content">

    Убрать пользователя из чёрного списка?
    
    <div class="fancybox-buttons">
        <?php echo CHtml::tag(
                'button',
                array(
                        'type' => 'button', 
                        'class' => 'button-square orange trWhitelist',
                        'data-link' => J::url('offer/towhitelist'),
                        'data-user-id' => $id_user
                ),
                'Убрать');?>
        <?php echo CHtml::tag('button', array('type' => 'button', 'class' => 'button-square aquamarine' , 'onClick' => '$.fancybox.close()'), 'Отмена');?>
    </div>
</div>