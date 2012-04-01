<?php
/**
 * DIST
 * Done
 * @category Dist
 * @package application\models
 * @author Victor Villca <victor.villca@swissbytes.ch>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class Model_ProvinceMapper {	
	
	/**
	 * 
	 * Enter description here ...
	 * @var unknown_type
	 */
	protected $_dbTable;

    private function setDbTable($dbTable) {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }
	
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
            'name'   => $province->getName(),
            'description' => $province->getDescription(),
        	'departmentId' => $province->getDepartment()->getId(),
            'created' => date('Y-m-d H:i:s'),
        	'state' => TRUE
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
            'name'   => $province->getName(),
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
        	'state' => FALSE,
        );
        
    	$this->getDbTable()->update($data, array('id = ?' => $id));
    }

    /**
     * 
     * Finds model
     * @param int $id
     * @param Model_Province $province
     */
    public function find($id, Model_Province $province) {
        $result = $this->getDbTable()->find($id);
    	if (0 == count($result)) {
            return;
        }
        
        $row = $result->current();
        
        $department = new Model_Department();
        $departmentMapper = new Model_DepartmentMapper();
        $departmentMapper->find($row->departmentId, $department);
        
        $province->setId($row->id)
        		->setName($row->name)
        		->setDescription($row->description)
        		->setDepartment($department)
        		->setCreated($row->created)
        		->setChanged($row->changed)
        		;
    }
     
    /**
     * 
     * Finds models
     * @return array
     */
    public function fetchAll() {
        $entries = $this->getDbTable()->fetchAll('state = 1');
        return $entries;
    }
    
 	/**
     * 
     * Finds the names of the models
     * @return array
     */
    public function fetchAllName() {
    	$resultSet = $this->getDbTable()->fetchAll('state = 1');
        $data = array();
        foreach ($resultSet as $row) 
            $data[$row->id] = $row->name;
            
        return $data;
    }	
}