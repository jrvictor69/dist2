<?php
/**
 * Model for Dist 2
 *
 * @category Dist
 * @package Models
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2012 Emini A/S
 * @license Proprietary
 */

class Model_Church extends Model_Entity {
	
	/**
	 * 
	 * @var string
	 */
	protected $name;

	/**
	 * 
	 * @var string
	 */
	protected $address;

	/**
	 * 
	 * @var string
	 */
	protected $content;
	
	/**
	 * 
	 * @var string
	 */
	protected $phone;
	
	/**
	 * 
	 * @var int
	 */
	protected $createdBy;

	/**
	 * 
	 * @var int
	 */
	protected $changedBy;

	public function __construct(array $options = null) {
		if (is_array($options)) {
			$this->setOptions($options);
		}
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return Model_Church
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getAddress() {
		return $this->address;
	}

	/**
	 * @param string $address
	 * @return Model_Church
	 */
	public function setAddress($address) {
		$this->address = $address;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * @param string $content
	 * @return Model_Church
	 */
	public function setContent($content) {
		$this->content = $content;
	}

	/**
	 * @return string
	 */
	public function getPhone() {
		return $this->phone;
	}

	/**
	 * @param string $phone
	 * @return Model_Church
	 */
	public function setPhone($phone) {
		$this->phone = $phone;
	}

	/**
	 * @return int
	 */
	public function getCreatedBy() {
		return $this->createdBy;
	}

	/**
	 * @param int $createdBy
	 * @return Model_Category
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
	 * @return Model_Category
	 */
	public function setChangedBy($changedBy) {
		$this->changedBy = $changedBy;
		return $this;
	}
}