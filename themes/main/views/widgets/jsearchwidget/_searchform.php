<?php $form = $this->beginWidget('CActiveForm', array(
            'id' => 'search-form',
            'method' => 'post',
            'action' => array('search'),
            'htmlOptions' => array(
                    'id' => 'search-form'
            ),
)) ?>
<table>
    <tr id="search-form-labels">
        <td>
            <label id="label-gender">Я ищу:</label>
        </td>
        <td>
            <label>В возрасте:</label>
        </td>
        <td>
            <label>Регион:</label>
        </td>
        <td>
            <label>Город:</label>
        </td>
        <td>
            <label>Способ знакомства:</label>
        </td>
        <td>
        </td>
    </tr>
    <tr id="search-form-fields">
        <td class="search-form-gender">
            <span>
                <?php echo CHtml::link('девушку', "javascript:void(0);", array('class' => 'female trGenderSelect'))?>
            </span>
            <span>
                <?php echo CHtml::link('парня', "javascript:void(0);", array('class' => 'male trGenderSelect'))?>
            </span>
            <?php echo $form->hiddenField($model, 'gender'); ?>

            <?php 
                $genderFieldId = CHtml::activeId($model, 'gender');

                Yii::app()->clientScript->registerScript('search-form-gender-select',"
                    var initGenderValue = $('#$genderFieldId').val();

                    if(initGenderValue == '1')
                        $('.search-form-gender .female').parent().addClass('selected');
                    else
                        $('.search-form-gender .male').parent().addClass('selected');

                    $('.trGenderSelect').click(function(){
                        $('.search-form-gender span').removeClass('selected');
                        $(this).parent().addClass('selected');

                        var genderValue = $(this).hasClass('female') ? 1 : 0;

                        $('#$genderFieldId').val(genderValue);
                    });
            ",  CClientScript::POS_READY)?>       
        </td>
        <td>
            <?php $minID = CHtml::activeId($model, 'minAge'); $maxID = CHtml::activeId($model, 'maxAge'); ?>
            <?php echo $form->dropDownList($model, 'minAge', Profile::getAgeList(), array(
                    'onChange' => "
                        if($('#$minID').val() > $('#$maxID').val() && $('#$minID').val() != '' && $('#$maxID').val() != '') 
                            $('#$maxID').val($('#$minID').val());     
                        $('#$maxID ,#$minID').trigger('refresh');
                    ",
                    'class' => 'search-form-select-age'
            ));?> — 
            <?php echo $form->dropDownList($model, 'maxAge', Profile::getAgeList(), array(
                    'onChange' => "
                        if($('#$maxID').val() < $('#$minID').val() && $('#$minID').val() != '' && $('#$maxID').val() != '') 
                            $('#$minID').val($('#$maxID').val());    
                        $('#$maxID ,#$minID').trigger('refresh');
                    ",
                    'class' => 'search-form-select-age'
            ));?>
        </td>
        <td>
            <?php echo $form->dropDownList($model, 'id_region', Region::getList(),
                  array(
                          'prompt' => 'Любой',
                          'ajax' => array(
                                  'type'=>'POST',
                                  'url'=>J::url('cities'), 
                                  'dataType'=>'json',
                                  'data'=>array('id_region'=>'js:this.value'),  
                                  'success'=>"function(data) {
                                      $('#".CHtml::activeId($model, 'id_city')."').html(data.dropDownCities).trigger('refresh');
                                  }"
                          ),
                          'class' => 'search-form-select-region'
           )) ?>
        </td>
        <td>
            <?php echo $form->dropDownList($model, 'id_city', 
                    City::getCitiesListByRegion($model->id_region), array(
                        'prompt' => 'Любой',
                        'class' => 'search-form-select-region'
                    )
            ); ?>
        </td>
        <td>
            <?php echo $form->dropDownList($model, 'id_meetmethod', 
                    JMeetmethod::getShortList(), array(
                        'prompt' => 'Любой',
                        'class' => 'search-form-select-meetmethod'
                    )
            );?>
        </td>
        <td class="search-button-wrapper">
            <?php echo CHtml::tag('button', array(
                    'id'=>'search-button',
                    'type'=>'submit'),
                    'Поиск'
            ); ?>
        </td>
    </tr>
</table>
<?php $this->endWidget(); ?>