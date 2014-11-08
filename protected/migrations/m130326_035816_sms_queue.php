<?php

class m130326_035816_sms_queue extends CDbMigration
{
	public function up()
	{
                $options = 'ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';
            
                $this->createTable('sms_queue', array(
                        'id' => 'pk',
                        'id_user' => 'int(11)',
                        'type' => 'tinyint(2)',
                        'date' => 'timestamp',
                        'phone' => 'varchar(12)',
                        'text' => 'varchar(140)',
                ), $options);
                
                $this->createIndex('idx_sms_queue_1', 'sms_queue', 'id_user');
                $this->createIndex('idx_sms_queue_2', 'sms_queue', 'date');
                $this->createIndex('idx_sms_queue_3', 'sms_queue', 'type');
	}

	public function down()
	{
		$this->dropTable('sms_queue');
	}
}