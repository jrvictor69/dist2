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

class Model_ProvinceMapper extends Model_TemporalMapper {	
	
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
    protected function setDbTable($dbTable) {
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
            $this->setDbTable('Model_DbTable_Province');
        }
        return $this->_dbTable;
    }

    /**
     * 
     * Saves model
     * @param Model_Province $province
     */
	public function save(Model_Province $province) {
        $data = array(
            'name' => $province->getName(),
            'description' => $province->getDescription(),
        	'departmentId' => $province->getDepartment()->getId(),
            'created' => date('Y-m-d H:i:s'),
        	self::STATE_FIELDNAME => TRUE
        );
        
		unset($data['id']);
		$this->getDbTable()->insert($data);
    }

    /**
     * 
     * Updates model
     * @param int $id
     * @param Model_Province $province
     */
	public function update($id, Model_Province $province) {
		$result = $this->getDbTable()->find($id); 
        if (0 == count($result)) {
            return;
        }
        
	 	$data = array(
            'name' => $province->getName(),
            'description' => $province->getDescription(),
	 		'departmentId' => $province->getDepartment()->getId(),
            'changed' => date('Y-m-d H:i:s')
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
     * @return Model_Province
     */
	public function find($id) {
        $result = $this->getDbTable()->find($id);
    	if (0 == count($result)) {
            return NULL;
        }
        
        $row = $result->current();
        
        $departmentMapper = new Model_DepartmentMapper();
        $department = $departmentMapper->find($row->departmentId);

        $province = new Model_Province();
        $province
        		->setName($row->name)
        		->setDescription($row->description)
        		->setDepartment($department)
        		->setCreated($row->created)
        		->setChanged($row->changed)
        		->setId($row->id)
        		;
    	return $province;
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
        	$departmentMapper = new Model_DepartmentMapper();
        	$department = $departmentMapper->find($row->departmentId);
        	
            $entry = new Model_Province();
            $entry->setName($row->name)
            	->setDescription($row->description)
            	->setDepartment($department)
            	->setCreated($row->created)
            	->setChanged($row->changed)
            	->setId($row->id)
            	;
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
				$order = 'departmentId';
				break;
				
			case 4:
				$order = 'created';
				break;
				
			case 5:
				$order = 'changed';
				break;
				
			default: $order = 'name';
		}
		
		$sortOrder = sprintf("%s %s", $order, $sortDirection);
		
		$whereState = sprintf("%s = 1", self::STATE_FIELDNAME);
        $resultSet = $this->getDbTable()->fetchAll("$whereState $where", $sortOrder, $limit, $offset);
        $entries = array();
        foreach ($resultSet as $row) {
        	$departmentMapper = new Model_DepartmentMapper();
        	$department = $departmentMapper->find($row->countryId);
        	
            $entry = new Model_Province();
            $entry->setName($row->name)
            	->setDescription($row->description)
            	->setDepartment($department)
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
     * Verifies if the name province already exist it.
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
	public function fetchAllName() {
		$whereState = sprintf("%s = 1", self::STATE_FIELDNAME);
    	$resultSet = $this->getDbTable()->fetchAll($whereState);
        $data = array();
        foreach ($resultSet as $row) 
            $data[$row->id] = $row->name;
            
        return $data;
    }
}