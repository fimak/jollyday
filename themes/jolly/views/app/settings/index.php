<?php $this->pageTitle = 'Настройки' . ' - ' . Yii::app()->name;?>

<h1 id="page-header">Настройки</h1>

<div id="settings-wrapper">
        <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'settings-form',
                'htmlOptions'=>array(
                        'class'=>'form-light',
                ),
        )); ?>
    
        <div id="settings-contacts">
            <?php $this->widget('application.extensions.yii-flash.EFlash', array(
                    'keys' => array('success','error','notice'),
                    'htmlOptions'=>array(
                            'class'=>'flash-message'
                    ),
            )); ?>
            <div class="row">
                <div class="left-long">
                    Номер телефона:
                </div>  
                <div class="right-medium">
                    <span id="settings-phone-number"><?php echo Yii::app()->format->formatPhone($model->phone, true, true); ?></span>&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php echo CHtml::ajaxLink('Изменить номер',Yii::app()->createUrl('/app/settings/setphone'), array(
                                    'type'=>'POST',
                                    'update'=>'#fancybox-container', 
                                    'complete'=>"function(){ 
                                        $.fancybox({
                                             href : '#fancybox-container',
                                             scrolling : 'no', 
                                             autoSize: false,
                                             autoWidth : false,
                                             autoHeight: true,
                                             fitToView: false,
                                             width : 460,
                                             openSpeed: 0,
                                             closeSpeed: 0,
                                             autoCenter: false,
                                             padding: 0,
                                            afterClose: function(){ 
                                                $('#fancybox-container').html(''); 
                                            },
                                            afterShow: function(){
                                                if(ltie8){
                                                    resetPie('.fancybox-skin');
                                                    resetPie('.fancybox-skin h2');
                                                    resetPie('.fancybox-skin button');
                                                    resetPie('.fancybox-skin input[type=\'submit\']');
                                                }
                                            }
                                        }
                                    );}"
                                ),
                                array(
                                        'id' => 'set-phone-uid-'. uniqid(),
                                )
                    );?>
                </div>  
            </div>
            
            <div class="row">
                <div class="left-long">
                    Пароль:
                </div>  
                <div class="right-medium">
                    <?php echo CHtml::ajaxLink('Изменить пароль',Yii::app()->createUrl('/app/settings/setpassword'), array(
                                    'type'=>'POST',
                                    'update'=>'#fancybox-container', 
                                    'complete'=>"function(){ 
                                        $.fancybox({
                                            href : '#fancybox-container',
                                            scrolling : 'no', 
                                            autoSize: false,
                                            autoWidth : false,
                                            autoHeight: true,
                                            fitToView: false,
                                            width : 460,
                                            openSpeed: 0,
                                            closeSpeed: 0,
                                            autoCenter: false,
                                            padding: 0,
                                            afterClose: function(){ 
                                                $('#fancybox-container').html(''); 
                                            },
                                            afterShow: function(){
                                                if(ltie8){
                                                    resetPie('.fancybox-skin');
                                                    resetPie('.fancybox-skin h2');
                                                    resetPie('.fancybox-skin button');
                                                    resetPie('.fancybox-skin input[type=\'submit\']');
                                                }
                                            }
                                        }
                                    );}"
                                ),
                                array(
                                        'id' => 'set-password-uid-'. uniqid()
                                )
                    );?>
                </div>  
            </div>

            <div class="row">
                <div class="left-long">
                    Адрес электронной почты:              
                </div>  
                <div class="right-longest">
                   <?php echo !empty($model->email) ? $model->email : 'Не указан'; ?>&nbsp;&nbsp;&nbsp;&nbsp;
                   <?php echo CHtml::ajaxLink('Изменить адрес',Yii::app()->createUrl('/app/settings/setemail'), array(
                                    'type'=>'POST',
                                    'update'=>'#fancybox-container', 
                                    'complete'=>"function(){ 
                                        $.fancybox({
                                            href : '#fancybox-container',
                                            scrolling : 'no', 
                                            autoSize: false,
                                            autoWidth : false,
                                            autoHeight: true,
                                            fitToView: false,
                                            width : 460,
                                            openSpeed: 0,
                                            closeSpeed: 0,
                                            autoCenter: false,
                                            padding: 0,
                                            afterClose: function(){ 
                                                $('#fancybox-container').html(''); 
                                            },
                                            afterShow: function(){
                                                if(ltie8){
                                                    resetPie('.fancybox-skin');
                                                    resetPie('.fancybox-skin h2');
                                                    resetPie('.fancybox-skin button');
                                                    resetPie('.fancybox-skin input[type=\'submit\']');
                                                }
                                            }
                                        }
                                    );}"
                                ),
                                array(
                                        'id' => 'set-email-uid-'. uniqid()
                                )
                    );?>
                    &nbsp;&nbsp;&nbsp;
                    
                   <?php if($model->fl_newmail == 1) : ?>
                            <span id="email-activate-alert" class="color-red"><i>(адрес электронной почты <span id="email-activate"><?php echo $model->getNewEmail();?></span> еще не подтвержден)</i></span>
                   <?php else : ?>
                            <span id="email-activate-alert" class="color-red hide"><i>(адрес электронной почты <span id="email-activate"><?php echo $model->getNewEmail();?></span> еще не подтвержден)</i></span>    
                    <?php endif; ?>
                        
                  
                </div>   
            </div>    

  
        </div>
        
        <div id="settings-personalies">
            <div class="row">
            <div class="left-long">
                    <?php echo $form->label($model,'name'); ?>
                </div>   
                <div class="right-medium">
                    <?php echo $form->textField($model,'name', array('class' => 'input-long', 'maxlength' => 32)); ?>
                    <?php echo $form->error($model,'name'); ?>
                </div>   
            </div>           

            <div class="row">
                <div class="left-long">
                    <?php echo $form->label($model,'id_region'); ?>
                </div>   
                <div class="right-medium">
                    <?php echo CHtml::ActiveDropDownList($model,'id_region', Region::getList(),
                            array(
                                'ajax' => array(
                                    'type'=>'POST',
                                    'url'=>CController::createUrl('loadCities'), 
                                    'dataType'=>'json',
                                    'data'=>array('id_region'=>'js:this.value'), 
                                    'beforeSend'=>"function(){
                                        $('#settings-submit-button').attr('disabled', 'disabled');
                                    }",
                                    'success'=>'function(data) {
                                        $("#User_id_city").html(data.dropDownCities).trigger("refresh");
                                        $("#settings-submit-button").removeAttr("disabled");
                                    }'
                                ),
                                'class' => 'input-long'
                            )
                    );?>
                    <?php echo $form->error($model,'id_region'); ?>
                </div>   
            </div>         
            <div class="row">
                <div class="left-long">
                    <?php echo $form->label($model,'id_city'); ?>
                </div>   
                <div class="right-medium">
                    <?php echo $form->dropDownList($model,'id_city', 
                            isset($model->id_city) ? City::getCitiesListByRegion($model->id_region) : array('' => 'Выберите город'),
                            array('class' => 'input-long')
                    ); ?>
                    <?php echo $form->error($model,'id_city'); ?>
                </div>   
            </div>         

            <div class="row">
                <div class="left-long">
                    <?php echo $form->label($model,'id_gender'); ?>
                </div>   
                <div class="right-medium">
                    <?php echo $form->dropDownList($model,'id_gender', JGender::getList(), array('class' => 'input-long')); ?>
                    <?php echo $form->error($model,'id_gender'); ?>
                </div>   
            </div>          

            <div class="row">
                <div class="left-long">
                    Дата рождения:
                </div>   
                <div class="right-medium date-block">
                    <?php $this->widget('JDropDownDate', array(
                                'model' => $model,
                                'attribute' => 'birthday',
                                'dOptions' => array('class' => 'input-day'),
                                'mOptions' => array('class' => 'input-month'),
                                'yOptions' => array('class' => 'input-year')
                    ));?>
                    <?php echo $form->error($model,'birthday'); ?>
                </div>  

            </div>              
        
            <div id="settings-buttons" class="row">
                    <?php echo CHtml::tag('button', array(
                            'type' => 'submit',
                            'class' => 'button-square orange',
                            'id' => 'settings-submit-button'
                    ), 'Сохранить')?>
            </div>
        </div>
    <?php $this->endWidget(); ?>
        <div id="settings-delete">
            Так же вы можете
            <?php echo CHtml::ajaxLink('удалить свой аккаунт', $this->createUrl('delete'), array(
                            'type'=>'POST',
                            'update'=>'#fancybox-container', 
                            'complete'=>"function(){ 
                                $.fancybox({
                                    href : '#fancybox-container',
                                    scrolling : 'no', 
                                    autoSize: false,
                                    autoWidth : false,
                                    autoHeight: true,
                                    fitToView: false,
                                    width : 460,
                                    openSpeed: 0,
                                    closeSpeed: 0,
                                    autoCenter: false,
                                    padding: 0,
                                    afterClose: function(){ 
                                        $('#fancybox-container').html(''); 
                                    },
                                    afterShow: function(){
                                        if(ltie8){
                                            resetPie('.fancybox-skin');
                                            resetPie('.fancybox-skin h2');
                                            resetPie('.fancybox-skin button');
                                            resetPie('.fancybox-skin input[type=\'submit\']');
                                        }
                                    }
                                }
                            );}"
                        ),
                        array(
                                'id' => 'delete-profile-uid-'. uniqid()
                        )
            );?>
        </div>
    </div>