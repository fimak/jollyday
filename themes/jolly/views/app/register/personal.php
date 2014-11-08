<?php $this->pageTitle = 'Регистрация :: Персональные данные' . ' - ' . Yii::app()->name;?>

<h1 id ="page-header">Ваши персональные данные:</h1>

<div class="register-form-wrapper">
    <p class="register-info">
        Поздравляем! Вы уже зарегистрированы. Осталось заполнить информацию о себе и можно начинать знакомиться.
    </p>  
    
    <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'form-reg-personal',
            'htmlOptions' => array(
                    'class' => 'form-light'
            ),
            'enableAjaxValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true
            )
    )); ?>
	<div class="row">
            <div class="left-medium">
		<?php echo $form->labelEx($model,'name'); ?>
            </div>
            <div class="right-medium">
		<?php echo $form->textField($model,'name', array('class' => 'input-long', 'maxlength' => 32)); ?>
                <?php echo $form->error($model,'name'); ?>
            </div>
            <?php $this->widget('JCharCounter', array(
                    'inputID' => CHtml::activeId($model, 'name'),
                    'containerID' => 'name-charcount',
                    'containerOptions' => array(
                            'class' => 'right-medium char-counter'
                    ),
            ))?>    
	</div>
	<div class="row">
            <div class="left-medium">
                <?php echo $form->labelEx($model,'birthday'); ?>
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
	<div class="row">
            <div class="left-medium">
		<?php echo $form->labelEx($model,'id_region'); ?>
            </div>
            <div class="right-medium">
                <?php echo CHtml::ActiveDropDownList($model,'id_region', Region::getList(),
                        array(
                            'prompt'=>'Выберите регион',
                            'ajax' => array(
                                    'type'=>'POST',
                                    'url'=>CController::createUrl('loadCities'), 
                                    'dataType'=>'json',
                                    'data'=>array('id_region'=>'js:this.value'),
                                    'beforeSend'=>"function(){
                                        $('#form-reg-personal button[type=\"submit\"]').attr('disabled', 'disabled');
                                    }",
                                    'success'=>'function(data) {
                                        $("#'.CHtml::activeId($model, 'id_city').'").html(data.dropDownCities).trigger("refresh");
                                        $("#form-reg-personal button[type=\'submit\']").removeAttr("disabled");    
                                    }',
                            ),
                            'class' => 'input-long'
                ));?>
                <?php echo $form->error($model,'id_region'); ?>
            </div>
	</div>  
	<div class="row">
            <div class="left-medium">
		<?php echo $form->labelEx($model,'id_city'); ?>
            </div>
            <div class="right-medium">
		<?php echo $form->dropDownList($model,'id_city', isset($model->id_city) ? City::getCitiesListByRegion($model->id_region) : array('' => 'Выберите город'), array(
                        'class' => 'input-long'
                )); ?>
                <?php echo $form->error($model,'id_city'); ?>
            </div>
	</div>      
	<div class="row">
            <div class="left-medium">
		<?php echo $form->labelEx($model,'id_gender'); ?>
            </div>
            <div class="right-medium">
		<?php echo $form->dropDownList($model,'id_gender', JGender::getList(), array(
                    'prompt' => 'не указан',
                    'class' => 'input-medium'
                ) ); ?>
                <?php echo $form->error($model,'id_gender'); ?>
            </div>
	</div>    
    
	<div class="row">
            <div class="left-medium">
		<?php echo $form->labelEx($model,'email'); ?>
            </div>
            <div class="right-medium">
		<?php echo $form->textField($model,'email', array('class' => 'input-long')); ?>
                <?php echo $form->error($model,'email'); ?>
            </div>
	</div>
        <div class="comment">
            Для подтверждения Вашей электронной почты будет отправлено автоматическое сообщение на электронную почту.<br />
            Вы сможете получать уведомления о новых событиях на сайте.
        </div>
	<div class="row medium_medium">
            <?php echo CHtml::tag('button', array(
                    'type' => 'submit',
                    'class' => 'button-square orange float-right'
            ),'Далее')?>	
	</div>
    <?php $this->endWidget(); ?>
</div>

<!-- Google Code for &#1056;&#1077;&#1075;&#1080;&#1089;&#1090;&#1088;&#1072;&#1094;&#1080;&#1103; Conversion Page -->
<script type="text/javascript">
    /* <![CDATA[ */
    var google_conversion_id = 996215007;
    var google_conversion_language = "en";
    var google_conversion_format = "3";
    var google_conversion_color = "ffffff";
    var google_conversion_label = "JEkeCLGqqAQQ35GE2wM";
    var google_conversion_value = 0;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js"></script>
<noscript>
    <div style="display:inline;">
    <img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/996215007/?value=0&amp;label=JEkeCLGqqAQQ35GE2wM&amp;guid=ON&amp;script=0"/>
    </div>
</noscript>