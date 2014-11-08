<?php

class m130418_041401_delete_unused_tables extends CDbMigration
{
	public function up()
	{
                $this->dropTable('issue');
                $this->dropTable('sms_queue');
                $this->dropTable('stat_daily');
	}

	public function down()
	{
                $options = 'ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';
            
		$this->createTable('issue', array(
                        'id' => 'pk',
                        'id_user' => 'int(11) DEFAULT NULL',
                        'useragent' => 'varchar(255) DEFAULT NULL',
                        'window_size' => 'varchar(16) DEFAULT NULL',
                        'url' => 'varchar(128) DEFAULT NULL',
                        'text' => 'text NOT NULL',
                        'date' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
                        'status' => 'tinyint(1) NOT NULL',
                ), $options);
                    
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
                
		$this->createTable('stat_daily', array(
                        'id' => 'pk',
                        'id_user' => 'int(11) NOT NULL',
                        'count_messages' => "int(11) NOT NULL DEFAULT '0'",
                        'count_gifts' => "int(11) NOT NULL DEFAULT '0'",
                        'count_offers' => "int(11) NOT NULL DEFAULT '0'",
                ), $options);
                
                $this->createIndex('id_user', 'stat_daily', 'id_user', true);
	}
}