<?php

class m130317_170228_alert_table extends CDbMigration
{
	public function up()
	{
                $options = 'ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';
            
                $this->createTable('alert', array(
                        'id' => 'pk',
                        'id_user' => 'int(11)',
                        'type' => 'tinyint(4)',
                        'data' => 'varchar(255)',
                        'date' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP '
                ), $options);
                
                $this->createIndex('idx_alert_1', 'alert', 'id_user');
                $this->createIndex('idx_alert_2', 'alert', 'date');
	}

	public function down()
	{
		$this->dropTable('alert');
	}
}