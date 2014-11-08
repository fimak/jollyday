<?php

class m130411_174541_message_to_metamessage extends CDbMigration
{
	public function up()
	{
                $this->dropIndex('idx_1', 'message');
                
                $this->renameColumn('message', 'id_offer', 'id_related');
                $this->alterColumn('message', 'id_related', 'int(11) NOT NULL');
                
                $this->addColumn('message', 'type', 'int(11) NOT NULL');
                $this->addColumn('message', 'paid', 'tinyint(1) NOT NULL');
                
                $this->createIndex('idx_message_1', 'message', 'id_sender, id_reciever');
                $this->createIndex('idx_message_2', 'message', 'id_related');
                $this->createIndex('idx_message_3', 'message', 'type');
                $this->createIndex('idx_message_4', 'message', 'status');
                $this->createIndex('idx_message_5', 'message', 'paid, date');
	}

	public function down()
	{
		$this->dropIndex('idx_message_1', 'message');
                $this->dropIndex('idx_message_2', 'message');
                $this->dropIndex('idx_message_3', 'message');
                $this->dropIndex('idx_message_4', 'message');
                $this->dropIndex('idx_message_5', 'message');
                
                $this->dropColumn('message', 'type');
                $this->dropColumn('message', 'paid');
                
                $this->alterColumn('message', 'id_related', 'int(11)');
                $this->renameColumn('message', 'id_related', 'id_offer');
                
                $this->createIndex('idx_1', 'message', 'id_sender, id_reciever, id_offer');
	}
}