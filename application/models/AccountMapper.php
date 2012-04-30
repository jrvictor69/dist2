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

class Model_AccountMapper extends Model_TemporalMapper {
	
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
            $this->setDbTable('Model_DbTable_Account');
        }
        return $this->_dbTable;
    }

    /**
     * 
     * Saves model
     * @param Model_Account $account
     */
    public function save(Model_Account $account) {
        $data = array(
        	'username' => $account->getUsername(),
        	'password' => md5($account->getPassword()),
            'email'    => $account->getEmail(),
            'role' 	   => $account->getRole(),
        	'created'  => date('Y-m-d H:i:s'),
        	self::STATE_FIELDNAME => TRUE
        );

       	unset($data['id']);
        $this->getDbTable()->insert($data);
    }

    /**
     * 
     * Updates model
     * @param int $id
     * @param Model_Account $account
     */
	public function update($id, Model_Account $account) {
		$result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        
	 	$data = array(
        	'username' => $account->getUsername(),
        	'password' => md5($account->getPassword()),
            'email'    => $account->getEmail(),
            'role' 	   => $account->getRole(),
	 		'changed' => date('Y-m-d H:i:s'),
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
     * @return Model_Account
     */
	public function find($id) {
        $result = $this->getDbTable()->find($id);
    	if (0 == count($result)) {
            return NULL;
        }
        
        $row = $result->current();
 		       
        $account = new Model_Account();
        $account->setUsername($row->username)
        		->setPassword($row->password)
        		->setEmail($row->email)
        		->setRole($row->role)
        		->setCreated($row->created)
        		->setChanged($row->changed)
        		->setId($row->id);
        		
        return $account;
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
        	echo var_dump($row); echo "<br>";
            $entry = new Model_Account();
//            $entry->setUsername($row->$username);
//        	$entry->setUsername($row->username)
//        		->setPassword($row->password)
//        		->setEmail($row->email)
//        		->setRole($row->role)
//        		->setCreated($row->created)
//        		->setChanged($row->changed)
//        		->setId($row->$id);
            $entries[] = $entry;
        }
        var_dump($entries); exit;
        return $entries;
    }
    
    /**
     * (non-PHPdoc)
     * @see Model_TemporalMapper::findByCriteria()
     */
	public function findByCriteria($filters = array(), $limit = NULL, $offset = NULL, $sortColumn = NULL, $sortDirection = NULL) {
		
//		$where = $this->getFilterQuery($filters);
//					
//		// Order
//		$order = '';
//		switch ($sortColumn) {
//			case 1:
//				$order = 'name';
//				break;
//				
//			case 2:
//				$order = 'description';
//				break;
//				
//			case 3:
//				$order = 'created';
//				break;
//				
//			case 4:
//				$order = 'changed';
//				break;
//				
//			default: $order = 'name';
//		}
//		
//		$sortOrder = sprintf("%s %s", $order, $sortDirection);
		
		$whereState = sprintf("%s = 1", self::STATE_FIELDNAME);
//        $resultSet = $this->getDbTable()->fetchAll("$whereState $where", $sortOrder, $limit, $offset);
        $entries = array();
//        foreach ($resultSet as $row) {
//            $entry = new Model_Category();
//            $entry->setName($row->name)
//            	->setDescription($row->description)
//            	->setCreated($row->created)
//            	->setChanged($row->changed)
//            	->setId($row->id);
//            $entries[] = $entry;
//        }
        
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
     * Finds Account model by password
     * @param $password
     * @return Model_Account
     */
	public function findByPassword($password) {
    	$resultSet = $this->getDbTable()->fetchRow("password = '". md5($password)."'");
    	if ($resultSet != NULL) {
    		return $resultSet;
    	} else {
    		return FALSE;
    	}    			    	
    }
    
    public function findLast() {
    	$accountId = (int)$this->getDbTable()->getAdapter()->lastInsertId();
    	return $this->find($accountId);
    }
}