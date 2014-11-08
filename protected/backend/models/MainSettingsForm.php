<?php

/**
 * Модель формы основных настроек сайта
 */
class MainSettingsForm extends CFormModel
{
        /**
         * @var boolean включение защиты от более 2 регистраций с одного IP за последние 15 минут
         */
        public $regProtection15Min;
        
        /**
         * @var boolean включение защиты от более 100 регистраций с одного IP за последние сутки
         */
        public $regProtectionDay;
        
        /**
         * @var boolean включение капчи при регистрации
         */
        public $regCaptcha;
       
        
        /**
         * Метод, возвращающий массив с описанием правил валидации модели
         * 
	 * @return array массив правил валидации
	 */
        public function rules()
        {
                return array(
                        array('regProtection15Min, regProtectionDay, regCaptcha', 'boolean', 
                                'allowEmpty' => false
                        )
                );
        }
        
	/**
         * Метод возвращает массив настраиваемых названий
         * атрибутов модели (имя атрибута => название)
         * 
	 * @return array массив названий атрибутов
	 */
        public function attributeLabels()
        {
                return array(
                        'regProtection15Min' => 'Запрет регистрации при совершении более двух попыток за 15 минут',
                        'regProtectionDay' => 'Запрет регистрации при более 100 попыток в сутки с одного IP',
                        'regCaptcha' => 'Капча при регистрации',
                );
        }

        /**
         * Метод заполняет поля модели данными из таблицы
         */
        public function getSettings()
        {           
                //присваиваем данные полям
                $this->regProtectionDay = Yii::app()->settings->get('SiteAccess','regProtectionDay');
                $this->regProtection15Min = Yii::app()->settings->get('SiteAccess','regProtection15Min');
                $this->regCaptcha = Yii::app()->settings->get('SiteAccess', 'regCaptcha');
        }
        
        /**
         * Метод сохраняет настройки в таблицу из модели
         */
        public function saveSettings()
        {      
                // ставим строгий тип boolean
                $this->regProtection15Min = $this->regProtection15Min == 0 ? false : true;
                $this->regProtectionDay = $this->regProtectionDay == 0 ? false : true;
                $this->regCaptcha = $this->regCaptcha == 0 ? false : true;
            
                // записываем настройки
                Yii::app()->settings->set('SiteAccess', 'regProtection15Min', $this->regProtection15Min);
                Yii::app()->settings->set('SiteAccess', 'regProtectionDay', $this->regProtectionDay);
                Yii::app()->settings->set('SiteAccess', 'regCaptcha', $this->regCaptcha);     
        }
}

?>
