<div id="fancybox-textured-wrapper">
    Вы собираетесь сделать подарок пользователю: <span class="color-blue"><b><?php echo $viewData['user']['name']; ?></b></span>
</div>
<div class="fancybox-content-thin">
    <table id="fancybox-table">
        <tr>
            <td class="fancybox-table-column">
                <?php echo CHtml::image($viewData['gift']->imageURLBig, '', array('width'=>240,'height'=>240));?>
            </td>
            <td id="fancybox-table-column-arrow">
            </td>
            <td class="fancybox-table-column">
                <div class="photo-wrapper">
                    <?php if($viewData['user']['userpic']) : ?>
                        <?php echo CHtml::image(Photo::getUploadFolderURL($viewData['user']['id']) . $viewData['user']['userpic'], '', array(
                            'width' => Photo::SIZE_MEDIUM_X,
                            'height' => Photo::SIZE_MEDIUM_Y)
                        )?>
                    <?php else :?>
                        <?php echo CHtml::image(User::getNoPic('medium'));?>
                    <?php endif; ?>
                </div>
            </td>
        </tr>
    </table>
    <?php if(!empty($viewData['form']['postcard'])) : ?>
        <div id="gift-postcard-wrapper-wrapper">
            <div id="gift-postcard-wrapper">
                <?php echo Yii::app()->format->formatText($viewData['form']['postcard']); ?>
            </div>
        </div>
    <?php endif; ?>
</div>