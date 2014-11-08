<?php

class m130417_032700_im_user_news_alter_timestamp_field extends CDbMigration
{
	public function up()
	{
                $this->alterColumn('im_user_news', 'date', 'timestamp NULL DEFAULT NULL');
	}

	public function down()
	{
		$this->alterColumn('im_user_news', 'date', 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
	}
}