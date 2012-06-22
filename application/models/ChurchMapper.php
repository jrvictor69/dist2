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

class Model_ChurchMapper extends Model_TemporalMapper {

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
			$this->setDbTable('Model_DbTable_Church');
		}
		return $this->_dbTable;
	}

	/**
	 * 
	 * Saves model
	 * @param Model_Church $church
	 */
	public function save(Model_Church $church) {
		$data = array(
			'name' => $church->getName(),
			'address' => $church->getAddress(),
			'created' => date('Y-m-d H:i:s'),
			'createdBy' => $church->getCreatedBy(),
			self::STATE_FIELDNAME => TRUE
		);

		unset($data['id']);
		$this->getDbTable()->insert($data);
	}

	/**
	 * 
	 * Updates model
	 * @param int $id
	 * @param Model_Church $church
	 */
	public function update($id, Model_Church $church) {
		$result = $this->getDbTable()->find($id); 
		if (0 == count($result)) {
			return;
		}

	 	$data = array(
			'name' => $church->getName(),
			'address' => $church->getAddress(),
			'changed' => date('Y-m-d H:i:s'),
			'changedBy' => $church->getChangedBy()
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
	 * @return Model_Church
	 */
	public function find($id) {
		$result = $this->getDbTable()->find($id);
		if (0 == count($result)) {
			return NULL;
		}

		$row = $result->current();

		$church = new Model_Church();
		$church->setName($row->name)
				->setAddress($row->address)
				->setCreated($row->created)
				->setChanged($row->changed)
				->setId($row->id)
				;
		return $church;
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
			$entry = new Model_Church();
			$entry->setName($row->name)
				->setAddress($row->address)
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
				$order = 'address';
				break;
				
			case 3:
				$order = 'created';
				break;
				
			case 4:
				$order = 'changed';
				break;
				
			default: $order = 'name';
		}

		$sortOrder = sprintf("%s %s", $order, $sortDirection);
		
		$whereState = sprintf("%s = 1", self::STATE_FIELDNAME);
		$resultSet = $this->getDbTable()->fetchAll("$whereState $where", $sortOrder, $limit, $offset);
		$entries = array();
		foreach ($resultSet as $row) {
			$entry = new Model_Church();
			$entry->setName($row->name)
				->setAddress($row->address)
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
	 * Verifies if the name church already exist it.
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
	 * Verifies if the id and name of the church already exist.
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
	public function fetchAllName() {
		$whereState = sprintf("%s = 1", self::STATE_FIELDNAME);
		$resultSet = $this->getDbTable()->fetchAll($whereState);
		$data = array();
		foreach ($resultSet as $row) 
			$data[$row->id] = $row->name;

		return $data;
	}

	/**
	 *
	 * Finds only names by criteria
	 * @param array $filters
	 */
	public function findByCriteriaOnlyName($filters = array()) {
		$where = $this->getFilterQuery($filters);
		$sortOrder = sprintf("%s %s", 'name', 'asc');
		$whereState = sprintf("%s = 1", self::STATE_FIELDNAME);

		$resultSet = $this->getDbTable()->fetchAll("$whereState $where", $sortOrder);
		$data = array();
		foreach ($resultSet as $row) {
			$data[] = $row->name;
		}
		return $data;
	}
}