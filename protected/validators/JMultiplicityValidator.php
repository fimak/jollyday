<?php
/**
 * Валидатор кратности числа
 *
 * @author office11
 */
class JMultiplicityValidator extends CValidator
{
        public $factor = 1; // множитель
        
        public $message = 'Число должно быть кратно {factor}';
               
        protected function validateAttribute($object, $attribute)
        {
                if($object->{$attribute} % $this->factor != 0)
                        $this->addError($object, $attribute, $this->message, array('{factor}' => $this->factor));
        }
}

?>
