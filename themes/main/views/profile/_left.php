<div class="profile-gender-icon-wrapper">
    <div class="trTooltipGender gender-icon<?php echo $user->id_gender == JGender::FEMALE ? ' female' : ' male'?>"></div>
    <div class="tooltip-gender <?php if($user->id_gender == JGender::FEMALE) :?>female<?php else :?>male<?php endif;?>">
        <?php echo JGender::getDescription($user->id_gender)?>
    </div>
</div>

<h5 class="color-blue user-name"><?php echo CHtml::encode($user->name); ?>, <?php echo $user->getAge() ;?> (<?php echo $user->getZodiacDescription($user->birthday)?>)</h5>

<ul class="questionary">
    <?php if($user->city) : ?>
        <li>
            <div class="left"><span class="questionary-question">Город:</span></div>
            <div class="right"><span class="questionary-answer"><?php echo $user->city->name;?></span></div>
        </li>
    <?php endif; ?>
    
    <?php if(isset($user->profile->height) || isset($user->profile->weight)) :?>
        <li>
            <?php if($user->profile->height) :?>
                <div class="left"><span class="questionary-question">Рост: </span><span class="questionary-answer"><?php echo $user->profile->height;?> см</span></div>
            <?php endif; ?>
            <?php if($user->profile->weight) :?>
                <div class="right"><span class="questionary-question weight">Вес: </span><span class="questionary-answer"><?php echo $user->profile->weight;?> кг</span></div>
            <?php endif;?>
        </li>
    <?php endif;?>
        
    <?php if(isset($user->profile->id_seeking)) : ?>
        <li>
            <div class="left"><span class="questionary-question">Познакомлюсь с: </span></div>
            <div class="right">
                <span class="questionary-answer">
                    <?php echo Profile::formatSeeking($user->profile->id_seeking, 'ablative', false); ?>
                </span>
            </div>
        </li>
    <?php endif; ?>
            
    <?php if(!empty($user->profile->age_min) || !empty($user->profile->age_max)) : ?>
        <li>
            <div class="left"><span class="questionary-question">В возрасте: </span></div>
            <div class="right"><span class="questionary-answer"><?php echo Profile::formatAgeInterval($user->profile->age_min, $user->profile->age_max); ?></span></div>
        </li>
    <?php endif; ?>               
            
    <?php if(isset($user->profile->meetTargets) && !empty($user->profile->meetTargets)) : ?>
        <li>
            <div class="left"><span class="questionary-question">Цель знакомства: </span></div>
            <div class="right">
                <?php echo JHtml::unorderedList($user->profile->targetList(), array('class' => 'a')); ?>
            </div>
        </li>
    <?php endif; ?>
        
    <?php if(isset($user->profile->id_orientation) && !empty($user->profile->id_orientation)) : ?>
        <li>
            <div class="left"><span class="questionary-question">Ориентация: </span></div>
            <div class="right"><span class="questionary-answer"><?php echo JOrientation::getDescription($user->profile->id_orientation); ?></span></div>
        </li>
    <?php endif; ?>
        
    <?php if(isset($user->profile->id_status) && !empty($user->profile->id_status)) : ?>
        <li>
            <div class="left"><span class="questionary-question">Отношения: </span></div>
            <div class="right"><span class="questionary-answer"><?php echo JStatus::getDescription($user->profile->id_status); ?></span></div>
        </li>
    <?php endif; ?> 
        
    <?php if(isset($user->profile->id_welfare) && !empty($user->profile->id_welfare)) : ?>
        <li>
            <div class="left"><span class="questionary-question">Мат. положение: </span></div>
            <div class="right"><span class="questionary-answer"><?php echo JWelfare::getDescription($user->profile->id_welfare); ?></span></div>
        </li>
    <?php endif; ?>
        
    <?php if(isset($user->profile->id_housing) && !empty($user->profile->id_housing)) : ?>
        <li>
            <div class="left"><span class="questionary-question">Наличие жилья: </span></div>
            <div class="right"><span class="questionary-answer"><?php echo JHousing::getDescription($user->profile->id_housing); ?></span></div>
        </li>
    <?php endif; ?>
        
    <?php if(isset($user->profile->id_children) && !empty($user->profile->id_children)) : ?>
        <li>
            <div class="left"><span class="questionary-question">Дети:</span></div>
            <div class="right"><span class="questionary-answer"><?php echo JChildren::getDescription($user->profile->id_children); ?></span></div>
        </li>
    <?php endif; ?>
        
    <?php if(isset($user->profile->iHave) && !empty($user->profile->iHave)): ?>
        <li>
            <div class="left"><span class="questionary-question">У меня есть: </span></div>
            <div class="right">
                <?php echo JHtml::unorderedList($user->profile->ihaveList(), array('class' => 'a')); ?>
            </div>
        </li>
    <?php endif; ?>    
</ul>