<?php $this->pageTitle = Yii::app()->name. ' :: ' . 'Поиск ' . Yii::t('gender' , 'searchtarget' , (int)$model['gender']) . ' в возрасте от ' . Profile::formatAgeInterval($model['minAge'],$model['maxAge'], " до ") . '.' ;?>

<div id="search-container">
    <?php if(count($users) > 0) : ?>
                <?php foreach($users as $user) : ?>
                    <div class="search-result">
                        <?php $this->renderPartial('theme.views.profile._profile', array(
                                'user' => $user,
                                'methodList' => $methodList,
                                'profileType' => 'search',
                                'gifts' => User::getGifts($user->id),
                        ))?>
                    </div>
                <?php endforeach;?>

            <?php $this->widget('ext.yiinfinite-scroll.YiinfiniteScroller', array(
                    'contentSelector' => '#search-container',
                    'itemSelector' => 'div.search-result',
                    'loading' => array(
                            'finishedMsg' => 'Больше результатов нет',
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
                    }"
            ));?>   
    <?php else: ?>
    
    
    <div id="no-search-results">По выбранным критериям нет результатов</div>
    
    
    <?php endif;?>
</div> 