<?php foreach($users as $user) : ?>
    <div class="span3 backend-photo-wrapper">
        <?php echo CHtml::image($user->getUserpic('medium'), '', array(
                'class' => 'img-polaroid',
        ))?>
        <?php echo CHtml::link('Удалить', 'javascript:void(0)', array(
                'data-url-delete' => J::url('/audithory/toprated/ban', array('uid' => $user->id)),
                'class' => 'toprated-delete-link',
        ))?>
        |
        <?php echo CHtml::link('Просмотр', array('/audithory/user/view', 'id' => $user->id), array(
                'target' => '_blank'
        ))?>
    </div>
<?php endforeach; ?>