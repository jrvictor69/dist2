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

class Model_Picture extends Model_Entity {
	
	/**
	 * 
	 * @var string
	 */
	private $title;
	
	/**
	 * 
	 * @var string
	 */
	private $description;
	
	/**
	 * 
	 * @var string
	 */
	private $src;
	
	/**
	 * 
	 * @var string
	 */
	private $srcCrops;
	
	/**
	 * 
	 * @var boolean
	 */
	protected $visibleExtWeb;
		
	/**
	 * 
	 * @var int
	 */
	private $createdBy;
	
	/**
	 * 
	 * @var int
	 */
	private $changedBy;
	
	/**
	 * 
	 * Id of the DataVault this model is associated with.
	 * @var int
	 */
	protected $fileId;
	
	/**
	 * 
	 * DataVault this model is associated with.
	 * @var Model_DataVault
	 */
	protected $file;
	
	/**
	 * 
	 * Id of the Category this model is associated with.
	 * @var int
	 */
	private $categoryId;
	
	/**
	 * 
	 * Category this model is associated with.
	 * @var Model_Category
	 */
	private $category;
	
	public function __construct(array $options = null) {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }
    
	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param string $title
	 * @return Model_Picture;
	 */
	public function setTitle($title) {
		$this->title = $title;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param string $description
	 * @return Model_Picture
	 */
	public function setDescription($description) {
		$this->description = $description;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getSrc() {
		return $this->src;
	}

	/**
	 * @param string $src
	 * @return Model_Picture
	 */
	public function setSrc($src) {
		$this->src = $src;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getSrcCrops() {
		return $this->srcCrops;
	}

	/**
	 * @param string $srcCrops
	 * @return Model_Picture
	 */
	public function setSrcCrops($srcCrops) {
		$this->srcCrops = $srcCrops;
		return $this;
	}
	
	/**
	 * @return boolean
	 */
	public function getVisibleExtWeb() {
		return $this->visibleExtWeb;
	}

	/**
	 * @param boolean $visibleExtWeb
	 * @return Model_Picture
	 */
	public function setVisibleExtWeb($visibleExtWeb) {
		$this->visibleExtWeb = $visibleExtWeb;
		return $this;
	}
	
	/**
	 * @return int
	 */
	public function getCreatedBy() {
		return $this->createdBy;
	}

	/**
	 * @param int $createdBy
	 * @return Model_Picture
	 */
	public function setCreatedBy($createdBy) {
		$this->createdBy = $createdBy;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getChangedBy() {
		return $this->changedBy;
	}

	/**
	 * @param int $changedBy
	 * @return Model_Picture
	 */
	public function setChangedBy($changedBy) {
		$this->changedBy = $changedBy;
		return $this;
	}
	
	/**
	 * @return Model_ImageDataVault
	 */
	public function getFile() {
		if (empty($this->file)) {
			$dataVaultMapper = new Model_ImageDataVaultMapper();
			$this->file = $dataVaultMapper->find($this->fileId);
		}
		return $this->file;
	}

	/**
	 * @param Model_ImageDataVault $file
	 * @return Model_Picture
	 */
	public function setFile(Model_ImageDataVault $file) {
		$this->file = $file;
		$this->fileId = $file->getId();
		return $this;
	}
	
	/**
	 * @return Model_Category
	 */
	public function getCategory() {
		$categoryMapper = new Model_CategoryMapper();
		return $categoryMapper->find($this->categoryId);
	}

	/**
	 * @param Model_Category $category
	 * @return Model_Picture
	 */
	public function setCategory(Model_Category $category) {
		$this->category = $category;
		$this->categoryId = $category->getId();
		return $this;
	}
}