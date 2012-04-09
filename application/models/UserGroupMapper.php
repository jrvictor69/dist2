<?php
/**
 * DataMapper for Dist 2.
 *
 * @category Dist
 * @package Models
 * @author Victor Villca <victor.villca@swissbytes.ch>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class Model_UserGroupMapper extends Model_TemporalMapper {
	
	/**
	 * 
	 * This class abstract is from Zend framework
	 * @var Zend_Db_Table_Abstract
	 */
	protected $_dbTable;
	
	/**
	 * 
	 * This class abstract is from a table many to many
	 * @var Zend_Db_Table_Abstract
	 */
	protected $_dbTableManyToMany;
	
	/**
	 * (non-PHPdoc)
	 * @see Model_TemporalMapper::setDbTable()
	 */
    public function setDbTable($dbTable) {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }
    
    /**
     * (non-PHPdoc)
     * @see Model_TemporalMapper::getDbTable()
     */
    public function getDbTable() {
        if (null === $this->_dbTable) {
            $this->setDbTable('Model_DbTable_UserGroup');
        }
        return $this->_dbTable;
    }
    
    /**
     * 
     * Creates a new object Zend_Db_Table_Abstract for a table many to many
     * @param string $dbTableManyToMany
     * @throws Exception
     */
    public function setDbTableManyToMany($dbTableManyToMany) {
        if (is_string($dbTableManyToMany)) {
            $dbTableManyToMany = new $dbTableManyToMany();
        }
        if (!$dbTableManyToMany instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTableManyToMany = $dbTableManyToMany;
        return $this;
    }
    
    /**
     * 
     * Returns the class abstract for a table many to many
     * @return Zend_Db_Table_Abstract
     */
    public function getDbTableManyToMany() {
        if (null === $this->_dbTableManyToMany) {
            $this->setDbTableManyToMany('Model_DbTable_UserGroupPrivilege');
        }
        return $this->_dbTableManyToMany;
    }
    
    /**
     * 
     * Saves model
     * @param Model_UserGroup $userGroup
     */
	public function save(Model_UserGroup $userGroup) {
		$data = array(
            'name' => $userGroup->getName(),
            'description' => $userGroup->getDescription(),
            'created' => date('Y-m-d H:i:s'),
			'createdBy' => $userGroup->getCreatedBy(),
        	self::STATE_FIELDNAME => TRUE
        );
		
		unset($data['id']);
		$this->getDbTable()->insert($data);
		
		$userGroupId = (int)$this->getDbTable()->getAdapter()->lastInsertId();
		$userGroup->setId($userGroupId);
		
		$privileges = $userGroup->getPrivileges();
		foreach ($privileges as $privilege) {
			$this->saveManyToMany($userGroup, $privilege);
		}
    }

	/**
     * 
     * Saves the data in the table many to many (tblUserGroup_Privilege)
     * @param Model_UserGroup $userGroup
     * @param Model_Privilege $privilege
     */
	public function saveManyToMany(Model_UserGroup $userGroup, Model_Privilege $privilege) {
		$data = array(
            'userGroupId' => $userGroup->getId(),
            'privilegeId' => $privilege->getId(),
			'created' => date('Y-m-d H:i:s')
        );

		unset($data['id']);
		$this->getDbTableManyToMany()->insert($data);
    }
    
    /**
     * 
     * Updates model
     * @param int $id
     * @param Model_UserGroup $userGroup
     */
	public function update($id, Model_UserGroup $userGroup) {
        $result = $this->getDbTable()->find($id); 
        if (0 == count($result)) {
            return;
        }
        
	 	$data = array(
            'name' => $userGroup->getName(),
            'description' => $userGroup->getDescription(),
            'changed' => date('Y-m-d H:i:s'),
	 		'changedBy' => $userGroup->getChangedBy()
        );

        $this->getDbTable()->update($data, array('id = ?' => $id));
    }
    
	/**
     * 
     * Updates the data in the table many to many (tblUserGroup_Privilege)
     * @param int $id
     * @param Model_UserGroup $userGroup
     * @param Model_Privilege $privilege
     */
	public function updateManyToMany($id, Model_UserGroup $userGroup, Model_Privilege $privilege) {
		$data = array(
            'userGroupId' => $userGroup->getId(),
            'privilegeId' => $privilege->getId(),
			'created' => date('Y-m-d H:i:s')
        );
        
		$this->getDbTableManyToMany()->update($data, array('id = ?' => $id));
    }
    
    /**
     * 
     * Deletes model
     * @param int $id
     */
    public function delete($id) {
    	$result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        
        $data = array(
            'changed' => date('Y-m-d H:i:s'),
        	self::STATE_FIELDNAME => FALSE,
        );
        
    	$this->getDbTable()->update($data, array('id = ?' => $id));
    }
    
    /**
     * (non-PHPdoc)
     * @see Model_TemporalMapper::find()
     * @return Model_UserGroup
     */
	public function find($id) {
        $result = $this->getDbTable()->find($id);
    	if (0 == count($result)) {
            return FALSE;
        }
        
        $row = $result->current();

        // Many to many
        $privilegesRowSet  = $row->findManyToManyRowset('Model_DbTable_Privilege', 'Model_DbTable_UserGroupPrivilege');
        $privileges = array();
        foreach ($privilegesRowSet as $privilegeRow) {
        	$privilege = new Model_Privilege();
        	$privilege->setName($privilegeRow->name)
            	->setDescription($privilegeRow->description)
            	->setModule($privilegeRow->module)
            	->setController($privilegeRow->controller)
            	->setAction($privilegeRow->action)
            	->setCreated($privilegeRow->created)
            	->setChanged($privilegeRow->changed)
            	->setId($privilegeRow->id);
            $privileges[] = $privilege;
        }
         
        $userGroup = new Model_UserGroup();
        $userGroup->setName($row->name)
        		->setDescription($row->description)
        		->setPrivileges($privileges)
        		->setCreated($row->created)
        		->setChanged($row->changed)
        		->setId($row->id)
   				;
   		return $userGroup;
    }
        
    /**
     * (non-PHPdoc)
     * @see Model_TemporalMapper::findAll()
     */
    public function findAll() {
    	$whereState = sprintf("%s = 1", self::STATE_FIELDNAME);
        $resultSet = $this->getDbTable()->fetchAll($whereState);
        
        $entries = array();
        foreach ($resultSet as $row) {
            $entry = new Model_UserGroup();
            $entry->setName($row->name)
            	->setDescription($row->description)
            	->setCreated($row->created)
            	->setChanged($row->changed)
            	->setId($row->id);
            $entries[] = $entry;
        }
        
        return $entries;
    }
        
	/**
	 * (non-PHPdoc)
	 * @see Model_TemporalMapper::findByCriteria()
	 */   
	public function findByCriteria($filters = array(), $limit = NULL, $offset = NULL, $sortColumn = NULL, $sortDirection = NULL) {
		
		$where = $this->getFilterQuery($filters);
					
		// Order
		$order = '';
		switch ($sortColumn) {
			case 1:
				$order = 'name';
				break;
				
			case 2:
				$order = 'description';
				break;
				
			case 3:
				$order = 'created';
				break;
				
			case 4:
				$order = 'changed';
				break;
				
			default: $order = 'name';
		}
		
		$sortOrder = sprintf("%s %s", $order, $sortDirection);
		
		$whereState = sprintf("%s = 1", self::STATE_FIELDNAME);
        $resultSet = $this->getDbTable()->fetchAll("$whereState $where", $sortOrder, $limit, $offset);
        $entries = array();
        foreach ($resultSet as $row) {
            $entry = new Model_UserGroup();
            $entry->setName($row->name)
            	->setDescription($row->description)
            	->setCreated($row->created)
            	->setChanged($row->changed)
            	->setId($row->id);
            $entries[] = $entry;
        }
        
        return $entries;
    }
     
    /**
     * (non-PHPdoc)
     * @see Model_TemporalMapper::getTotalCount()
     */
    public function getTotalCount($filters = array()) {
		$where = $this->getFilterQuery($filters);
		
		$whereState = sprintf("%s = 1", self::STATE_FIELDNAME);
		$resultSet = $this->getDbTable()->fetchAll("$whereState $where");
		
		return count($resultSet);
    }
    
    /**
     * 
     * Verifies if the name user group already exist it.
     * @return boolean
     */
    public function verifyExistName($name) {
    	$whereState = sprintf("%s = 1", self::STATE_FIELDNAME);
    	$resultSet = $this->getDbTable()->fetchRow("$whereState AND name = '$name'");
    	if ($resultSet != NULL) {
    		return TRUE;
    	} else {
    		return FALSE;
    	}
    }
    
    /**
     * 
     * Finds the names of the models
     * @return array
     */
	public function findAllName() {
		$whereState = sprintf("%s = 1", self::STATE_FIELDNAME);
    	$resultSet = $this->getDbTable()->fetchAll($whereState);
        $data = array();
        foreach ($resultSet as $row) 
            $data[$row->id] = $row->name;
            
        return $data;
    }
}