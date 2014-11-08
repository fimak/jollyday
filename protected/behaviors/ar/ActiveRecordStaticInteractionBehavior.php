<?php

/**
 * Поведение, подключаемое где нужно организовать особую связь:
 * эмуляция связи MANY_MANY, где одна из таблиц заменена ассоциативным массивом
 */
class ActiveRecordStaticInteractionBehavior extends CBehavior
{
        /**
         * @var integer имя колонки связующей родителя поведения и статичную таблицу
         */
        public $foreignKey;
    
        /**
         * Метод записывает в поле текущей модели массив значений указанного атрибута
         * из связанных моделей
         * 
         * @param string $relation имя реляционной связи
         * @param array $arrayAttribute имя поля, в которое будет записан массив
         * @param string $attribute атрибут связанных моделей.
         */
        public function getRelatedStaticData($relation,$arrayAttribute, $attribute)
        {
                if(!empty($this->owner->{$relation}))
                        foreach ($this->owner->{$relation} as $key => $value)
                                $this->owner->{$arrayAttribute}[] = $value->{$attribute};
        }

        /**
         * Метод сохраняет во вспомогательную таблицу значения из ассоциативного массива
         * 
         * @param string $table имя вспомогательной таблицы
         * @param string $ownerArrayAttribute имя атрибута модели, содержащего массив ID привязанных записей из ассоциативного массива
         * @param string $attribute столбец вспомогательной таблицы для вставки значений из ассоциативного массива
         * @param string $foreignKeyValue значение внешнего ключа, связующего вспомогательную и родительскую таблицы 
         * @return boolean успешно ли прошло сохранение данных
         */
        public function saveRelatedStaticData($table, $ownerArrayAttribute, $attribute, $foreignKeyValue)
        {         
                Yii::app()->db->createCommand()
                        ->delete(
                                $table,
                                "{$this->foreignKey} = :userID",
                                array(
                                        'userID' => $foreignKeyValue
                                ));

                $items = array();

                if(empty($this->owner->{$ownerArrayAttribute}))
                        return;

                foreach($this->owner->{$ownerArrayAttribute} as $item)
                        $items[] = '('.$foreignKeyValue.','.$item.')';

                $string = implode(',', $items);

                $sql = "INSERT INTO {$table} ({$this->foreignKey}, {$attribute}) VALUES {$string}";
                return Yii::app()->db->createCommand($sql)->execute();
        }
}

?>
