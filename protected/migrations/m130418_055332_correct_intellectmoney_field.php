<?php

class m130418_055332_correct_intellectmoney_field extends CDbMigration
{
	public function up()
	{
                $this->alterColumn('pay_intellectmoney', 'service_name', 'varchar(255)');
	}

	public function down()
	{
		$this->alterColumn('pay_intellectmoney', 'service_name', 'int(11)');
	}
}