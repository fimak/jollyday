<?php
/**
 * Валидатор размеров изображений
 * 
 * @property integer $minWidth минимальная ширина изображения
 * @property integer $minHeight минимальная высота изображения
 * @property integer $maxWidth максимальная ширина изображения
 * @property integer maxHeigh максимальная высота изображения
 * @property integer $errorIfNotImage выдавать ли ошибку если загружаемый файл не является изображением
 * 
 * @property string $minWidthNotMatch текст сообщения об ошибке при несоответствии мин. ширины изображения
 * @property string $minHeightNotMatch текст сообщения об ошибке при несоответствии мин. высоты изображения
 * @property string $maxWidthNotMatch текст сообщения об ошибке при несоответствии макс. ширины изображения
 * @property string $maxHeightNotMatch текст сообщения об ошибке при несоответствии макс. высоты изображения
 * @property string $notImage текст сообщения об ошибке, о том, что загруженные файл не изображение
 * 
 * @property array $allowedMime допустимые mime у файла
 */

class JImageDimensionsValidator extends CValidator
{
        public $minWidth = 0;               // минимальная ширина изображения
        public $minHeight = 0;              // минимальная высота изображения
        public $maxWidth;                   // максимальная ширина изображения
        public $maxHeight;                  // максимальная высота изображения
        public $errorIfNotImage = true;     // выдавать ли ошибку если загружаемый файл не является изображением

        // Сообщения об ошибках
        public $minWidthNotMatch = 'Ширина изображения меньше чем {value}';
        public $minHeightNotMatch = 'Высота изображения меньше чем {value}';
        public $maxWidthNotMatch = 'Ширина изображения больше чем {value}';
        public $maxHeightNotMatch = 'Высота изображения больше чем {value}';
        public $notImage = '{field_name} не является изображением';

        // перечисляем какие mime типы разрешены
        protected static $allowedMime = array(
                'image/jpeg',
                'image/png',
                'image/gif',
        );

        /**
         * Метод выполняющий валидацию
         * 
         * @param type $object Валидируемый объект CModel
         * @param type $attribute атрибут валидации
         * @throws CException Неверно заданы парметры JImageDimensionsValidator
         */
        protected function validateAttribute($object, $attribute) {

            // проверяем не больше ли минимальные размеры максимальных
            if ($this->minHeight > $this->maxHeight || $this->minWidth > $this->maxWidth)
                    throw new CException('Неверно заданы парметры JImageDimensionsValidator');
            $file = $object->$attribute;

            // получаем загруженный файл
            if(!$file instanceof CUploadedFile){
                    $file = CUploadedFile::getInstance($object, $attribute);
                    if(null===$file)
                            return;
            }
            $image = $file->getTempName();

            // получаем информацию о загруженном файле - mime тип и размеры
            $imgInfo = getimagesize($image);
            $mime = $imgInfo['mime'];
            $width = $imgInfo[0];
            $height = $imgInfo[1];

            // проверяем не находится ли текущий mime тип в списке разрешенных
            if (!in_array($mime, self::$allowedMime)){
                    // если нет и у нас установлен флаг errorIfNotImage выводим ошибку
                    if ($this->errorIfNotImage){
                            $this->addError ($object, $attribute, $this->notImage, array('{field_name}' => $attribute));
                    }
                    // завершаем выполнение валидатора, так как проверять 
                    // высоту и ширину уже нет смысла если загруженный файл не является изображением
                    return;
            }

            // проверяем размеры
            if ($width < $this->minWidth)
                    $this->addError ($object, $attribute, $this->minWidthNotMatch, array('{field_name}' => $attribute, '{value}' => $this->minWidth));
            if ($height < $this->minHeight)
                    $this->addError ($object, $attribute, $this->minHeightNotMatch, array('{field_name}' => $attribute, '{value}' => $this->minHeight));
            if ($width > $this->maxWidth)
                    $this->addError ($object, $attribute, $this->maxWidthNotMatch, array('{field_name}' => $attribute, '{value}' => $this->maxWidth));
            if ($height > $this->maxHeight)
                    $this->addError ($object, $attribute, $this->maxHeightNotMatch, array('{field_name}' => $attribute, '{value}' => $this->maxHeight));
        }
}
?>
