<?php $this->pageTitle = 'Регистрация :: Анкета' . ' - ' . Yii::app()->name;?>

<h1 id="page-header">Обо мне:</h1>

<div class="register-form-wrapper">
    <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'questionary-form',
            'htmlOptions' => array(
                    'class' => 'form-light'
            )
    )); ?>

        <?php $this->widget('application.extensions.yii-flash.EFlash', array(
                'keys' => array('success','error','notice'),
                'htmlOptions'=>array(
                        'class'=>'flash-message'
                ),
        )); ?>
            <div class="row">
                <div class="left-long">
                    Хочу познакомиться с:
                </div>
                <div class="right-medium">
                    <?php echo $form->checkBoxList($profile,'seekingIds', JGender::getFormattedList('ablative'), array(
                            'template' => '{input} {label}',
                            'labelOptions' => array(
                                    'style'=>'display:inline;font-weight:normal',
                            )
                    ));?>
                    <?php $seekinngListID = CHtml::activeId($profile, 'seekingIds'); ?>
                    <?php Yii::app()->getClientScript()->registerScript('seeking-checkboxes',"
                            $('#$seekinngListID :checkbox').change(function(){
                                    if($('#$seekinngListID :checkbox:checked').length == 0){
                                            $('#$seekinngListID :checkbox').not($(this)).attr('checked','checked');
                                            $('#$seekinngListID :checkbox').trigger('refresh');
                                    }
                            });
                    ");?>
                    <?php echo $form->error($profile,'seekingIds'); ?>
                </div>        
            </div>
    
            <div class="row">
                    <div class="left-long">
                        В возрасте:
                    </div>
                    <div class="right-medium">
                        <?php $minID = CHtml::activeId($profile, 'age_min'); $maxID = CHtml::activeId($profile, 'age_max'); ?>
                        от&nbsp;&nbsp;

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
                        &nbsp;&nbsp;&nbsp;до&nbsp;&nbsp;
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
                    <div class="left-long">
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
                    <div class="left-long">
                        Ориентация:
                    </div>
                    <div class="right-medium">
                        <?php echo $form->dropDownList($profile, 'id_orientation', JOrientation::getList(), array('class' => 'input-long')); ?>
                        <?php echo $form->error($profile,'id_orientation'); ?>
                    </div>
            </div>    
    
            <div class="row">
                    <div class="left-long">
                        Состоите ли вы в отношениях?
                    </div>
                    <div class="right-medium">
                        <?php echo $form->dropDownList($profile, 'id_status', JStatus::getList(), array('class' => 'input-long')); ?>
                        <?php echo $form->error($profile,'id_status'); ?>
                    </div>
            </div> 
    
            <div class="row">
                    <div class="left-long">
                        Есть ли у вас дети?
                    </div>
                    <div class="right-medium">
                        <?php echo $form->dropDownList($profile, 'id_children', JChildren::getList(), array('class' => 'input-long')); ?>
                        <?php echo $form->error($profile,'id_children'); ?>
                    </div>
            </div> 
    
            <div class="row">
                    <div class="left-long">
                       Мой рост, см
                    </div>
                    <div class="right-medium">
                        <?php echo $form->dropDownList($profile, 'height', Profile::getHeightList(), array('class' => 'input-short')); ?>
                        <?php echo $form->error($profile,'height'); ?>
                </div>
            </div>  
    
            <div class="row">
                    <div class="left-long">
                       Мой вес, кг
                    </div>
                    <div class="right-medium">
                        <?php echo $form->dropDownList($profile, 'weight', Profile::getWeightList(), array('class' => 'input-short')); ?>
                        <?php echo $form->error($profile,'weight'); ?>
                    </div>
            </div>   
    
            <div class="row">
                    <div class="left-long">
                       Материальное положение:
                    </div>
                    <div class="right-medium">
                        <?php echo $form->dropDownList($profile, 'id_welfare', JWelfare::getList(), array('class' => 'input-long')); ?>
                        <?php echo $form->error($profile,'id_welfare'); ?>
                    </div>
            </div>     
    
            <div class="row">
                    <div class="left-long">
                        Наличие жилья:
                    </div>
                    <div class="right-medium">
                        <?php echo $form->dropDownList($profile, 'id_housing', JHousing::getList(), array('class' => 'input-long')); ?>
                        <?php echo $form->error($profile,'id_housing'); ?>
                    </div>
            </div> 
    
            <div class="row">
                    <div class="left-long">
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
    
            <div class="row long_medium">
                <?php echo CHtml::tag('button', array(
                        'type' => 'submit',
                        'class' => 'button-square orange float-right'
                ),'Сохранить')?>	
            </div>
    <?php $this->endWidget(); ?>
</div>


    