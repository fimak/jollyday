<h4>Моя анкета</h4>

<div class="backlink-wrapper">
    <?php echo CHtml::link('перейти к моей странице', 'javascript:void(0)', array(
            'class' => 'backlink trLoadCompactMessages',
            'data-link' => J::url('profile/loadrecentmessages')
     ))?> 
</div>

<div id="json-response-questionary" class="flash-message"></div>

<div class="form">
    <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'questionary-form',
            'htmlOptions' => array( 
                    'class' => 'form-light clearfix', 
            ),         
    )); ?>

            <div class="row">
                <div class="left-medium">
                    С кем я познакомлюсь:
                </div>
                <div class="right-medium">
                    <?php echo $form->checkBoxList($profile,'seekingIds', JGender::getFormattedList('nominative'), array(
                            'template' => '{input} {label}',
                            'labelOptions' => array(
                                    'style'=>'display:inline;font-weight:normal',
                            )
                    ));?>
                    <?php $seekinngListID = CHtml::activeId($profile, 'seekingIds'); ?>
                    <?php Yii::app()->getClientScript()->registerScript('seeking-checkboxes',"
                            $('#$seekinngListID :checkbox').change(function(){
                                    if($('#$seekinngListID :checkbox:checked').length == 0){
                                            $('#$seekinngListID :checkbox').not($(this)).attr('checked','checked').trigger('refresh');
                                    }
                            });
                    ");?>
                    <?php echo $form->error($profile,'seekingIds'); ?>
                </div>        
            </div>
    
            <div class="row">
                    <div class="left-medium">
                        В возрасте:
                    </div>
                    <div class="right-medium">
                        <?php $minID = CHtml::activeId($profile, 'age_min'); $maxID = CHtml::activeId($profile, 'age_max'); ?>
                        от
                        <?php echo $form->dropDownList($profile, 'age_min', Profile::getAgeList(), array(
                                'onChange' => "
                                    if(parseInt($('#$minID').val()) > parseInt($('#$maxID').val()) && $('#$minID').val() != '' && $('#$maxID').val() != ''){ 
                                        $('#$maxID').val($('#$minID').val());
                                        $('#$maxID, #$minID').trigger('refresh');
                                    }
                                ",
                                'prompt' => '',
                                'class' => 'input-short',
                        ))?>        
                        до 
                        <?php echo $form->dropDownList($profile, 'age_max', Profile::getAgeList(), array(
                                'onChange' => "
                                    if(parseInt($('#$maxID').val()) < parseInt($('#$minID').val()) && $('#$minID').val() != '' && $('#$maxID').val() != ''){ 
                                        $('#$minID').val($('#$maxID').val());
                                        $('#$maxID, #$minID').trigger('refresh');
                                    }
                                ",
                                'prompt' => '',
                                'class' => 'input-short',
                        ))?> 
                    </div>
            </div>

            <div class="row">
                    <div class="left-medium">
                        Цель знакомства:
                    </div>
                    <div class="right-medium">
                        <?php echo $form->checkBoxList($profile,'targetIds', JTarget::getList(), array(
                                'template' => '{input} {label}',
                                'labelOptions' => array(
                                        'style'=>'display:inline;font-weight:normal',
                                )
                        ));?>
                        <?php echo $form->error($profile,'targetIds'); ?>
                    </div>
            </div>
    
            <div class="row">
                    <div class="left-medium">
                        Ориентация:
                    </div>
                    <div class="right-medium">
                        <?php echo $form->dropDownList($profile, 'id_orientation', JOrientation::getList(), array('class' => 'input-long')); ?>
                        <?php echo $form->error($profile,'id_orientation'); ?>
                    </div>
            </div>    
    
            <div class="row">
                    <div class="left-medium">
                        Состоите ли вы в отношениях?
                    </div>
                    <div class="right-medium">
                        <?php echo $form->dropDownList($profile, 'id_status', JStatus::getList(), array('class' => 'input-long')); ?>
                        <?php echo $form->error($profile,'id_status'); ?>
                    </div>
            </div> 
    
            <div class="row">
                    <div class="left-medium">
                        Есть ли у вас дети?
                    </div>
                    <div class="right-medium">
                        <?php echo $form->dropDownList($profile, 'id_children', JChildren::getList(), array('class' => 'input-long')); ?>
                        <?php echo $form->error($profile,'id_children'); ?>
                    </div>
            </div> 
    
            <div class="row">
                    <div class="left-medium">
                       Мой рост, см
                    </div>
                    <div class="right-medium">
                        <?php echo $form->dropDownList($profile, 'height', Profile::getHeightList(), array('class' => 'input-short')); ?>
                        <?php echo $form->error($profile,'height'); ?>
                </div>
            </div>  
    
            <div class="row">
                    <div class="left-medium">
                       Мой вес, кг
                    </div>
                    <div class="right-medium">
                        <?php echo $form->dropDownList($profile, 'weight', Profile::getWeightList(), array('class' => 'input-short')); ?>
                        <?php echo $form->error($profile,'weight'); ?>
                    </div>
            </div>   
    
            <div class="row">
                    <div class="left-medium">
                       Материальное положение:
                    </div>
                    <div class="right-medium">
                        <?php echo $form->dropDownList($profile, 'id_welfare', JWelfare::getList(), array('class' => 'input-long')); ?>
                        <?php echo $form->error($profile,'id_welfare'); ?>
                    </div>
            </div>     
    
            <div class="row">
                    <div class="left-medium">
                        Наличие жилья:
                    </div>
                    <div class="right-medium">
                        <?php echo $form->dropDownList($profile, 'id_housing', JHousing::getList(), array('class' => 'input-long')); ?>
                        <?php echo $form->error($profile,'id_housing'); ?>
                    </div>
            </div> 
    
            <div class="row">
                    <div class="left-medium">
                        У меня есть
                    </div>
                    <div class="right-medium">
                        <?php echo $form->checkBoxList($profile,'ihaveIds', JIhave::getList(), array(
                                'template' => '{input} {label}',
                                'labelOptions' => array(
                                        'style'=>'display:inline;font-weight:normal',
                                )
                        ));?>
                        <?php echo $form->error($profile,'ihaveIds'); ?>
                    </div>
            </div>    
    
            <div class="row buttons-questionary">
                    <?php echo CHtml::ajaxSubmitButton('Сохранить', array('questionary/update'), 
                            array(
                                    'update' => '#ajax-container',
                                    'url' => array('questionary/update'),
                                    'dataType' => 'json',
                                    'success' => 'function(data){
                                            $(document).scrollTop($("#ajax-block").offset().top);
                                            moveToAnchor("#ajax-block",300);
                                            $("#json-response-questionary").notice(data.status, data.message, 3000);
                                            if(data.html != null){
                                                    $("#u'.$userID.' .profile-column-left").html(data.html);
                                            }
                                    }',
                            ),
                            array(
                                    'id' => 'profile-submit-uid-'.uniqid(),
                                    'name' => 'profile-submit',
                                    'class' => 'button-square orange'
                            )
                    ); ?>
            </div>
    <?php $this->endWidget(); ?>
</div>