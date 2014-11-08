<?php
/*
 * Форматтер даты, приводящий её к удобному виду (сегодня, вчера и.т.п)
 */
class TimeagoFormatBehavior extends CBehavior
{
    /**
     * @var array данные локализации 
     */
    public $localData = array(
            'today' => 'cегодня',
            'yesterday' => 'вчера',
            'delimiter' => ', в ',
    );
    
    /*
     * Метод форматирует дату в относительный вид
     * 
     * @param string $value форматированное время
     * @param string $format формат времени
     * @return string
     */
    public function formatTimeago($value, $format = 'Y-m-d H:i:s')
    {       
            $string = '';
            $timeZoneObj = new DateTimeZone(Yii::app()->user->getTimezone());
                                               
            $nowDateTime = new DateTime('now', $timeZoneObj);
            $valDateTime = DateTime::createFromFormat($format, $value)->setTimeZone($timeZoneObj);
                         
            $yesterdayDateTime = new DateTime('now', $timeZoneObj);
            $yesterdayDateTime->sub(new DateInterval('P1D')); 
            
            // если борода с датой, то ничего не выводим
            if(empty($nowDateTime) || empty($valDateTime))
                    return $string;       
            
            // очень давно - так не бывает
            if($valDateTime->format('Y') < 1900)
                    return $string;
            
            // текущая дата в формате Г-м-д
            $nowYMD = $nowDateTime->format('Y-m-d');
            $nowY = $nowDateTime->format('Y');
            
            // Г-м-д сутки назад от текущей даты
            $yesterdayYMD = $yesterdayDateTime->format('Y-m-d');

            // форматируемая дата в формате Г-м-д
            $valYMD = $valDateTime->format('Y-m-d');
            $valY = $valDateTime->format('Y');
                          
            // если текущий д-м-г совпадает с проверякмым, то добавляем в результат "сегодня",
            // иначе "вчера"
            if($nowYMD == $valYMD)
                    $string .= $this->localData['today'];
            elseif($valYMD == $yesterdayYMD)
                    $string .= $this->localData['yesterday'];
            else
            {
                    // если совпадает год, то выводить его не надо
                    if($nowY == $valY)
                            $string .= Yii::app()->dateFormatter->formatDateTime($value,'dm','none');
                    else
                            $string .= Yii::app()->dateFormatter->formatDateTime($value,'dmy','none');
            }
            $string = trim($string);
            
            // разделитель даты и времени
            $string .= $this->localData['delimiter'];
            
            // добавляем локальное время
            $string .= Yii::app()->localtime->toLocalDateTime($value,'none','short'); 
            
            // вывод строки
            return $string;
    }
}
