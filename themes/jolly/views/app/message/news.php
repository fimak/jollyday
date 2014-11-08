<?php $this->pageTitle = 'Оповещения' . ' - ' . Yii::app()->name;?>

<h1 id="page-header">Оповещения</h1>

<div id="news-container">
    <?php foreach ($news as $item) : ?>
     <div class="notice-container clearfix">
            <div class="item-left">
                <div class="photo-wrapper">
                    <?php echo CHtml::image($item['image'], $item['title'], array(
                            'width' => Photo::SIZE_MEDIUM_X,
                            'height' => Photo::SIZE_MEDIUM_Y,
                    )); ?>
                </div>
            </div>
            <div class="item-right">
                <div class="timestamp"><?php echo J::ago($item['date']);?></div>
                <div class="notice-header<?php echo $item['status'] == News::STATUS_UNREAD ? ' new-notice' : ''?>">
                    <?php echo CHtml::encode($item['title']);?>
                </div>
                <div class="notice-msg"><?php echo $item['text'];?></div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php $this->widget('ext.yiinfinite-scroll.YiinfiniteScroller', array(
        'contentSelector' => '#news-container',
        'itemSelector' => 'div.notice-container',
        'loading' => array(
                'finishedMsg' => 'Показаны все оповещения',
                'msgText' => ' ',
                'img' => Yii::app()->theme->baseUrl . '/img/loading.gif',
        ),
        'pages' => $pages,
        'addFormData' => false,
        'debug' => false,
        'nextLinkTitle' => '',
));?>  