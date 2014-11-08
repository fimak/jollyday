<?php $this->pageTitle = 'Сообщения' . ' - ' . Yii::app()->name;?>

<?php $this->renderPartial('_filter', array(
        'filter' => $filter,
))?>

<div id="search-container">
    <?php if(count($models) > 0) : ?>
            <?php foreach($models as $model) : ?>
                    <div class="search-result clearfix">
                        <?php 
                                switch ($filterType)
                                {
                                        case 'blacklist': $this->renderPartial('theme.views.app.profile._profile', array(
                                                    'user' => $model,
                                                    'methodList' => $methodList,
                                                    'profileType' => 'blacklist',
                                                    'gifts' => User::getGifts($model->id),
                                                )); 
                                                break;
                                        default :
                                                if($model->type == Metamessage::TYPE_MESSAGE)                                
                                                        $this->renderPartial('theme.views.app.profile._profile', array(
                                                            'offer' => $model->relatedEntity,
                                                            'user' => $model->relatedEntity->interlocutor,
                                                            'methodList' => $methodList,
                                                            'profileType' => 'message',
                                                            'gifts' => User::getGifts($model->relatedEntity->interlocutor->id),
                                                        )); 
                                                break;
                                }
                        ?>
                    </div>
            <?php endforeach; ?>
    <?php else: ?>
    
    <div class="no-message-results">
        Пока у вас нет ни одного сообщения.<br />
        Начните ваши знакомства с <?php echo CHtml::link('поиска', '/search')?>
    </div>
    
    <?php endif;?>
</div>

<?php $this->widget('ext.yiinfinite-scroll.YiinfiniteScroller', array(
        'contentSelector' => '#search-container',
        'itemSelector' => 'div.search-result',
        'loading' => array(
                'finishedMsg' => 'Показаны все сообщения',
                'msgText' => ' ',
                'img' => Yii::app()->theme->baseUrl . '/img/loading.gif',
        ),
        'pages' => $pages,
        'addFormData' => true,
        'debug' => true,
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
));?>   