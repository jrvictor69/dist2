<?php
/**
 * DataMapper for Dist 2.
 *
 * @category Dist
 * @package Models
 * @author Victor Villca <victor.villca@people-t.com>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class Model_DataVaultMapper {
	
	/**
	 * 
	 * This class abstract is from Zend framework
	 * @var Zend_Db_Table_Abstract
	 */
	protected $_dbTable;

	/**
	 *  
	 * Creates a new object Zend_Db_Table_Abstract
	 * @param string $dbTable
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
	 * 
	 * Returns the class abstract
	 * @return Zend_Db_Table_Abstract
	 */
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
	 
    /**
     * 
     * Finds model with and without field binary
     * @param int $id
     * @param int $isBinary
     * @param Model_DataVault $dataVault
     */
    public function find($id, $isBinary = TRUE) {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return NULL;
        }
        
        $row = $result->current();
        
        $dataVault = new Model_DataVault();
        $dataVault
       			->setFilename($row->filename)
       			->setMimeType($row->mimeType)
       			->setExpires($row->expires)
       			->setCreated($row->created)
       			->setChanged($row->changed)
       			->setId($row->id)
        		;
        if ($isBinary) {
        	$dataVault->setBinary($row->binary);
        }
        
		return $dataVault;
    }
}