<?php
/**
 * Валидатор количества элементов массива
 *
 * @author office11
 */
class JArrayElementsCountValidator extends CValidator
{
        public $min = null;
        public $max = null;
        
        public $is = null; // если не null, то строгое сравнение с полученным значением, мин и макс игнорируются
        
        public $tooMany = 'Должно быть выбрано не более {count} значений';
        public $tooFew = 'Должно быть выбрано не менее {count} значений';
        public $message = 'Должно быть выбрано ровно {count} значений';
        
        protected function validateAttribute($object, $attribute)
        {
                // если пусто или не массив - то количество элементов - 0
                $count = (empty($object->{$attribute}) || !is_array($object->{$attribute})) ? 0 : count($object->{$attribute});
                 
                if($this->is != null)
                {
                        if($count != $this->is)
                                $this->addError($object, $attribute, $this->message, array('{count}' => $this->is));
                        return;
                }
                
                if($this->min != null && $count < $this->min)
                        $this->addError($object, $attribute, $this->tooFew, array('{count}' => $this->min));
                        
                if($this->max != null && $count > $this->max)
                        $this->addError ($object, $attribute, $this->tooMany, array('{count}' => $this->max));
        }
}

?>
