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

class Model_MemberFileMapper extends Model_TemporalMapper {
	
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
            $this->setDbTable('Model_DbTable_MemberFile');
        }
        return $this->_dbTable;
    }
    
    /**
     * 
     * Saves model
     * @param Model_MemberFile $memberFile
     */
	public function save(Model_MemberFile $memberFile) {
		$file = $memberFile->getFile();
		$dataVaultMapper = new Model_DataVaultMapper();
		$dataVaultMapper->save($file);

        $data = array(
            'name' => $memberFile->getName(),
            'note' => $memberFile->getNote(),
            'created' => date('Y-m-d H:i:s'),
        	self::STATE_FIELDNAME => TRUE,
        	'memberId' => 1,
        	'fileId' => $file->getId(),
        	'createdById' => $memberFile->getCreatedBy()->getId(),
        );
		
		unset($data['id']);
		$this->getDbTable()->insert($data);
    }

    /**
     * 
     * Updates model
     * @param int $id
     * @param Model_MemberFile $memberFile
     */
	public function update($id, Model_MemberFile $memberFile) {
		$result = $this->getDbTable()->find($id); 
        if (0 == count($result)) {
            return;
        }
        
	 	$data = array(
            'name' => $memberFile->getName(),
            'note' => $memberFile->getNote(),
            'changed' => date('Y-m-d H:i:s'),
	 		'memberId' => 1,
        	'fileId' => $memberFile->getFile()->getId(),
        	'createdById' => $memberFile->getCreatedBy()->getId(),
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
        
        $row = $result->current();
        
		$dataVaultMapper = new Model_DataVaultMapper();
		$dataVaultMapper->delete($row->fileId);
				
        $data = array(
            'changed' => date('Y-m-d H:i:s'),
        	self::STATE_FIELDNAME => FALSE,
        );
        
    	$this->getDbTable()->update($data, array('id = ?' => $id));
    }

    /**
     * (non-PHPdoc)
     * @see Model_TemporalMapper::find()
     * @return Model_MemberFile
     */
    public function find($id) {
        $result = $this->getDbTable()->find($id);
    	if (0 == count($result)) {
            return NULL;
        }
        
        $row = $result->current();
        
        $member = new Model_Member();
        $member->setId(1);
        
        $dataVaultMapper = new Model_DataVaultMapper();
        $file = $dataVaultMapper->find($row->fileId);
        
        $accountMapper = new Model_AccountMapper();
        $createdBy = $accountMapper->find($row->createdById);
        
        $memberFile = new Model_MemberFile();
        $memberFile->setName($row->name)
        		->setNote($row->note)
        		->setVisibleExtWeb($row->visibleExtWeb)
        		->setChangedBy($row->changedBy)
        		->setMember($member)
        		->setFile($file)
        		->setCreatedBy($createdBy)
        		->setCreated($row->created)
        		->setChanged($row->changed)
        		->setId($row->id)
        		;
    	return $memberFile;
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
			$member = new Model_Member();
			$member->setId(1);
        
			$dataVaultMapper = new Model_DataVaultMapper();
			$file = $dataVaultMapper->find($row->fileId);
        
			$accountMapper = new Model_AccountMapper();
			$createdBy = $accountMapper->find($row->createdById);
        	
            $entry = new Model_MemberFile();
            
			$entry->setName($row->name)
					->setNote($row->note)
					->setVisibleExtWeb($row->visibleExtWeb)
					->setChangedBy($row->changedBy)
					->setMember($member)
					->setFile($file)
					->setCreatedBy($createdBy)
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
				$order = 'note';
				break;
				
			case 3:
				$order = 'fileId';
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
            $member = new Model_Member();
			$member->setId(1);
        
			$dataVaultMapper = new Model_DataVaultMapper();
			$file = $dataVaultMapper->find($row->fileId, FALSE);
        
			$accountMapper = new Model_AccountMapper();
			$createdBy = $accountMapper->find($row->createdById);
        	
            $entry = new Model_MemberFile();
            
			$entry->setName($row->name)
					->setNote($row->note)
					->setVisibleExtWeb($row->visibleExtWeb)
					->setChangedBy($row->changedBy)
					->setMember($member)
					->setFile($file)
					->setCreatedBy($createdBy)
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
     * Verifies if the name Member file already exist it.
     * @param string $name
     * @return boolean
     */
	public function verifyExistName($name) {
    	$whereState = sprintf("%s = 1", self::STATE_FIELDNAME);
    	$resultSet = $this->getDbTable()->fetchRow("$whereState AND name = '$name'");
    	return $resultSet != NULL? TRUE : FALSE;
    }
    
	/**
     * 
     * Verifies if the id and name of the Member file already exist.
     * @param int $id
     * @param string $name
     * @return boolean
     */
    public function verifyExistIdAndName($id, $name) {
    	$whereState = sprintf("%s = 1", self::STATE_FIELDNAME);
    	$resultSet = $this->getDbTable()->fetchRow("$whereState AND id = $id AND  name = '$name'");
    	return $resultSet != NULL? TRUE : FALSE;
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