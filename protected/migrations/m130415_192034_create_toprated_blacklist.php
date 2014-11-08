<?php

class m130415_192034_create_toprated_blacklist extends CDbMigration
{
	public function up()
	{
                $options = 'ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';
            
                $this->createTable('blacklist_toprated', array(
                        'id' => 'pk',
                        'id_user' => 'int(11) NOT NULL'
                ), $options);
                
                $this->createIndex('idx_toprated_blacklist_1', 'blacklist_toprated', 'id_user', true);
	}

	public function down()
	{
		$this->dropTable('blacklist_toprated');
	}
}