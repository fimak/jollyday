<h2><?php echo CHtml::image("/images/fancybox-icons/question.png", "icon", array('width'=>28,'height'=>28));?>Как написать сообщение?</h2>
<div class="fancybox-content-thin">
    <div id="fancybox-textured-wrapper">
            Ищешь кнопку, чтобы написать сообщение?
            <b>У нас нет такой кнопки!</b>
    </div>
    <div id="howtomeet-about-buttons"> 
        Зато есть <b><span class="color-blue"><span class="big-number">15</span> кнопок готовых предложений</span></b>:
    </div>   
    <table id="howtomeet-table">
        <tr>
            <td id="howtomeet-table-column-list">
                <span id="howitworks">Как это работает?</span>
                <ol id="howtomeet-list">
                    <li><span>Нажми на кнопку с предложением</span></li>
                    <li><span>Дождись согласия на знакомство</span></li>
                    <li><span>Общайся или договаривайся о встрече</span></li>         
                </ol>
            </td>
            <td>
                <?php echo CHtml::image('/images/common/rounded-arrow.png', '', array(
                        'width' => 101,
                        'height' => 28,
                ))?>
            </td>
            <td>
                <?php echo CHtml::image('/images/common/howtomeet_buttons.png', '', array(
                        'width' => 258,
                        'height' => 309,
                ))?>
            </td>
        </tr>
    </table>
    <?php echo CHtml::image('/images/common/heart.png', '', array(
        'id' => 'image-heart'
    ))?>   
    <div id="howtomeet-guarantee">
        <span class="color-blue">Это гарантия взаимной симпатии до начала переписки!</span>
    </div>
    
    <div class="fancybox-content-padding-thick">
        <div class="fancybox-buttons">
            <?php echo CHtml::tag('button', array(
                    'type' => 'button', 
                    'class' => 'button-square aquamarine' , 
                    'onClick' => '$.fancybox.close()'), 
            'Готово');?>
        </div>
    </div>
</div>
