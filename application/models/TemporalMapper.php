<?php
/**
 * PHP Framework for Dist 2
 *
 * @category Dist 2
 * @package Models
 * @author Victor Villca <victor.villca@swissbytes.ch>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

abstract class Model_TemporalMapper {
	const STATE_FIELDNAME = 'state';
		
	/**
	 * 
	 * Returns the code sql of the condition "where"
	 * @param array $filters
	 * @return string
	 */
	protected function getFilterQuery($filters = array()) {
		$where = "";
		foreach ($filters as $filter) {
			$where = $where .' AND '. $filter['field'] .' '. $filter['operator'].' "' . $filter['filter'] . '"';
		}
				
		return $where;
	}
	
	/**
	 *  
	 * Creates a new object Zend_Db_Table_Abstract
	 * @param string $dbTable
	 */
	abstract protected function setDbTable($dbTable);
	
	/**
	 * 
	 * Returns the class abstract
	 * @return Zend_Db_Table_Abstract
	 */
	abstract protected function getDbTable();
	
	/**
	 * 
	 * Finds a model by id
	 * @param int $id
	 * @return Model
	 */
	abstract protected function find($id);
	
	/**
	 * 
	 * Returns all models
	 * @return Array Models
	 */
	abstract protected function findAll();
	
	/**
	 * 
	 * Returns models according the filters
	 * @param array $filters
	 * @param int $limit
	 * @param int $offset
	 * @param int $sortColumn
	 * @param string $sortDirection
	 * @return Array Objects
	 */
	abstract protected function findByCriteria($filters = array(), $limit = NULL, $offset = NULL, $sortColumn = NULL, $sortDirection = NULL);
	
	/**
	 * 
	 * Finds count of models according the filters
	 * @param array $filters
	 * @return int
	 */
	abstract protected function getTotalCount($filters = array());
}