<?php
/**
 * Валидатор на минимальный возраст
 *
 * @author office11
 */
class JAdultValidator extends CValidator
{
        public $minAge;
        public $maxAge;
        public $format = 'Y-m-d';
        
        protected function validateAttribute($object, $attribute)
        {
                $now = new DateTime;
                $date = DateTime::createFromFormat($this->format, $object->{$attribute});
                
                if(!is_a($date, 'DateTime'))
                {        
                        $this->addError($object, $attribute, 'Неправильный формат даты');
                        return;
                }
                
                $age = $date->diff($now)->format('%y');

                if($age < $this->minAge)
                {
                        $this->addError($object, $attribute, 'Вам должно быть как минимум '.$this->minAge.' лет');
                        $object->{$attribute} = null;
                }
                
                if($age > $this->maxAge)
                {
                        $this->addError($object, $attribute, 'Нам кажется, что в таком возрасте на свидания лучше не ходить :)');
                        $object->{$attribute} = null;
                }
        }
}

?>
