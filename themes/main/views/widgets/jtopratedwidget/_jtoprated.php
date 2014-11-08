<div id="top-rated">
    <div class="title search-title">
      <?php echo CHtml::image(Yii::app()->theme->baseUrl."/img/title2.png", "Самые популярные"); ?>
    </div>

    <div id="search-container">
        <?php foreach($users as $user) : ?>
            <div class="search-result">
                <?php $controller->renderPartial('theme.views.profile._profile', array(
                        'user' => $user,
                        'methodList' => $methodList,
                        'profileType' => 'search',
                        'gifts' => User::getGifts($user->id),
                ))?>
            </div>
        <?php endforeach;?>  
    </div>
    <div id="search-result-more">
        <div id="search-result-more-title">Хочешь увидеть все анкеты?</div>
        <div id="search-result-more-button">
            <?php echo CHtml::button('Бесплатная регистрация', array(
                    'type' => 'button',
                    'class' => 'trLoadRegisterForm big-button',
                    'data-link' => J::url('/register/ajax/form')
            ))?>
        </div>
    </div>
    <?php $this->widget('ext.yiinfinite-scroll.YiinfiniteScroller', array(
            'action' => '/site/toprated.loadProfiles',
            'contentSelector' => '#search-container',
            'itemSelector' => 'div.search-result',
            'loading' => array(
                    'msgText' => ' ',
                    'img' => Yii::app()->theme->baseUrl . '/img/loading.gif',
            ),
            'pages' => $pages,
            'addFormData' => true,
            'nextLinkTitle' => '',
            'enableUserCallback' => true,
            'userCallback' => "js:function(){
                $('.jcarousel-list').jcarousel({
                    'wrap': 'circular',
                    'scroll': 1,
                    'buttonPrevHTML': '<div class=\"slider-button-prev\">',
                    'buttonNextHTML': '<div class=\"slider-button-next\">',
                    'setupCallback': function(instance){
                        $(instance.container).parent().css('overflow', 'visible');
                    },
                    'itemFirstInCallback' : carouselLazyLoader,
                });
            }",
            'errorCallback' => "js:function(){
                    $('#search-result-more').fadeIn('fast');  
            }"
    ));?>
</div>