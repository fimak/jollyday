<h4>Мои фото (<span class="own-photo-counter"><?php echo $photosCount;?></span>)</h4>

<div class="backlink-wrapper">
    <?php echo CHtml::link('перейти к моей странице', 'javascript:void(0)', array(
            'class' => 'backlink trLoadCompactMessages',
            'data-link' => J::url('profile/loadrecentmessages')
     ))?>    
</div>

<div id="album-container">
    <?php foreach ($photos as $photo) : ?>
        <?php $this->renderPartial('theme.views.app.photo._photo', array(
                'url' => $photo['filename_medium'],
                'id' => $photo['id'],
        ))?>
    <?php endforeach; ?>
</div>