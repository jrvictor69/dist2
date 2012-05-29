<?php
/**
 * Model for Dist 2
 *
 * @category Dist
 * @package Models
 * @author Victor Villca <victor.villca@people-t.com>
 * @copyright Copyright (c) 2012 Emini A/S
 * @license Proprietary
 */

class Model_PictureMapper extends Model_TemporalMapper {

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
            $this->setDbTable('Model_DbTable_Picture');
        }
        return $this->_dbTable;
    }
    
	/**
     * 
     * Saves model
     * @param Model_Picture $picture
     */
	public function save(Model_Picture $picture) {
		$file = $picture->getFile();
		$dataVaultMapper = new Model_ImageDataVaultMapper();
		$dataVaultMapper->save($file);
		
		$data = array(
			'title' => $picture->getTitle(),
            'description' => $picture->getDescription(),
			'src' => $picture->getSrc(),
			'srcCrops' => $picture->getSrcCrops(),
            'created' => date('Y-m-d H:i:s'),
			'createdBy' => $picture->getCreatedBy(),
//			'categoryId' => $picture->getCategory()->getId(),
			'fileId' => $file->getId(),
			'categoryId' => 35,
        	self::STATE_FIELDNAME => TRUE
        );
        
		unset($data['id']);
		$this->getDbTable()->insert($data);
    }
    
	/**
     * 
     * Updates model
     * @param int $id
     * @param Model_Picture $picture
     */
	public function update($id, Model_Picture $picture) {
        $result = $this->getDbTable()->find($id); 
        if (0 == count($result)) {
            return;
        }
        
		$file = $picture->getFile();
		$dataVaultMapper = new Model_ImageDataVaultMapper();
		$dataVaultMapper->save($file);
		
	 	$data = array(
			'title' => $picture->getTitle(),
			'description' => $picture->getDescription(),
			'src' => $picture->getSrc(),
			'srcCrops' => $picture->getSrcCrops(),
			'changed' => date('Y-m-d H:i:s'),
			'changedBy' => $picture->getChangedBy(),
			'categoryId' => $picture->getCategory()->getId()
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
		                
        $dataVaultMapper = new Model_ImageDataVaultMapper();
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
     * @return Model_Picture
     */
	public function find($id) {
        $result = $this->getDbTable()->find($id);
    	if (0 == count($result)) {
            return NULL;
        }
        
        $row = $result->current();
        
        $dataVaultMapper = new Model_ImageDataVaultMapper();
        $file = $dataVaultMapper->find($row->fileId);
        
        $categoryMapper = new Model_CategoryMapper();
        $category = $categoryMapper->find($row->categoryId);
        
		$picture = new Model_Picture();
        $picture->setTitle($row->title)
			->setDescription($row->description)
			->setSrc($row->src)
			->setSrcCrops($row->srcCrops)
			->setVisibleExtWeb($row->visibleExtWeb)
			->setCreated($row->created)
			->setChanged($row->changed)
			->setCreatedBy($row->createdBy)
			->setChangedBy($row->changedBy)
			->setFile($file)
			->setCategory($category)
			->setId($row->id);
		
   		return $picture;
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
			$dataVaultMapper = new Model_ImageDataVaultMapper();
			$file = $dataVaultMapper->find($row->fileId, FALSE);
			
        	$categoryMapper = new Model_CategoryMapper();
        	$category = $categoryMapper->find($row->categoryId);
        	
        	$entry = new Model_Picture();
        	$entry->setTitle($row->title)
        		->setDescription($row->description)
				->setSrc($row->src)
				->setSrcCrops($row->srcCrops)
				->setCreated($row->created)
				->setChanged($row->changed)
				->setCreatedBy($row->createdBy)
				->setChangedBy($row->changedBy)
				->setFile($file)
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
				$order = 'description';
				break;
				
			case 3:
				$order = 'fileId';
				break;
			
			case 4:
				$order = 'categoryId';
				break;
				
			case 5:
				$order = 'created';
				break;
				
			case 6:
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
        	$dataVaultMapper = new Model_ImageDataVaultMapper();
			$file = $dataVaultMapper->find($row->fileId, FALSE);
			
        	$categoryMapper = new Model_CategoryMapper();
        	$category = $categoryMapper->find($row->categoryId);

        	$entry = new Model_Picture();
        	$entry->setTitle($row->title)
        		->setDescription($row->description)
				->setSrc($row->src)
				->setSrcCrops($row->srcCrops)
				->setCreated($row->created)
				->setChanged($row->changed)
				->setCreatedBy($row->createdBy)
				->setChangedBy($row->changedBy)
				->setFile($file)
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
     * Verifies if the title picture already exist it.
     * @param string $title
     * @return boolean
     */
	public function verifyExistTitle($title) {
    	$whereState = sprintf("%s = 1", self::STATE_FIELDNAME);
    	$resultSet = $this->getDbTable()->fetchRow("$whereState AND title = '$title'");
    	return $resultSet != NULL? TRUE : FALSE;
    }
    
	/**
     * 
     * Verifies if the id and title of the picture already exist.
     * @param int $id
     * @param string $title
     * @return boolean
     */
    public function verifyExistIdAndTitle($id, $title) {
    	$whereState = sprintf("%s = 1", self::STATE_FIELDNAME);
    	$resultSet = $this->getDbTable()->fetchRow("$whereState AND id = $id AND  title = '$title'");
    	return $resultSet != NULL? TRUE : FALSE;
    }
    
	/**
     * 
     * Finds the titles of the models
     * @return array
     */
	public function findAllTitles() {
		$whereState = sprintf("%s = 1", self::STATE_FIELDNAME);
		$resultSet = $this->getDbTable()->fetchAll($whereState);
		$data = array();
		foreach ($resultSet as $row) { 
			$data[$row->id] = $row->title;
		}
		
		return $data;
	}
}