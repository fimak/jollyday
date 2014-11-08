<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="ru" />
        <?php echo CHtml::cssFile(Yii::app()->theme->baseUrl. '/css/style.css')?>
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    </head>
    
    <body>
    <?php $this->widget('bootstrap.widgets.TbNavbar', array(
        'type'=>'inverse',
        'brand'=>'JollyDay',
        'collapse'=>false,
        'items' => array(
            array(
                'class' => 'bootstrap.widgets.TbMenu',
                'items'=>array(
                        array('label'=>'Аудитория', 'items' => array(
                                array('label' => 'Главная', 'url' => array('/audithory/default')),
                                '---',
                                array('label' => 'Пользователи', 'url' => array('/audithory/user')),
                                array('label' => 'Спам', 'url' => array('/audithory/spam')),
                                array('label' => 'Новости', 'url' => array('/audithory/news')),
                                array('label' => 'Служба поддержки', 'url' => array('/audithory/feedback')),
                                array('label' => 'Самые популярные', 'url' => array('/audithory/toprated/index')),
                        )),
                        array('label'=>'География', 'items' => array(
                                 array('label' => 'Главная', 'url' => array('/geography/default')),
                                '---',
                                array('label'=>'Регионы', 'url'=>array('/geography/region')),                    
                                array('label'=>'Города', 'url'=>array('/geography/city')),
                        )),
                        array('label'=>'Сущности', 'items'=>array(
                                array('label' => 'Главная', 'url' => array('/entity/default')),
                                '---',
                                array('label'=>'Подарки','url'=>array('/entity/gift'))
                        )),
                        array('label'=>'Настройки', 'items' => array(
                                array('label' => 'Основные', 'url' => array('/settings/default')),
                                array('label' => 'Пагинация', 'url' => array('/settings/default/pagination')),
                                '---',
                                array('label' => 'Администраторы', 'url'=>array('/settings/admin')),
                                '---',
                                array('label' => 'Gii', 'url'=>array('/gii'), 'linkOptions' => array('target' => '_blank')),
                        )),
                        array('label'=>'Статистика', 'items'=>array(
                                array('label' => 'География', 'url' => array('/statistics/geography/index')),
                                array('label' => 'Мобильные', 'url' => array('/statistics/mobile/index')),
                                array('label' => 'Пользователи', 'url' => array('/statistics/users/index')),
                        )),
                        array('label'=>'Выход', 'url' => array('/default/logout'), 'visible' => !Yii::app()->user->isGuest),
                        array('label'=>'Вход', 'url' => array('/default/login'), 'visible' => Yii::app()->user->isGuest),
                ),
            ),
        ),
    ));?>

    <div class="container">   
        <?php if(isset($this->breadcrumbs)):?>
                <?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
                    'links'=>$this->breadcrumbs,
                    'homeLink' => CHtml::link('Администрирование', array('/')),
                )); ?>
        <?php endif?>

        <div class="row">
            <div class="span2">

            <?php $this->widget('bootstrap.widgets.TbMenu', array(
                'type'=>'tabs', // '', 'tabs', 'pills' (or 'list')
                'stacked'=>true,
                'items'=>$this->menu
            )); ?>

            <?php $this->widget('bootstrap.widgets.TbMenu', array(
                'type'=>'pills', // '', 'tabs', 'pills' (or 'list')
                'stacked'=>true,
                'items'=>$this->submenu
            )); ?>   
            </div>     
            <div class="span10">
                <?php echo $content; ?>
            </div>  
        </div>                                         
    </div>

    </body>
</html>