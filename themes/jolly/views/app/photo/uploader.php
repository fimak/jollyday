<?php $this->pageTitle = 'Загрузка фотографий' . ' - ' . Yii::app()->name;?>

<div id="uploader-header-wrapper">
    <h1 id="page-header">Загрузка фотографий</h1>
</div>

<div id="photo-uploader-wrapper">  
    <div id="photo-uploader-button-wrapper">
        <div id="photo-uploader-button-inner">
            <form>
                <div id="photo-uploader-button">
                    <?php $this->widget('application.widgets.JFileUpload.JFileUpload', array(
                            'id' => 'photo-upload-field',
                            'model' => $model,
                            'attribute' => 'file',
                            'config' => array(
                                    'url' => J::url('/app/photo/upload'),
                                    'dataType' => 'html',
                                    'limitMultiFileUploads' => 1,
                                    'sequentialUploads' => true,
                                    'limitConcurrentUploads' => 1,
                                    'change' => "js:function(e, data){
                                        $(this).data('count-total', data.files.length);
                                        $(this).data('count-progress', 0);
                                        $(this).data('count-success', 0);
                                        $(this).data('count-fail', 0);
                                        
                                        $('#uploader-count-progress').html('0');
                                        $('#uploader-count-total').html('0');
                                        $('#upload-progress').progressbar('value', 0);

                                        $('#uploader-count-total').html($(this).data('count-total'));
                                        $('#photo-uploader-button-wrapper').addClass('hide');
                                        $('#photo-uploader-progress-wrapper').removeClass('hide');
                                        $('#uploader-information').hide();
                                        
                                    }",
                                    'done' => "js:function(e, data){       
                                        var countSuccess =  parseInt($(this).data('count-success'));            
                                        var countFail =     parseInt($(this).data('count-fail'));      
                                        var countProgress = parseInt($(this).data('count-progress'));    

                                        if(data.result != ''){
                                            $('#album-container').prepend(data.result);    
                                            $('#uploader-count-success').html(++countSuccess);
                                            $(this).data('count-success', countSuccess);
                                        }
                                        else{
                                            $('#uploader-count-fail').html(++countFail);
                                            $(this).data('count-fail', countFail);
                                        }
                                        $('#uploader-count-progress').html(++countProgress);
                                        $(this).data('count-progress', countProgress);    
                                    }",
                                    'fail' => "js:function(e, data){
                                        var countFail = parseInt($(this).data('count-fail'));
                                        var countProgress = parseInt($(this).data('count-progress'));

                                        $('#uploader-count-fail').html(++countFail);
                                        $(this).data('count-fail', countFail);

                                        $('#uploader-count-progress').html(++countProgress);
                                        $(this).data('count-progress', countProgress);
                                    }",
                                    'always' => "js:function(e, data){
                                        var countTotal = $(this).data('count-total');
                                        var countProgress = $(this).data('count-progress');
                                        var countSuccess = $(this).data('count-success');
                                        
                                        $('#upload-progress').progressbar('value', 100 / countTotal * countProgress);
                                        $('#uploader-count-progress').html(countProgress);

                                        if(countProgress == countTotal){
                                            $('#photo-uploader-progress-wrapper').addClass('hide');
                                            $('#photo-uploader-message-wrapper').notice('upload-success', 'Успешно загружено <b>' + countSuccess + '</b> фото из <b>' + countTotal + '</b>', 3000, function(){});
                                            $('#photo-uploader-button-wrapper').removeClass('hide');
                                        }
                                    }"
                            ),               
                    ))?>
                    Добавить фото
                </div>
            </form>
        </div>
    </div>
    <div id="photo-uploader-progress-wrapper" class="hide">
        <?php $this->widget('zii.widgets.jui.CJuiProgressBar', array(
            'value' => 0,
            'cssFile' => false,
            'htmlOptions' => array(
                    'id' => 'upload-progress'
            )
        )); ?>
        <div id="photo-uploader-progress-info">
            Загружено <span id="uploader-count-progress">0</span> фото из <span id="uploader-count-total"></span>
        </div>       
    </div>
    
    <div id="photo-uploader-message-wrapper" class="hide"></div>
    
    <div id="backlink-uploader-wrapper">
        <?php echo CHtml::link('перейти к моей странице',array('/app/profile/index'), array('class' => 'backlink'))?>
    </div>
</div>

<div id="uploader-information">
Вы можете загрузить одновременно несколько фотографий.<br/>
Для этого удерживайте CTRL или SHIFT при выборе фото.

<br /><br />
Допускается загрузка фотографий в формате *png, *jpg размером не более 10МБ

</div>

<div id="album-container">
    <?php foreach ($photos as $photo) : ?>
        <?php $this->renderPartial('theme.views.app.photo._photo', array(
                'url' => $photo['filename_medium'],
                'id' => $photo['id'],
        ))?>
    <?php endforeach; ?>
</div>

<?php
    Yii::app()->clientScript->registerScript('uploader-fixer',"
            $(document).ready(function(){
                var offset = $('#photo-uploader-wrapper').offset();

                $(window).scroll(function(){
                    if ($(window).scrollTop() > offset.top) {
                        $('#photo-uploader-wrapper').css({
                            'top': '0',
                            'position': 'fixed'
                        });
                    }
                    else {
                        $('#photo-uploader-wrapper').css({
                            'position': 'relative',
                            'width' : '960px'
                        });
                    };
                });

            });
    ");
?>