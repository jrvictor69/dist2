<?php
/**
 * 
 * Table abstract of tblUserGroup
 * @author Victor Villca <victor.villca@swissbytes.ch>
 *
 */

class Model_DbTable_UserGroup extends Zend_Db_Table_Abstract {
	protected $_name = 'tblUserGroup';
	protected $_dependentTables = array('UserGroupPrivilege');
}