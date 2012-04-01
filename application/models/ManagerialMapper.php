<?php
class Model_ManagerialMapper {
	/**
	 * 
	 * Enter description here ...
	 * @var unknown_type
	 */
	protected $_dbTablePerson;
	
	/**
	 * 
	 * Enter description here ...
	 * @var unknown_type
	 */
	protected $_dbTable;
	
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

	public function getDbTablePerson() {
        if (null === $this->_dbTablePerson) {
            $this->setDbTablePerson('Model_DbTable_Person');
        }
        return $this->_dbTablePerson;
    }
    
    public function getDbTable() {
        if (null === $this->_dbTable) {
            $this->setDbTable('Model_DbTable_Managerial');
        }
        return $this->_dbTable;
    }
	
	public function save(Model_Managerial $managerial) {
        $data = array(
      		'name'   	  => $managerial->getName(),
            'firstName'   => $managerial->getFirstName(),
            'lastName'    => $managerial->getLastName(),
        	'dateOfBirth' => $managerial->getDateOfBirth(),
        	'phone'       => $managerial->getPhone(),
        	'phonework'   => $managerial->getPhonework(),
        	'phonemobil'  => $managerial->getPhonemobil(),
        	'sex'         => $managerial->getSex(),
        	'type'        => 1,
        	'created'     => date('Y-m-d H:i:s'),
        	'state'       => TRUE
        );
		// insert Person 
        $this->getDbTablePerson()->insert($data);
       	
		$person = $this->getDbTablePerson()->fetchRow("name = '".$managerial->getName()."'");
        $data = array(
      		'personId' => (int)$person->id,
        	'created'  => date('Y-m-d H:i:s'),
        	'state'    => TRUE
        );
        // insert Managerial
        $this->getDbTable()->insert($data);
    }

    public function update($id, Model_Managerial $managerial) {
    	$result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        
    	$data = array(
      		'name'   	  => $managerial->getName(),
            'firstName'   => $managerial->getFirstName(),
            'lastName'    => $managerial->getLastName(),
        	'dateOfBirth' => $managerial->getDateOfBirth(),
        	'phone'       => $managerial->getPhone(),
        	'phonework'   => $managerial->getPhonework(),
        	'phonemobil'  => $managerial->getPhonemobil(),
        	'sex'         => $managerial->getSex(),
        	'changed'     => date('Y-m-d H:i:s')
        );
        
        $row = $result->current();
        $this->getDbTablePerson()->update($data, array('id = ?' => (int)$row->personId));
        
        $person = $this->getDbTablePerson()->fetchRow("name = '".$managerial->getName()."'");
        $data = array(
      		'personId' => (int)$person->id,
        	'visible'  => TRUE,
        	'changed'  => date('Y-m-d H:i:s'),
        );
        
        $this->getDbTable()->update($data, array('id = ?' => $id));
    }
    
	public function delete($id) {
    	$result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        
        $data = array(
            'changed' => date('Y-m-d H:i:s'),
        	'state' => FALSE,
        );
        
        $row = $result->current();
        $this->getDbTablePerson()->update($data, array('id = ?' => (int)$row->personId));
        
        $data = array(
        	'changed' => date('Y-m-d H:i:s'),
        	'state'   => FALSE
        );
        
        $this->getDbTable()->update($data, array('id = ?' => $id));
    }
    
	public function find($id, Model_Managerial $managerial) {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        
        $row = $result->current();
        $resultPerson = $this->getDbTablePerson()->find((int)$row->personId);
		if (0 == count($resultPerson)) {
            return;
        }
        
        $row = $result->current();
        $rowPerson = $resultPerson->current();
        
//        $managerial->setName($name)
//        			->setFirstName($firstName)
//        			->setLastName($lastName)
//        			->setDateOfBirth($dateOfBirth)
//        			->setSex($sex)
        			
        
        			

//        $managerial->setId($row->id)
//        		->setName($rowPerson->name)
//       			->setFirstName($rowPerson->firstName)
//            	->setLastName($rowPerson->lastName)
//            	->setDateOfBirth($rowPerson->dateOfBirth)
//            	->setPhone($rowPerson->phone)
//            	->setPhonework($rowPerson->phonework)
//            	->setPhonemobil($rowPerson->phonemobil)
//            	->setSex($rowPerson->sex)
//            	->setCreated($rowPerson->created)
//            	->set
//          	 
	}
	
    public function findOri($id, Model_Person $person) {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        
        $row = $result->current();
        $resultPerson = $this->getDbTablePerson()->find((int)$row->personId);
//        $this->getDbTablePerson()->update($data, array('id = ?' => (int)$row->personId));
        
        $row = $result->current();
        $person->setId($row->id)
        	->setName($row->name)
       		->setFirstName($row->firstName)
            ->setLastName($row->lastName)
            ->setDateOfBirth($row->dateOfBirth)
            ->setPhone($row->phone)
            ->setPhonework($row->phonework)
            ->setPhonemobil($row->phonemobil)
            ->setSex($row->sex)
//            ->setType($row->type)
            ->setCreated($roe->created)  
    	;    
	}

    public function fetchAll() {
    	$resultSet = $this->getDbTable()->fetchAll('state = 1');
        return $resultSet;
    }
}