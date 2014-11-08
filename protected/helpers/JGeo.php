<?php

/**
 * Класс для работы с геолокацией
 */
class JGeo
{
        /**
         * @var boolean поле для хрения пометки об успешности проведения геолокации 
         */
        private $isLocated = false;
        
        /**
         * @var boolean поле для хрения ID города пользователя
         */
        private $cityID = false;
        
        /**
         * @var boolean поле для хрения ID региона пользователя
         */
        private $regionID = false;
        
        /**
         * @var boolean поле для хрения IP-адреса пользователя
         */   
        private $ip;
        
        /**
         * Конструктор
         * 
         * @param string $ip IP адрес пользователя
         */
        public function __construct($ip = false)
        {
                // если не задан, то берётся адрес юзера
                $ip = $ip ? $ip : Yii::app()->request->userHostAddress;
                 
                $this->ip = Yii::app()->format->formatIP2Long($ip);
                
                if(!$this->ip)
                        return;
                
                $result = Yii::app()->db->createCommand()
                        ->select('geoip.id_city AS cityID, city.id_region AS regionID')
                        ->from('geoip')
                        ->join('city', 'geoip.id_city = city.id')
                        ->where(':ip BETWEEN long_ip1 AND long_ip2', array('ip' => $this->ip))
                        ->queryRow();
                
                if($result)
                {
                        $this->cityID = $result['cityID'];
                        $this->regionID = $result['regionID'];   
                        $this->isLocated = true;
                }
        }
        
        /**
         * @return integer ID города пользователя, если не определился, то false
         */
        public function getCityID()
        {
                return $this->cityID;
        }
        
        /**
         * @return integer ID региона пользователя, если не определился, то false
         */
        public function getRegionID()
        {
                return $this->regionID;
        }
        
        /**
         * @return boolean успешно ли произведена геолокация
         */
        public function isLocated()
        {
                return $this->isLocated;
        }
}

?>
