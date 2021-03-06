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

class Model_PrivilegeMapper extends Model_TemporalMapper {
	
	/**
	 * 
	 * This class abstract is from Zend framework
	 * @var Zend_Db_Table_Abstract
	 */
	protected $_dbTable;
	
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
            $this->setDbTable('Model_DbTable_Privilege');
        }
        return $this->_dbTable;
    }

    /**
     * 
     * Saves model
     * @param Model_Privilege $privilege
     */
	public function save(Model_Privilege $privilege) {
		$data = array(
            'name' => $privilege->getName(),
            'description' => $privilege->getDescription(),
			'module' => $privilege->getModule(),
			'controller' => $privilege->getController(),
			'action' => $privilege->getAction(),
            'created' => date('Y-m-d H:i:s'),
			'createdBy' => $privilege->getCreatedBy(),
        	self::STATE_FIELDNAME => TRUE
        );

		unset($data['id']);
		$this->getDbTable()->insert($data);
    }

    /**
     * 
     * Updates model
     * @param int $id
     * @param Model_Privilege $privilege
     */
	public function update($id, Model_Privilege $privilege) {
        $result = $this->getDbTable()->find($id); 
        if (0 == count($result)) {
            return;
        }
        
	 	$data = array(
            'name' => $privilege->getName(),
            'description' => $privilege->getDescription(),
	 		'module' => $privilege->getModule(),
	 		'controller' => $privilege->getController(),
	 		'action' => $privilege->getAction(),
            'changed' => date('Y-m-d H:i:s'),
	 		'changedBy' => $privilege->getChangedBy()
        );

        $this->getDbTable()->update($data, array('id = ?' => $id));
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
     * @return Model_Privilege
     */
	public function find($id) {
        $result = $this->getDbTable()->find($id);
    	if (0 == count($result)) {
            return NULL;
        }
        
        $row = $result->current();
        
        $privilege = new Model_Privilege();
        $privilege->setName($row->name)
        		->setDescription($row->description)
        		->setModule($row->module)
        		->setController($row->controller)
        		->setAction($row->action)
        		->setCreatedBy($row->createdBy)
        		->setChangedBy($row->changedBy)
        		->setCreated($row->created)
        		->setChanged($row->changed)
        		->setId($row->id)
   				;
   		return $privilege;
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
            $entry = new Model_Privilege();
            $entry->setName($row->name)
            	->setDescription($row->description)
            	->setModule($row->module)
            	->setController($row->controller)
            	->setAction($row->action)
            	->setCreatedBy($row->createdBy)
            	->setChangedBy($row->changedBy)
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
				$order = 'module';
				break;
				
			case 4:
				$order = 'controller';
				break;
				
			case 5:
				$order = 'action';
				break;
				
			case 6:
				$order = 'created';
				break;
				
			case 7:
				$order = 'changed';
				break;
				
			default: $order = 'name';
		}
		
		$sortOrder = sprintf("%s %s", $order, $sortDirection);
		
		$whereState = sprintf("%s = 1", self::STATE_FIELDNAME);
        $resultSet = $this->getDbTable()->fetchAll("$whereState $where", $sortOrder, $limit, $offset);
        $entries = array();
        foreach ($resultSet as $row) {
            $entry = new Model_Privilege();
            $entry->setName($row->name)
            	->setDescription($row->description)
            	->setModule($row->module)
            	->setController($row->controller)
            	->setAction($row->action)
            	->setCreatedBy($row->createdBy)
            	->setChangedBy($row->changedBy)
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
     * Verifies if the name privilege already exist it.
     * @param string $name
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
     * Verifies if the id and name of the privilege already exist.
     * @param int $id
     * @param string $name
     * @return boolean
     */
    public function verifyExistIdAndName($id, $name) {
    	$whereState = sprintf("%s = 1", self::STATE_FIELDNAME);
    	$resultSet = $this->getDbTable()->fetchRow("$whereState AND id = $id AND  name = '$name'");
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