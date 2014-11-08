<?php $i = 1?>
    <?php foreach($users as $item) : ?>
<div class="faceribbon-position<?php echo Yii::app()->user->id == $item['id'] ? ' own-position' : ''?>" data-place="<?php echo $pageSize * ($page - 1) + $i ?>">
            <div class="faceribbon-place"><?php echo $pageSize * ($page - 1) + $i ?> место</div>
            <?php echo CHtml::image($item['userpic'] != null ? Photo::getUploadFolderURL($item['id']) .$item['userpic'] : User::getNoPic('faceribbon'),
                        "",
                        array(
                            'data-user-id' => $item['id'],
                            'data-user-current-id' => Yii::app()->user->id,
                            'data-link' => J::url('profile/loadprofile'),
                            'class' => 'trLoadFaceribbonProfile trTooltipFaceribbon',
                            'width' => Photo::SIZE_FACERIBBON_X,
                            'height' => Photo::SIZE_FACERIBBON_Y,
                        )
            );?>
            <div class="faceribbon-tooltip">
                <div class="tooltip-faceribbon-header">
                    <span class="<?php echo Yii::app()->user->isMyId($item['id']) ? 'color-orange' : 'color-blue'?>"><?php echo CHtml::encode($item['username'])?></span>, 
                    <?php echo J::age($item['birthday']); ?><br />
                    <?php echo $item['city']?>
                </div>
                <div class="tooltip-faceribbon-data">
                    Ищу <?php echo Profile::formatSeeking($item['seeking'], 'genitive', false)?>
                    <?php if(!empty($item['agemin']) || !empty($item['agemax'])) : ?>
                    в возрасте <?php echo Profile::formatAgeInterval($item['agemin'], $item['agemax'])?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php $i++; ?>
<?php endforeach; ?>
