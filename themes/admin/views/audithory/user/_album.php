<?php foreach($photos as $photo) : ?>
    <div class="span3 backend-photo-wrapper">
        <?php echo CHtml::image(Photo::getUploadFolderURL($userId) . $photo['filename_medium'], '', array(
                'width' => Photo::SIZE_MEDIUM_X,
                'height' => Photo::SIZE_MEDIUM_Y,
                'class' => 'img-polaroid'
        ))?>
        
        <?php echo CHtml::link('Удалить', 'javascript:void(0)', array(
                'data-url-delete' => J::url('/audithory/user/deletephoto', array('id' => $photo['id'])),
                'onClick' => "
                    link = $(this);
                    $.ajax({
                        url: $(this).data('url-delete'),
                        dataType: 'json',
                        success: function(data){
                            if(data.status == true){
                                console.log($(this));
                                $(link).parent().fadeOut('fast');
                            }
                        }
                    });
                ",
        ))?>
        
    </div>
<?php endforeach;?>
