<?php

class m130415_191954_set_unique_register_code extends CDbMigration
{
	public function up()
	{
                $this->dropIndex('idx_1', 'new_user');
                $this->createIndex('idx_new_user_1', 'new_user', 'code', true);
	}

	public function down()
	{
		$this->dropIndex('idx_new_user_1', 'new_user');
		$this->createIndex('idx_1', 'new_user', 'code');
	}
}