<?php $i = 1?>
    <?php foreach($users as $item) : ?>
        <div class="faceribbon-position" data-place="<?php echo $pageSize * ($page - 1) + $i ?>">
            <?php echo CHtml::image($item['userpic'] != null ? Photo::getUploadFolderURL($item['id']) .$item['userpic'] : User::getNoPic('faceribbon'),
                        $item['username'],
                        array(
                            'data-user-id' => $item['id'],
                            'data-link' => J::url('/site/loadProfile'),
                            'class' => 'trLoadFaceribbonProfile'
                        )
            );?>
        </div>
    <?php $i++; ?>
<?php endforeach; ?>
