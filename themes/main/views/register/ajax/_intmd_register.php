<div id="intmdreg-title">Хочешь познакомиться с <?php echo Yii::t('gender' , 'whom' , (int)$user['id_gender'])?> поближе?</div>
<div class="fancybox-content">
    <div id="intmdreg-right-column">
        <div class="photo-wrapper">
            <?php echo CHtml::image(Photo::getUploadFolderURL($user['id']) . $user['userpic'], '', array(
                        'width' => Photo::SIZE_MEDIUM_X,
                        'height' => Photo::SIZE_MEDIUM_Y))?>
        </div>
        <div id="intmdreg-username">
            <?php echo CHtml::encode($user['name']);?>, <?php echo J::age($user['birthday']); ?>
        </div>
    </div>
    <div id="intmdreg-left-column">
        <div>
            <span class="left-column-title">Нет ничего проще!</span><br />
            <?php echo CHtml::link('Авторизируйтесь', "javascript:void(0)",array(
                            'class' => 'trShowLoginForm',
                            'data-link' => J::url('/site/loginform'),
                    )
            ); ?>
            на сайте и познакомтесь с <?php echo Yii::t('gender' , 'whom' , (int)$user['id_gender'])?> в один клик!<br /><br />
            <p class="left-column-title">Еще не зарегистрировались?</p>
            Всего несколько шагов - и все возможности сайта станут полностью доступны<br /> для Вас!<br /><br />
            <ul>
                <li>Это абсолютно бесплатно!</li>
                При регистрации и пользовании большинством услуг сайта оплата не требуется.
                <li>Это очень удобно!</li>
                Анкеты и фото пользователей доступны в результатах поиска. Без переходов!<br /> 
                А для знакомства с выбранным человеком вам потребуется всего один клик.
                <li>Это безопасно!</li>
                Ваш номер телефона  и другие приватные данные будут надежно защищены.
                <li>Это выгодно!</li>
                При регистрации Вас ждут специальные <b>БОНУСЫ</b> и <b>ГАРАНТИИ ЗНАКОМСТВА.</b>
            </ul>
        </div>
        <div id="intmdregbtn">
            <?php echo CHtml::tag('button', array(
                    'class' => 'trLoadRegisterForm',
                    'data-link' => J::url('/register/ajax/form'),
            ), 'Регистрация', true); ?>
        </div>
    </div>
</div>

