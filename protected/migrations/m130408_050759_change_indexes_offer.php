<?php

class m130408_050759_change_indexes_offer extends CDbMigration
{
	public function up()
	{
                $this->dropIndex('idx_1', 'offer');
                
                $this->createIndex('idx_offer_1', 'offer', 'id_sender, id_reciever', true);
                $this->createIndex('idx_offer_2', 'offer', 'id_method');
                $this->createIndex('idx_offer_3', 'offer', 'status');
                $this->createIndex('idx_offer_4', 'offer', 'paid');
	}

	public function down()
	{
                $this->dropIndex('idx_offer_1', 'offer');
                $this->dropIndex('idx_offer_2', 'offer');
                $this->dropIndex('idx_offer_3', 'offer');
                $this->dropIndex('idx_offer_4', 'offer');
                
                $this->createIndex('idx_1', 'offer', 'id_method, id_sender, id_reciever');
	}
}