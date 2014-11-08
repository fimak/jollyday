<?php $this->pageTitle = 'Поиск ' . Yii::t('gender' , 'searchtarget' , (int)$model['id_seeking']) . ' - ' . Yii::app()->name;?>

<h1 id="page-header">Поиск</h1>

<?php $this->renderPartial('_form', array(
        'model' => $model,
));?>

<div id="search-container" class="clearfix <?php echo $model->resultType == Search::RESULT_PHOTO ? ' container-photo-result' : ''?>">
    <?php if(count($users)) : ?>
            <?php $i = 1; ?>

                <?php foreach($users as $user) : ?>
                    <div class="clearfix search-result <?php echo $model->resultType == Search::RESULT_PHOTO ? 'result-photo' :''?>">
                        <?php if($i == 4) $i = 1?>
                        <?php $this->renderPartial($model->resultType == Search::RESULT_PHOTO ? '_result_photo' :'theme.views.app.profile._profile', array(
                                'user' => $user,
                                'methodList' => $methodList,
                                'columnNumber' => $i,
                                'profileType' => 'search',
                                'gifts' => User::getGifts($user->id),
                        ))?>
                        <?php $i++; ?>
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
                    'debug' => false,
                    'enableUserCallback' => true,
                    'userCallback' => "js:function(){
                        $('.jcarousel-list').jcarousel({
                            'wrap': 'circular',
                            'scroll': 1,
                            'buttonNextHTML': '<div class=\"slider-button-prev\">',
                            'buttonPrevHTML': '<div class=\"slider-button-next\">',
                            'setupCallback': function(instance){
                                $(instance.container).parent().css('overflow', 'visible');
                            },
                            'itemFirstInCallback' : carouselLazyLoader
                        });
                        $('.gender-icon').tooltip({
                            offset : [-2,22],
                            relative: true,
                            delay : 0
                        });
                    }",
            ));?>   
    <?php else: ?>
    
    
    <div class="no-search-results">По выбранным критериям нет результатов</div>
    
    
    <?php endif;?>
</div>