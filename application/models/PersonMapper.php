<?php
class Model_PersonMapper {
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
            $this->setDbTable('Model_DbTable_Person');
        }
        return $this->_dbTable;
    }
	
	public function save(Model_Person $person) {
        $data = array(
      		'name'   	  => $person->getName(),
            'firstName'   => $person->getFirstName(),
            'lastName'    => $person->getLastName(),
        	'dateOfBirth' => $person->getDateOfBirth(),
        	'phone'       => $person->getPhone(),
        	'phonework'   => $person->getPhonework(),
        	'phonemobil'  => $person->getPhonemobil(),
        	'sex'         => $person->getSex(),
        	'type'        => $person->getType(),
        	'created'     => $person->getCreated(),
        	'type'        => TRUE
        );

        $this->getDbTable()->fetchRow("name = 'Daniel'");
//        if (null === ($id = $person->getId())) {
//            unset($data['id']);
//            $this->getDbTable()->fetchRow('name ='.$)//insert($data);
//        } else {
//            $this->getDbTable()->update($data, array('id = ?' => $id));
//        }
    }

    public function update($id, Model_Person $person) {
    	$result = $this->getDbTable()->find($id);
    	
        if (0 == count($result)) {
            return;
        }
        
    	$data = array(
      		'name'   	  => $person->getName(),
            'firstName'   => $person->getFirstName(),
            'lastName'    => $person->getLastName(),
        	'dateOfBirth' => $person->getDateOfBirth(),
        	'phone'       => $person->getPhone(),
        	'phonework'   => $person->getPhonework(),
        	'phonemobil'  => $person->getPhonemobil(),
        	'sex'         => $person->getSex(),
        	'type'        => $person->getType(),
        	'created'     => $person->getCreated(),
        	'type'        => TRUE
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
        
    	$this->getDbTable()->update($data, array('id = ?' => $id));
    }
    
    public function find($id, Model_Person $person) {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        
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
//        $entries   = array();
//        foreach ($resultSet as $row) {
//            $entry = new Model_Guestbook();
//            $entry->setId($row->id)
//                  ->setEmail($row->email)
//                  ->setComment($row->comment)
//                  ->setCreated($row->created);
//            $entries[] = $entry;
//        }
//        return $entries;
    }
}