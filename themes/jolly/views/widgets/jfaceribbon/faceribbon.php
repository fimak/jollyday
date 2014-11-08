<div id="faceribbon">
    <table id="faceribbon-inner">
        <tr>
            <td id="faceribbon-left-column"<?php if($position == 1) echo ' class="faceribbon-first-place"'?>>
                <?php if($messageCode == JFaceRibbon::MESSAGE_RATING) : ?>
                    <?php if($position == 1): ?>
                        <div id="faceribbon-first-place-wrapper">
                            <div class ="faceribbon-first-place-text">
                                Ваша анкета на
                            </div>
                            <div id="rating" class="faceribbon-first-place trFaceribbonNav" data-link="<?php echo J::url('/app/profile/fr.loadFaces')?>" data-direction="curr">
                                1
                            </div>
                            <div class ="faceribbon-first-place-text">
                                месте
                            </div>
                            <div id="faceribbon-first-place-congratulations">
                                Поздравляем!
                            </div>
                        </div>  
                    <?php else : ?>
                        Ваша анкета на 
                        <div id="rating-wrapper">
                            <span id="rating" class="trFaceribbonNav" data-link="<?php echo J::url('/app/profile/fr.loadFaces')?>" data-direction="curr"><?php echo $position ?></span>
                        </div>
                        месте<br />
                        <?php echo CHtml::link('Подняться наверх', 'javascript:void(0)', array(
                                'id' => 'trShowRateForm',
                                'data-link' => J::url('profile/rateform'),
                        ));?>
                        <br /> и попасть сюда <div class="faceribbon-arrow"></div>
                    <?php endif; ?>
                <?php elseif($messageCode == JFaceRibbon::MESSAGE_DELETED) : ?>   
                    Ваша анкета удалена.<br /><br />
                    А вы могли быть на <?php echo $position ?> месте...
                <?php elseif($messageCode == JFaceRibbon::MESSAGE_NOPHOTO) : ?>
                    <div id="faceribbon-nophoto-alert">НЕТ ФОТО</div>
                    <div id="faceribbon-nophoto-description">
                        <?php echo CHtml::link('Загрузить', array('/app/photo/uploader'))?><br /> 
                    </div>
                <?php endif; ?>
            </td>
            <td class ="faceribbon-arrow-td"><div id="faceribbon-nav-prev" class="trFaceribbonNav<?php if($page == 1) echo ' unactive'?>" data-link="<?php echo J::url('/app/profile/fr.loadFaces')?>" data-direction="prev"></div></td>
            <td id="faceribbon-center-column">
                    <div id="faceribbon-faces-wrapper" data-page="<?php echo $page?>" data-lastpage="<?php echo $isLastPage ?>">
                        <?php $this->render('theme.views.widgets.jfaceribbon._faces', array(
                                'users' => $users,
                                'page' => $page,
                                'pageSize' => $pageSize,
                                'isLastPage' => $isLastPage,
                        ));?>
                    </div>
            </td>
            <td class ="faceribbon-arrow-td"><div id="faceribbon-nav-next" class="trFaceribbonNav<?php if($isLastPage) echo ' unactive'?>" data-link="<?php echo J::url('/app/profile/fr.loadFaces')?>" data-direction="next"></div> </td>
            <td id="faceribbon-right-column">
                На вашем счете 
                
             
                <div id="account"><?php echo JPayment::formatAmount($account) ?></div>
                <span id="word-money"><?php echo ($account - floor($account)) != 0 ? 'монеты' :  Yii::t('jolly', 'money', (int)$account); ?></span>
                <?php echo CHtml::link('Пополнить счёт', array('/app/payment/account'), array(
                        'id' => 'faceribbon-button-account'
                ))?>
            </td>
        </tr>
    </table>
</div>

<div id="faceribbon-profile-container"></div>