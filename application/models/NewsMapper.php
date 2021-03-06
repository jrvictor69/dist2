<?php
/**
 * Model for Dist 2
 *
 * @category Dist
 * @package Models
 * @author Victor Villca <victor.villca@swissbytes.ch>
 * @copyright Copyright (c) 2012 Emini A/S
 * @license Proprietary
 */

class Model_NewsMapper extends Model_TemporalMapper {

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
            $this->setDbTable('Model_DbTable_News');
        }
        return $this->_dbTable;
    }
    
	/**
     * 
     * Saves model
     * @param Model_News $news
     */
	public function save(Model_News $news) {
		$data = array(
            'title' => $news->getTitle(),
            'summary' => $news->getSummary(),
			'contain' => $news->getContain(),
			'fount' => $news->getFount(),
			'imagename' => $news->getImagename(),
			'newsdate' => date('Y-m-d H:i:s'),
            'created' => date('Y-m-d H:i:s'),
			'createdBy' => $news->getCreatedBy(),
			'categoryId' => $news->getCategory()->getId(),
			'managerialId' => 1,
        	self::STATE_FIELDNAME => TRUE
        );
        
		unset($data['id']);
		$this->getDbTable()->insert($data);
    }
    
	/**
     * 
     * Updates model
     * @param int $id
     * @param Model_News $news
     */
	public function update($id, Model_News $news) {
        $result = $this->getDbTable()->find($id); 
        if (0 == count($result)) {
            return;
        }
        
	 	$data = array(
            'title' => $news->getTitle(),
            'summary' => $news->getSummary(),
			'contain' => $news->getContain(),
			'fount' => $news->getFount(),
			'imagename' => $news->getImagename(),
			'newsdate' => $news->getNewsdate(),
            'changed' => date('Y-m-d H:i:s'),
	 		'changedBy' => $news->getChangedBy(),
	 		'categoryId' => $news->getCategory()->getId(),
	 		'managerialId' => 1,
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
     * @return Model_News
     */
	public function find($id) {
        $result = $this->getDbTable()->find($id);
    	if (0 == count($result)) {
            return NULL;
        }
        
        $row = $result->current();
        
        $categoryMapper = new Model_CategoryMapper();
        $category = $categoryMapper->find($row->categoryId);
        
		$news = new Model_News();
        $news->setTitle($row->title)
        	->setSummary($row->summary)
			->setContain($row->contain)
			->setFount($row->fount)
			->setImagename($row->imagename)
			->setNewsdate($row->newsdate)
			->setCreated($row->created)
			->setChanged($row->changed)
			->setCreatedBy($row->createdBy)
			->setChangedBy($row->changedBy)
			->setCategory($category)
			->setId($row->id);
		
   		return $news;
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
        	$categoryMapper = new Model_CategoryMapper();
        	$category = $categoryMapper->find($row->categoryId);
        	
        	$entry = new Model_News();
        	$entry->setTitle($row->title)
        		->setSummary($row->summary)
				->setContain($row->contain)
				->setFount($row->fount)
				->setImagename($row->imagename)
				->setNewsdate($row->newsdate)
				->setCreated($row->created)
				->setChanged($row->changed)
				->setCreatedBy($row->createdBy)
				->setChangedBy($row->changedBy)
				->setCategory($category)
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
				$order = 'title';
				break;
				
			case 2:
				$order = 'summary';
				break;
				
			case 3:
				$order = 'categoryId';
				break;
				
			case 4:
				$order = 'imagename';
				break;
				
			case 5:
				$order = 'newsdate';
				break;
				
			case 6:
				$order = 'fount';
				break;
				
			case 7:
				$order = 'created';
				break;
				
			case 8:
				$order = 'changed';
				break;
				
			default: $order = 'title';
		}
		
		if ($sortDirection == NULL) {
			$sortDirection = 'asc';
		}
		
		$sortOrder = sprintf("%s %s", $order, $sortDirection);
		
		$whereState = sprintf("%s = 1", self::STATE_FIELDNAME);
        $resultSet = $this->getDbTable()->fetchAll("$whereState $where", $sortOrder, $limit, $offset);
        
        $entries = array();
        foreach ($resultSet as $row) {
        	$categoryMapper = new Model_CategoryMapper();
        	$category = $categoryMapper->find($row->categoryId);
        	
        	$entry = new Model_News();
        	$entry->setTitle($row->title)
        		->setSummary($row->summary)
				->setContain($row->contain)
				->setFount($row->fount)
				->setImagename($row->imagename)
				->setNewsdate($row->newsdate)
				->setCreated($row->created)
				->setChanged($row->changed)
				->setCreatedBy($row->createdBy)
				->setChangedBy($row->changedBy)
				->setCategory($category)
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
     * Verifies if the title news already exist it.
     * @param string $title
     * @return boolean
     */
	public function verifyExistTitle($title) {
    	$whereState = sprintf("%s = 1", self::STATE_FIELDNAME);
    	$resultSet = $this->getDbTable()->fetchRow("$whereState AND title = '$title'");
    	if ($resultSet != NULL) {
    		return TRUE;
    	} else {
    		return FALSE;
    	}
    }
    
	/**
     * 
     * Verifies if the id and title of the news already exist.
     * @param int $id
     * @param string $title
     * @return boolean
     */
    public function verifyExistIdAndTitle($id, $title) {
    	$whereState = sprintf("%s = 1", self::STATE_FIELDNAME);
    	$resultSet = $this->getDbTable()->fetchRow("$whereState AND id = $id AND  title = '$title'");
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
	public function findAllNames() {
		$whereState = sprintf("%s = 1", self::STATE_FIELDNAME);
    	$resultSet = $this->getDbTable()->fetchAll($whereState);
        $data = array();
        foreach ($resultSet as $row) 
            $data[$row->id] = $row->title;
            
        return $data;
    }
}