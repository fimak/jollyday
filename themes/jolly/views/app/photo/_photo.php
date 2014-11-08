<div class="photo-container" id="p<?php echo $id?>">
    <div class="photo-edit-wrapper">
         <div class="photo-wrapper">
             <?php echo CHtml::image($url, '', array(
                        'width' => Photo::SIZE_MEDIUM_X,
                        'height' => Photo::SIZE_MEDIUM_Y)); ?>
         </div>
         <div class="photo-operations-wrapper">
             <div class="photo-button btn-userpic">
                <?php echo CHtml::link('Сделать аватаркой', 'javascript:void(0)', array(
                            'class' => 'trUseAsUserpic greybluelink',
                            'data-link' => J::url('photo/set'),
                            'data-photo-id' => $id,
                            'data-user-id' => Yii::app()->user->id
                 ))?>                 
             </div>
             <div class="photo-button btn-delete">
                <?php echo CHtml::link('Удалить', 'javascript:void(0)', array(
                        'class' => 'trDeletePhoto greybluelink',
                        'data-link' => J::url('photo/delete'),
                        'data-photo-id' => $id,
                        'data-user-id' => Yii::app()->user->id
                 ))?>                 
             </div>
         </div>
    </div>
</div>