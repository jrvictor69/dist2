<?php
/**
 * 
 * Table abstract of tblPrivilege
 * @author Victor Villca <victor.villca@swissbytes.ch>
 *
 */

class Model_DbTable_Privilege extends Zend_Db_Table_Abstract {
	protected $_name = 'tblPrivilege';
	protected $_dependentTables = array('Model_DbTable_UserGroupPrivilege');
}