<?php

class Model_DataVaultMapper {
	
	/**
	 * 
	 * Enter description here ...
	 * @var unknown_type
	 */
	protected $_dbTable;

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

    public function getDbTable() {
        if (null === $this->_dbTable) {
            $this->setDbTable('Model_DbTable_DataVault');
        }
        return $this->_dbTable;
    }

    /**
     * 
     * Saves model
     * @param Model_DataVault $dataVault
     */
    public function save(Model_DataVault $dataVault) {
    	$data = array(
        	'filename' => $dataVault->getFilename(),
        	'mimeType' => $dataVault->getMimeType(),
            'binary'   => $dataVault->getBinary(),
            'created'  => date('Y-m-d H:i:s'),
    		'state'    => TRUE
        );
		
		unset($data['id']);
		$this->getDbTable()->insert($data);
		
		$id = (int)$this->getDbTable()->getAdapter()->lastInsertId();
		$dataVault->setId($id);
    }

    /**
     * 
     * Updates model
     * @param int $id
     * @param Model_DataVault $dataVault
     */
    public function update($id, Model_DataVault $dataVault) {
    	$result = $this->getDbTable()->find($id); 
        if (0 == count($result)) {
            return;
        }
        
        $data = array(
        	'filename' => $dataVault->getFilename(),
        	'mimeType' => $dataVault->getMimeType(),
            'binary'   => $dataVault->getBinary(),
            'changed'  => date('Y-m-d H:i:s'),
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
    
//    public function delete($id) {
//    	$result = $this->getDbTable()->find($id);
//        if (0 == count($result)) {
//            return;
//        }
//        
//    	$this->getDbTable()->delete(array('id = ?' => $id));
//    }
	 
    /**
     * 
     * Finds model
     * @param int $id
     * @param Model_DataVault $dataVault
     */
    public function find($id) {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return NULL;
        }
        
        $row = $result->current();
        
        $dataVault = new Model_DataVault();
        $dataVault
       			->setFilename($row->filename)
       			->setMimeType($row->mimeType)
       			->setBinary($row->binary)
       			->setExpires($row->expires)
       			->setCreated($row->created)
       			->setChanged($row->changed)
       			->setId($row->id)
        		;
		return $dataVault;
    }
	  
    /**
     * 
     * Finds models
     * @return array
     */
    public function fetchAll() {
        $resultSet = $this->getDbTable()->fetchAll();
        return $resultSet;
    }
}