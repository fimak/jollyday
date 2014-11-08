<script>
    $(document).ready(function(){
        $('#<?php echo CHtml::activeId($model, 'isExtendedSearch')?>').on('change', function(){
                if($(this).is(":checked")){
                        $('.row.buttons-search button').css('top', '-45px');
                        $('.advanced-search').css('margin-bottom', '65px');
                        $('.advanced-search').show();
                }   
                else{
                        $('.row.buttons-search button').css('top', '-47px');
                        $('.advanced-search').css('margin-bottom', '20px');
                        $('.advanced-search').hide();
                } 
        });
    
    });
</script>

<div class="search-wrapper">
    <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'search-form',
            'method' => 'POST',
            'action' => array('index'),
            'htmlOptions'=>array(
                            'class'=>'form-light',
                    ),
    )); ?>
    
    <div class="search-form clearfix">
        <div class="search-form-column">
            <table class="search-form-column-table">
                <tr>
                    <td class="search-description">
                        Хочу найти: 
                    </td>
                    <td class="search-value">
                        <?php echo $form->dropDownList($model, 'id_seeking', JGender::getFormattedList('genitive', true) + array('2' => 'друга'), array('class' => 'input-long')); ?>
                    </td>
                </tr>
                <tr>
                    <td class="search-description">
                        В возрасте:
                    </td>
                    <td class="search-value">
                        <?php $minID = CHtml::activeId($model, 'minAge'); $maxID = CHtml::activeId($model, 'maxAge'); ?>
                        от &nbsp;
                        <?php echo $form->dropDownList($model, 'minAge', Profile::getAgeList(), array(
                                'onChange' => "
                                    if($('#$minID').val() > $('#$maxID').val() && $('#$minID').val() != '' && $('#$maxID').val() != '') 
                                        $('#$maxID').val($('#$minID').val())  
                                ",
                                'prompt' => '',
                                'class' => 'input-short',
                        ));?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        до &nbsp;
                        <?php echo $form->dropDownList($model, 'maxAge', Profile::getAgeList(), array(
                                'onChange' => "
                                    if($('#$maxID').val() < $('#$minID').val() && $('#$minID').val() != '' && $('#$maxID').val() != '') 
                                        $('#$minID').val($('#$maxID').val())  
                                ",
                                'prompt' => '',
                                'class' => 'input-short',
                        ));?>
                    </td>
                </tr>
                <tr>
                    <td class="search-description">
                        Регион:
                    </td>
                    <td class="search-value">
                        <?php echo $form->dropDownList($model, 'id_region', Region::getList(),
                                array(
                                        'prompt' => '',
                                        'ajax' => array(
                                                'type'=>'POST',
                                                'url'=>J::url('loadCities'), 
                                                'dataType'=>'json',
                                                'data'=>array('id_region'=>'js:this.value'),  
                                                'success'=>"function(data) {
                                                    $('#".CHtml::activeId($model, 'id_city')."').html(data.dropDownCities).trigger('refresh');
                                                }"
                                        ),
                                        'class' => 'input-long'
                        )) ?>
                    </td>
                </tr>
                <tr>
                    <td class="search-description">
                        Город:
                    </td>
                    <td class="search-value">
                        <?php echo $form->dropDownList($model, 'id_city', array('' => '') + City::getCitiesListByRegion($model->id_region), array('class' => 'input-long')); ?>
                    </td>
                </tr>
                <tr class="row">
                    <td class="search-description">
                        Способ знакомства:
                    </td>
                    <td class="search-value">
                        <?php echo $form->dropDownList($model, 'id_meetmethod', array(""=>"любой вариант") + JMeetmethod::getList(), array('class' => 'input-long'))?>
                    </td>
                </tr>
            </table>
        </div>
        <div class="search-form-column">
            <table>
                <tr>
                    <td class="search-description">
                        Только с фото:
                    </td>
                    <td class="search-value">
                        <?php echo $form->checkBox($model, 'withPhoto'); ?>
                    </td>
                </tr>
                <tr class="row">
                    <td class="search-description">
                        Новые анкеты:
                    </td>
                    <td class="search-value">
                        <?php echo $form->checkBox($model, 'isNewProfile'); ?>
                    </td>
                </tr>
                <tr class="row">
                    <td class="search-description">
                        Сейчас онлайн:
                    </td>
                    <td class="search-value">
                        <?php echo $form->checkBox($model, 'isOnline'); ?>
                    </td>
                </tr>
                <tr class="row">
                    <td class="search-description long-description">
                        Не отображать тех, с кем общаюсь: &nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                    <td class="search-value">
                        <?php echo $form->checkBox($model, 'isDontShowOffered'); ?>
                    </td>
                </tr>
                <tr class="row">
                    <td class="search-description">
                        Показать в виде:
                    </td>
                    <td class="search-value">
                        <?php echo $form->dropDownList($model, 'resultType', Search::getResultTypeList(), array('class' => 'input-long'))?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    
    <div class="advanced-search-tool">
        <?php echo $form->checkBox($model, 'isExtendedSearch'); ?> <b>Расширеный поиск</b>
    </div>
    
    <div class="advanced-search clearfix<?php if($model->isExtendedSearch == 0 ) echo ' hide'?>">
        <div class="search-form-column">
            <table class="search-form-column-table">
                <tr>
                    <td class="search-description">
                        Цель знакомства:
                    </td>
                    <td class="search-value">
                        <?php echo $form->checkBoxList($model, 'targetIds', JTarget::getList(), array(
                                'template' => '{input} {label}',
                                'labelOptions' => array(
                                        'style'=>'display:inline;font-weight:normal',
                                )
                        )); ?>
                    </td>
                </tr>
                <tr>
                    <td class="search-description">
                        Ориентация:
                    </td>
                    <td class="search-value">
                        <?php echo $form->checkBoxList($model, 'orientationIds', JArray::removeEmpty(JOrientation::getList()), array(
                                'template' => '{input} {label}',
                                'labelOptions' => array(
                                        'style'=>'display:inline;font-weight:normal',
                                )
                        )); ?>
                    </td>
                </tr>
                <tr>
                    <td class="search-description">
                        Отношения:
                    </td>
                    <td class="search-value">
                        <?php echo $form->checkBoxList($model, 'statusIds', JArray::removeEmpty(JStatus::getList()), array(
                                'template' => '{input} {label}',
                                'labelOptions' => array(
                                        'style'=>'display:inline;font-weight:normal',
                                )
                        )); ?>
                    </td>
                </tr>
                <tr>
                    <td class="search-description">
                        Есть ли дети:
                    </td>
                    <td class="search-value">
                        <?php echo $form->checkBoxList($model, 'childrenIds', JArray::removeEmpty(JChildren::getList()), array(
                                'template' => '{input} {label}',
                                'labelOptions' => array(
                                        'style'=>'display:inline;font-weight:normal',
                                )
                        )); ?>
                    </td>
                </tr>
            </table>
        </div>
        <div class="search-form-column right">
            <table class="search-form-column-table">
                <tr>
                    <td class="search-description">
                        Рост:
                    </td>
                    <td class="search-value">
                        от &nbsp;
                        <?php echo $form->textField($model, 'minHeight', array('size'=>2, 'maxlength'=>3, 'class' => 'input-short')) ?>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        до &nbsp;
                        <?php echo $form->textField($model, 'maxHeight', array('size'=>2, 'maxlength'=>3, 'class' => 'input-short')) ?>
                    </td>
                </tr>
                <tr>
                    <td class="search-description">
                        Вес:
                    </td>
                    <td class="search-value">
                        от &nbsp;
                        <?php echo $form->textField($model, 'minWeight', array('size'=>2, 'maxlength'=>3, 'class' => 'input-short')) ?>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        до &nbsp;
                        <?php echo $form->textField($model, 'maxWeight', array('size'=>2, 'maxlength'=>3, 'class' => 'input-short')) ?>
                    </td>
                </tr>
                <tr>
                    <td class="search-description">
                        Материальное положение:
                    </td>
                    <td class="search-value">
                        <?php echo $form->checkBoxList($model, 'welfareIds', JArray::removeEmpty(JWelfare::getList()), array(
                                'template' => '{input} {label}',
                                'labelOptions' => array(
                                        'style'=>'display:inline;font-weight:normal',
                                )
                        )); ?>
                    </td>
                </tr>
                <tr>
                    <td class="search-description">
                        Наличие жилья:
                    </td>
                    <td class="search-value">
                        <?php echo $form->checkBoxList($model, 'housingIds', JArray::removeEmpty(JHousing::getList()), array(
                                'template' => '{input} {label}',
                                'labelOptions' => array(
                                        'style'=>'display:inline;font-weight:normal',
                                )
                        )); ?>
                    </td>
                </tr>
                <tr>
                    <td class="search-description">
                        У меня есть:
                    </td>
                    <td class="search-value">
                        <?php echo $form->checkBoxList($model, 'iHaveIds', JArray::removeEmpty(JIhave::getList()), array(
                                'template' => '{input} {label}',
                                'labelOptions' => array(
                                        'style'=>'display:inline;font-weight:normal',
                                )
                        )); ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="row buttons-search">
        <?php echo CHtml::tag('button', array('class' => 'button-square orange'), 'Найти')?>

        <?php /*echo CHtml::ajaxSubmitButton('Поиск', 
               array('search/index'), array(
                       'update' => '#search-container'
               ),
               array(
                       'style' => 'width:100%; height: 35px;',
               )
        );*/?>       
    </div>
        <?php $this->endWidget(); ?>
</div>