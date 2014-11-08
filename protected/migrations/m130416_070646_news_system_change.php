<?php

class m130416_070646_news_system_change extends CDbMigration
{
	public function up()
	{
                $this->dropTable('newstype');
                
                $this->addColumn('im_user_news', 'type', 'tinyint(4) NOT NULL');
                $this->addColumn('im_user_news', 'text', 'text');
                $this->addColumn('im_user_news', 'std_image', 'varchar(32)');
                $this->addColumn('im_user_news', 'title', 'varchar(255)');
                
                $this->alterColumn('im_user_news', 'id_news', 'int(11)');
	}

	public function down()
	{
                $this->alterColumn('im_user_news', 'id_news', 'int(11) NOT NULL');
            
                $this->dropColumn('im_user_news', 'type');
                $this->dropColumn('im_user_news', 'text');
                $this->dropColumn('im_user_news', 'std_image');
                $this->dropColumn('im_user_news', 'title');
            
                $options = 'ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';
                
                $this->createTable('newstype', array(
                        'id' => 'pk',
                        'alias' => 'varchar(32) NOT NULL',
                        'id_news' => 'int(11)'
                ), $options);
                
                $this->createIndex('idx_1', 'newstype', 'id_news');
	}
}