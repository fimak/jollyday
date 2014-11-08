<h4 class="collapsible">Данные пользователя</h4>

<table id="debug-toolbar-jollyday-user">
    <thead>
        <tr>
            <th>Параметр</th>
            <th>Значение</th>
        </tr>
    </thead>
    <tbody>
        <tr class="odd">
            <th>ID пользователя</th>
            <td><?php echo Yii::app()->user->id; ?></td>
        </tr>
        <tr class="even">
            <th>Номер телефона пользователя</th>
            <td><?php echo Yii::app()->format->formatPhone(Yii::app()->user->name, true); ?></td>
        </tr>
        <tr class="odd">
            <th>Роль в системе</th>
            <td><?php echo Yii::app()->user->role; ?></td>
        </tr>
        <tr class="even">
            <th>Шаг регистрации</th>
            <td><?php echo Yii::app()->user->getRegisterStep(); ?></td>
        </tr>
        <tr class="even">
            <th>Имя</th>
            <td><?php echo CHtml::encode(Yii::app()->user->getRealname()); ?></td>
        </tr>
        <tr class="odd">
            <th>Счёт</th>
            <td><?php echo Yii::app()->user->getAccount(); ?>$</td>
        </tr>
        <tr class="odd">
            <th>Обычный счёт</th>
            <td><?php echo Yii::app()->user->getUsualAccount(); ?>$</td>
        </tr>
        <tr class="odd">
            <th>Бонусный счёт</th>
            <td><?php echo Yii::app()->user->getBonusAccount(); ?>$</td>
        </tr>
        <tr class="even">
            <th>Время локальное</th>
            <td><?php echo Yii::app()->localtime->localNow;?></td>
        </tr>
        <tr class="odd">
            <th>Время UTC</th>
            <td><?php echo Yii::app()->localtime->UTCNow;?></td>
        </tr>
    </tbody>
</table>