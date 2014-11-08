<?php

class m130325_171233_create_aviso_orders_table extends CDbMigration
{
	public function up()
	{
                $options = 'ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';
            
                $this->createTable('pay_sms_aviso', array(
                        'id' => 'pk',
                        'id_user' => 'int(11)',
                        'type' => 'tinyint(4)',
                        'data' => 'varchar(255)',
                        'date' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
                        'order_id' => 'varchar(100)',
                        'merchant_order_id' => 'varchar(100)',
                        'status' => 'tinyint(11)',
                        'phone' => 'varchar(12)',
                ), $options);
                
                $this->createIndex('idx_pay_sms_aviso_1', 'pay_sms_aviso', 'id_user');
                $this->createIndex('idx_pay_sms_aviso_2', 'pay_sms_aviso', 'date');
                $this->createIndex('idx_pay_sms_aviso_3', 'pay_sms_aviso', 'merchant_order_id', true);
                $this->createIndex('idx_pay_sms_aviso_4', 'pay_sms_aviso', 'order_id', true);
	}

	public function down()
	{
		$this->dropTable('pay_sms_aviso');
	}
}