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

class Model_ManagerialMapper extends Model_TemporalMapper {
	
	/**
	 * 
	 * This class abstract for the table tblPerson
	 * @var Zend_Db_Table_Abstract
	 */
	protected $_dbTablePerson;
	
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
            $this->setDbTable('Model_DbTable_Managerial');
        }
        return $this->_dbTable;
    }
    
	public function setDbTablePerson($dbTable) {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTablePerson = $dbTable;
        return $this;
    }
    
	public function getDbTablePerson() {
        if (null === $this->_dbTablePerson) {
            $this->setDbTablePerson('Model_DbTable_Person');
        }
        return $this->_dbTablePerson;
    }
    
    /**
     * 
     * Saves model
     * @param Model_Managerial $managerial
     */
	public function save(Model_Managerial $managerial) {
		$dateCreated = date('Y-m-d H:i:s');
		
        $data = array(
        	'identityCard' => $managerial->getIdentityCard(),
            'firstName'    => $managerial->getFirstName(),
            'lastName'     => $managerial->getLastName(),
        	'dateOfBirth'  => $managerial->getDateOfBirth(),
        	'phone'        => $managerial->getPhone(),
        	'phonework'    => $managerial->getPhonework(),
        	'phonemobil'   => $managerial->getPhonemobil(),
        	'sex'          => $managerial->getSex(),
        	'type'         => $managerial->getType(),
        	'created'      => $dateCreated,
        	'profilePictureId'    => NULL,
        	self::STATE_FIELDNAME  => TRUE
        );
        
		// Saves a person 
		unset($data['id']);
        $this->getDbTablePerson()->insert($data);
        
        $personId = (int)$this->getDbTablePerson()->getAdapter()->lastInsertId();
        
        $data = array(
      		'personId' => $personId,
        	'accountId' => $managerial->getAccount()->getId(),
        	'userGroupId' => $managerial->getUserGroup()->getId(),
            'provinceId' => NULL,
        	'visible' => TRUE,
        	'created' => $dateCreated,
        	self::STATE_FIELDNAME => TRUE
        );
        // Saves a managerial
        unset($data['id']);
        $this->getDbTable()->insert($data);
    }

    /**
     * 
     * Updates model
     * @param int $id
     * @param Model_Managerial $managerial
     */
    public function update($id, Model_Managerial $managerial) {
    	$result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        
        $dateChanged = date('Y-m-d H:i:s');
        
    	$data = array(
    		'identityCard' => $managerial->getIdentityCard(),
            'firstName'    => $managerial->getFirstName(),
            'lastName'     => $managerial->getLastName(),
        	'dateOfBirth'  => $managerial->getDateOfBirth(),
        	'phone'        => $managerial->getPhone(),
        	'phonework'    => $managerial->getPhonework(),
        	'phonemobil'   => $managerial->getPhonemobil(),
        	'sex'          => $managerial->getSex(),
    		'type'         => $managerial->getType(),
        	'profilePictureId' => NULL,
        	'changed'      => $dateChanged
        );
        
        $row = $result->current();
        $this->getDbTablePerson()->update($data, array('id = ?' => $row->personId));
        
        $data = array(
      		'personId' => $row->personId,
//        	'provinceId' => $managerial->getProvince()->getId(),
        	'provinceId' => NULL,
        	'accountId' => $managerial->getAccount()->getId(),
        	'userGroupId' => $managerial->getUserGroup()->getId(),
        	'visible' => TRUE,
        	'changed' => $dateChanged,
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
        	self::STATE_FIELDNAME => FALSE
        );
        
        $row = $result->current();
        $this->getDbTablePerson()->update($data, array('id = ?' => $row->personId));
        
        $this->getDbTable()->update($data, array('id = ?' => $id));
    }
    
	/**
     * (non-PHPdoc)
     * @see Model_TemporalMapper::find()
     * @return Model_Managerial
     */
	public function find($id) {
		// Finds abstract Managerial
      	$result = $this->getDbTable()->find($id);
    	if (0 == count($result)) {
            return NULL;
        }
        $row = $result->current();
        
        $accountMapper = new Model_AccountMapper();
        $account = $accountMapper->find($row->accountId);

        $provinceMapper = new Model_ProvinceMapper();
        $province = $provinceMapper->find($row->provinceId);

        $userGroupMapper = new Model_UserGroupMapper();
        $userGroup = $userGroupMapper->find($row->userGroupId);
        
        $managerial = new Model_Managerial();
        if ($account != NULL) {
        	$managerial->setAccount($account);
        }
        
		if ($province != NULL) {
        	$managerial->setProvince($province);
        }
        
		if ($userGroup != NULL) {
        	$managerial->setUserGroup($userGroup);
        }
        
        // Finds abstract Person 
		$resultPerson = $this->getDbTablePerson()->find((int)$row->personId);
        $rowPerson = $resultPerson->current();
        
        $managerial
        		->setIdentityCard($rowPerson->identityCard)
        		->setVisible($row->visible)
        		->setCreated($row->created)
        		->setChanged($row->changed)
        		->setFirstName($rowPerson->firstName)
        		->setLastName($rowPerson->lastName)
        		->setDateOfBirth($rowPerson->dateOfBirth)
        		->setType($rowPerson->type)
        		->setSex($rowPerson->sex)
        		->setPhone($rowPerson->phone)
        		->setPhonework($rowPerson->phonework)
        		->setPhonemobil($rowPerson->phonemobil)
        		->setId($row->id)
        		;
        
   		return $managerial;
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
	        $accountMapper = new Model_AccountMapper();
	        $account = $accountMapper->find($row->accountId);
	        	
	        $provinceMapper = new Model_ProvinceMapper();
	        $province = $provinceMapper->find($row->provinceId);
	        
	        $userGroupMapper = new Model_UserGroupMapper();
	        $userGroup = $userGroupMapper->find($row->userGroupId);
	        
	        $managerial = new Model_Managerial();
	        if ($account != NULL) {
	        	$managerial->setAccount($account);
	        }
	        
			if ($province != NULL) {
	        	$managerial->setProvince($province);
	        }
	        
			if ($userGroup != NULL) {
	        	$managerial->setUserGroup($userGroup);
	        }
	        
	        // Finds abstract Person 
			$resultPerson = $this->getDbTablePerson()->find((int)$row->personId);
	        $rowPerson = $resultPerson->current();
	        
	        $managerial
	        		->setVisible($row->visible)
	        		->setCreated($row->created)
	        		->setChanged($row->changed)
	        		->setFirstName($rowPerson->firstName)
	        		->setLastName($rowPerson->lastName)
	        		->setDateOfBirth($rowPerson->dateOfBirth)
	        		->setType($rowPerson->type)
	        		->setSex($rowPerson->sex)
	        		->setPhone($rowPerson->phone)
	        		->setPhonework($rowPerson->phonework)
	        		->setPhonemobil($rowPerson->phonemobil)
	        		->setId($row->id)
	        		;
            $entries[] = $managerial;
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
				$order = 'personId';
				break;
				
			default: $order = 'personId';
		}
		
		$sortOrder = sprintf("%s %s", $order, $sortDirection);
		
		$whereState = sprintf("%s = 1", self::STATE_FIELDNAME);
        $resultSet = $this->getDbTable()->fetchAll("$whereState $where", $sortOrder, $limit, $offset);
        $entries = array();
		foreach ($resultSet as $row) {
	        $accountMapper = new Model_AccountMapper();
	        $account = $accountMapper->find($row->accountId);
	        	
	        $provinceMapper = new Model_ProvinceMapper();
	        $province = $provinceMapper->find($row->provinceId);
	        
	        $userGroupMapper = new Model_UserGroupMapper();
	        $userGroup = $userGroupMapper->find($row->userGroupId);
	        
	        $managerial = new Model_Managerial();
	        if ($account != NULL) {
	        	$managerial->setAccount($account);
	        }
	        
			if ($province != NULL) {
	        	$managerial->setProvince($province);
	        }
	        
			if ($userGroup != NULL) {
	        	$managerial->setUserGroup($userGroup);
	        }
	        
	        // Finds abstract Person 
			$resultPerson = $this->getDbTablePerson()->find((int)$row->personId);
	        $rowPerson = $resultPerson->current();
	        
	        $managerial
	        		->setVisible($row->visible)
	        		->setCreated($row->created)
	        		->setChanged($row->changed)
	        		->setFirstName($rowPerson->firstName)
	        		->setLastName($rowPerson->lastName)
	        		->setDateOfBirth($rowPerson->dateOfBirth)
	        		->setType($rowPerson->type)
	        		->setSex($rowPerson->sex)
	        		->setPhone($rowPerson->phone)
	        		->setPhonework($rowPerson->phonework)
	        		->setPhonemobil($rowPerson->phonemobil)
	        		->setId($row->id)
	        		;
            $entries[] = $managerial;
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
     * Verifies if the identity card managerial already exist it.
     * @return boolean
     */
	public function verifyExistIdentityCard($identityCard) {
    	$whereState = sprintf("%s = 1", self::STATE_FIELDNAME);
    	$resultSet = $this->getDbTablePerson()->fetchRow("$whereState AND identityCard = $identityCard");
    	if ($resultSet != NULL) {
    		return TRUE;
    	} else {
    		return FALSE;
    	}
    }
    
	/**
     * 
     * Verifies if the id and identity card of the managerial already exist.
     * @param int $id
     * @param int $identityCard
     * @return boolean
     */
    public function verifyExistIdAndIdentityCard($id, $identityCard) {
    	$whereState = sprintf("%s = 1", self::STATE_FIELDNAME);
    	$resultSet = $this->getDbTable()->fetchRow("$whereState AND id = $id AND  identityCard = $identityCard");
    	if ($resultSet != NULL) {
    		return TRUE;
    	} else {
    		return FALSE;
    	}
    }
    
	/**
	 * 
     * @see Model_TemporalMapper::find()
     * @return Model_Managerial
     */
	public function findByIdentityCard($identityCard) {
		$whereState = sprintf("%s = 1", self::STATE_FIELDNAME);
    	$resultSet = $this->getDbTablePerson()->fetchRow("$whereState AND identityCard = $identityCard");
    	if ($resultSet != NULL) {
    		return TRUE;
    	} else {
    		return FALSE;
    	}
        
        $row = $resultSet->current();
        
        $accountMapper = new Model_AccountMapper();
        $account = $accountMapper->find($row->accountId);

        $provinceMapper = new Model_ProvinceMapper();
        $province = $provinceMapper->find($row->provinceId);

        $userGroupMapper = new Model_UserGroupMapper();
        $userGroup = $userGroupMapper->find($row->userGroupId);
        
        $managerial = new Model_Managerial();
        if ($account != NULL) {
        	$managerial->setAccount($account);
        }
        
		if ($province != NULL) {
        	$managerial->setProvince($province);
        }
        
		if ($userGroup != NULL) {
        	$managerial->setUserGroup($userGroup);
        }
        
        // Finds abstract Person 
		$resultPerson = $this->getDbTablePerson()->find((int)$row->personId);
        $rowPerson = $resultPerson->current();
        
        $managerial
        		->setVisible($row->visible)
        		->setCreated($row->created)
        		->setChanged($row->changed)
        		->setIdentityCard($rowPerson->identityCard)
        		->setFirstName($rowPerson->firstName)
        		->setLastName($rowPerson->lastName)
        		->setDateOfBirth($rowPerson->dateOfBirth)
        		->setType($rowPerson->type)
        		->setSex($rowPerson->sex)
        		->setPhone($rowPerson->phone)
        		->setPhonework($rowPerson->phonework)
        		->setPhonemobil($rowPerson->phonemobil)
        		->setId($row->id)
        		;
        
   		return $managerial;
    }
    
}