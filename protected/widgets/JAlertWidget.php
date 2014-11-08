<?php

/**
 * Description of JAlertWidget
 *
 * @author h4sh1sh
 */
class JAlertWidget extends CWidget
{
        const TYPE_RATING = JPayment::OPERATION_RATING;
        const TYPE_BALANCE = JPayment::OPERATION_BALANCE;
        const TYPE_OFFERNOTICE = JPayment::OPERATION_OFFERNOTICE;
        const TYPE_GIFT = JPayment::OPERATION_GIFT;
    
        /**
         * @var integer ID пользователя
         */
        public $userID;
        
        /**
         * @var boolean удалять ли накопившиеся у пользователя уведомления после показа первого 
         */
        public $deleteAll;
    
        private $_lastAlert;
        private $_countNew;
      
        public function init()
        {
                parent::init();

                $this->_countNew = Yii::app()->db->createCommand()
                        ->select('COUNT(*)')
                        ->from('alert')
                        ->where('id_user = :userID', array('userID' => $this->userID))
                        ->queryScalar();
                
                if(!$this->_countNew)
                        return;
                   
                $this->_lastAlert = Yii::app()->db->createCommand()
                        ->select('*')
                        ->from('alert')
                        ->where('id_user = :userID', array('userID' => $this->userID))
                        ->order('date DESC')
                        ->queryRow();
                
                if($this->deleteAll)
                        Yii::app()->db->createCommand()->delete('alert', 'id_user = :userID', array('userID' => $this->userID));
                else
                        Yii::app()->db->createCommand()->delete('alert', 'id = :alertID', array('alertID' => $this->_lastAlert ['id']));
        }
        
        public function run()
        {
                if(!$this->_countNew)
                        return;
                
                $url = $this->_getUrl($this->_lastAlert['type']);
                              
                $this->_registerScript($url, unserialize($this->_lastAlert['data']));
        }
        
        private function _registerScript($url, $postData)
        {
                $postData = CJSON::encode($postData);
            
                $script = <<<EOD
    $.ajax({
        url : '$url',
        type: 'post',
        dataType: 'html',
        data : $postData,
        success : function(data){                                                      
            $("#fancybox-container").html(data);
            
            $.fancybox({
                href : '#fancybox-container',
                scrolling : 'no', 
                autoSize: false,
                autoWidth : false,
                autoHeight: true,
                fitToView: false,
                width : 730,
                openSpeed: 0,
                closeSpeed: 0,
                autoCenter: false,
                padding: 0,
                afterClose: function(){ 
                    $('#fancybox-container').html(''); 
                },
                beforeShow : function(){
                    $("#fancybox-overlay").css({"position":"fixed"});
                },
                afterShow : function(){
                    if(ltie8){
                        resetPie('.fancybox-skin');
                        resetPie('.fancybox-skin h2');
                        resetPie('.fancybox-skin button');
                        resetPie('.fancybox-skin input[type=\'submit\']');
                    }
                }
            });
        },
    });
EOD;
                
                Yii::app()->clientScript->registerScript('alert-messages-widget', $script, CClientScript::POS_READY);
;                
        }
        
        private function _getUrl($type)
        {
                switch($type)
                {
                        case self::TYPE_RATING:
                                $url = J::url('/app/payment/ShowSuccessRating');
                                break;
                        case self::TYPE_OFFERNOTICE:
                                $url = J::url('/app/payment/ShowSuccessOffernotice');
                                break;
                        case self::TYPE_BALANCE:
                                $url = J::url('/app/payment/ShowSuccessAccount');
                                break;
                        case self::TYPE_GIFT:
                                return J::url('/app/payment/ShowSuccessGift');
                                break;
                        default:
                                $url = false;
                                break;
                }
                
                return $url;
        }
        
        public static function createAlert($userID, $type, $data)
        {
                return Yii::app()->db->createCommand()
                        ->insert('alert', array(
                                'id_user' => $userID,
                                'type' => $type,
                                'data' => serialize($data),
                        ));
        }
}

?>
