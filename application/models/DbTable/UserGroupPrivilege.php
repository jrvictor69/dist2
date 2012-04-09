<?php
/**
 * 
 * Table abstract of tblUserGroupPrivilege
 * @author Victor Villca <victor.villca@swissbytes.ch>
 *
 */

class Model_DbTable_UserGroupPrivilege extends Zend_Db_Table_Abstract {
    protected $_name = 'tblUserGroup_Privilege';
    
    protected $_referenceMap    = array(
        'UserGroup' => array(
            'columns'           => array('userGroupId'),
            'refTableClass'     => 'Model_DbTable_UserGroup',
            'refColumns'        => array('id')
        ),
        'Privilege' => array(
            'columns'           => array('privilegeId'),
            'refTableClass'     => 'Model_DbTable_Privilege',
            'refColumns'        => array('id')
        )
     );
}