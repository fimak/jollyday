<?php

class m130327_075525_create_sms_lastlog extends CDbMigration
{
	public function up()
	{
                $options = 'ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';
            
                $this->createTable('sms_log', array(
                        'id' => 'pk',
                        'id_user' => 'int(11)',
                        'date_gift' => "timestamp NULL DEFAULT NULL",
                        'date_offernotice' => "timestamp NULL DEFAULT NULL",
                ), $options);
                
                $this->createIndex('idx_sms_log_1', 'sms_log', 'id_user');
	}

	public function down()
	{
		$this->dropTable('sms_log');
	}
}