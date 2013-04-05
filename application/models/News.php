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

class Model_News extends Model_Entity {

	/**
	 * @var string
	 */
	private $title;

	/**
	 * @var string
	 */
	private $summary;

	/**
	 * @var string
	 */
	private $contain;

	/**
	 * @var string
	 */
	private $fount;

	/**
	 * @var string
	 */
	private $imagename;

	/**
	 * @var date
	 */
	private $newsdate;

	/**
	 * @var int
	 */
	private $createdBy;

	/**
	 * @var int
	 */
	private $changedBy;

	/**
	 * Id of the Category this model is associated with.
	 * @var int
	 */
	private $categoryId;

	/**
	 * Category this model is associated with.
	 * @var Model_Category
	 */
	private $category;

	/**
	 * Id of the Managerial this model is associated with.
	 * @var int
	 */
	private $managerialId;

	/**
	 * Managerial this model is associated with.
	 * @var Model_Managerial
	 */
	private $managerial;

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
	 * @return Model_News;
	 */
	public function setTitle($title) {
		$this->title = $title;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getSummary() {
		return $this->summary;
	}

	/**
	 * @param string $summary
	 * @return Model_News
	 */
	public function setSummary($summary) {
		$this->summary = $summary;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getContain() {
		return $this->contain;
	}

	/**
	 * @param string $contain
	 * @return Model_News
	 */
	public function setContain($contain) {
		$this->contain = $contain;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getFount() {
		return $this->fount;
	}

	/**
	 * @param string $fount
	 * @return Model_News
	 */
	public function setFount($fount) {
		$this->fount = $fount;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getImagename() {
		return $this->imagename;
	}

	/**
	 * @param string $imagename
	 * @return Model_News
	 *
	 */
	public function setImagename($imagename) {
		$this->imagename = $imagename;
		return $this;
	}

	/**
	 * @return date
	 */
	public function getNewsdate() {
		return $this->newsdate;
	}

	/**
	 * @param date $newsdate
	 * @return Model_News
	 */
	public function setNewsdate($newsdate) {
		$this->newsdate = $newsdate;
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
	 * @return Model_News
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
	 * @return Model_News
	 */
	public function setChangedBy($changedBy) {
		$this->changedBy = $changedBy;
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
	 * @return Model_Category
	 */
	public function setCategory(Model_Category $category) {
		$this->category = $category;
		$this->categoryId = $category->getId();
		return $this;
	}

	/**
	 * @return Model_Managerial
	 */
	public function getManagerial() {
		$managerialMapper = new Model_ManagerialMapper();
		return $managerialMapper->find($this->categoryId);
	}

	/**
	 * @param Model_Category $category
	 * @return Model_Category
	 */
	public function setManagerial(Model_Managerial $managerial) {
		$this->managerial = $managerial;
		$this->managerialId = $managerial->getId();
		return $this;
	}
}