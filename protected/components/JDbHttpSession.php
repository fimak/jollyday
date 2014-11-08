<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of JDbHttpSession
 *
 * @author gbespyatykh
 */
class JDbHttpSession extends CDbHttpSession
{
	/**
	 * Creates the session DB table.
	 * @param CDbConnection $db the database connection
	 * @param string $tableName the name of the table to be created
	 */
	protected function createSessionTable($db,$tableName)
	{
                $options = 'ENGINE=MyISAM DEFAULT CHARSET=utf8';
            
		$db->createCommand()->createTable($tableName,array(
			'id'=>'CHAR(32) PRIMARY KEY',
			'expire'=>'integer',
			'data'=>'LONGBLOB',
		));
                
                $db->createCommand()->createIndex("idx_".$tableName.'_1', $tableName, 'expire');
	}
}

?>
