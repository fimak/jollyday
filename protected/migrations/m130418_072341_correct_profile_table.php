<?php

class m130418_072341_correct_profile_table extends CDbMigration
{
	public function up()
	{
                $this->alterColumn('profile', 'height', 'int(3) DEFAULT NULL');
	}

	public function down()
	{
		$this->alterColumn('profile', 'height', 'int(2) DEFAULT NULL');
	}
}