 <h2><?php echo CHtml::image("/images/fancybox-icons/offer.png", "icon", array('width'=>28,'height'=>28));?>Сделать уведомление о предложении</h2>
<div class="fancybox-content-padding-thin">
    <div id="fancybox-textured-wrapper">
        <div id="offer-request-text">
            Хочешь ускорить знакомство с <?php echo Yii::t('gender', 'whom', (int)$user->id_gender)?>?
            Отправь <?php echo Yii::t('gender', 'him', (int)$user->id_gender)?> предложение по СМС!
        </div>
    </div>
    <div class="fancybox-content-padding-thick">
        <table id="fancybox-table">
            <tr>
                <td class="fancybox-table-column">
                    <div class="smsnotice-pic"></div>
                </td>
                <td id="fancybox-table-column-arrow-double">
                    
                </td>
                <td class="fancybox-table-column">
                    <div class="photo-wrapper">
                        <?php echo CHtml::image($user->getUserpic('medium'), '', array(
                        'width' => Photo::SIZE_MEDIUM_X,
                        'height' => Photo::SIZE_MEDIUM_Y)); ?>    
                    </div>
                    <h5 class="color-blue"><b><?php echo CHtml::encode($user->name); ?>, <?php echo $user->getAge() ;?> (<?php echo $user->getZodiacDescription($user->birthday)?>)</b></h5>
                </td>
            </tr>
        </table>
        <div id="offernotice">
            Всего за <span class="cost-offernotice">10 рублей</span> 
            <?php echo Yii::t('gender', 'he', (int)$user->id_gender)?> получит СМС уведомление о твоем предложении ПРЯМО СЕЙЧАС!           
            <div id="offernotice-bonus">
                БОНУС: Ваше предложение будет первым в списке, пока <?php echo Yii::t('gender', 'he', (int)$user->id_gender)?> не ответит на него! 
            </div>
        </div>
        
        <?php echo CHtml::ajaxButton('Отправить уведомление',
                array('offer/notice', 'id' => $offerID), 
                array(
                        'type' => 'get',
                        'dataType' => 'json',
                        'success' => "js:function(data){
                            $.fancybox.close(true);
                            
                            if(data.status == 'error')
                                return false;
                            
                            if(data.status == 'success'){
                                $('#account').html(data.account);
                                $('#word-money').html(data.wordMoney);
                                $('#fancybox-container').html(data.html);

                                $.fancybox({
                                    href : '#fancybox-container',
                                    scrolling : 'no', 
                                    autoSize: false,
                                    autoWidth : false,
                                    autoHeight: true,
                                    fitToView: false,
                                    width : 730,
                                    openSpeed: 0,
                                    closeSpeed: 0,
                                    autoCenter: false,
                                    padding: 0,
                                    afterClose: function(){ 
                                        $('#fancybox-container').html(''); 
                                    },
                                    afterShow : function(){
                                        if(ltie8){
                                            resetPie('.fancybox-skin');
                                            resetPie('.fancybox-skin h2');
                                            resetPie('.fancybox-skin button');
                                            resetPie('.fancybox-skin input[type=\'submit\']');
                                        }
                                    }
                                });
                            }
                        }",
                ), 
                array(
                        'id' => 'button-pay-offernotice'. uniqid(),
                        'class' => 'button-offernotice-pay big-button'
                )
        )?>
        <div id="rating-tip">* с Вашего счета будет списано 0,3 монеты. Доставка СМС - уведомлений происходит в период с 10:00 до 21:00 местного времени.</div>
        <div class="fancybox-buttons">
            <?php echo CHtml::tag('button', array(
                    'type' => 'button',
                    'class' => 'button-square aquamarine',
                    'onClick' => '$.fancybox.close()'),
                    'Отмена'
            );?>
        </div>
    </div>
</div>

<?php
// регистрируем скрипт для проверки достаточно ли средств что бы отправить смс
Yii::app()->clientScript->registerScript('checkcost',"
    $(document).ready(function(){
        $('#offerNotice').change(function(){
            var checked = $(this).prop('checked');
            var account = parseFloat($(this).data('account'));
            var cost = parseFloat($(this).data('cost'));

            if((checked) && (cost > account)){
                $('#offer-submit-button').attr('disabled', 'disabled');
            }
            else{
                $('#offer-submit-button').removeAttr('disabled');
            }
        });
    });
", CClientScript::POS_READY)
?>