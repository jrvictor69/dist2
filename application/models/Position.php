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

class Model_Position extends Model_Entity {
	
	/**
	 * 
	 * @var string
	 */
	protected $_name;
	
	/**
	 * 
	 * @var string
	 */
	protected $_description;
	
	/**
	 * 
	 * @var int
	 */
	protected $_createdBy;
	
	/**
	 * 
	 * @var int
	 */
	protected $_changedBy;
	
    public function __construct(array $options = null) {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }
    
	/**
	 * @return string
	 */
	public function getName() {
		return $this->_name;
	}

	/**
	 * @param string $name
	 * @return Model_Position
	 */
	public function setName($name) {
		$this->_name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->_description;
	}

	/**
	 * @param string $description
	 * @return Model_Position
	 */
	public function setDescription($description) {
		$this->_description = $description;
		return $this;
	}
	
	/**
	 * @return int
	 */
	public function getCreatedBy() {
		return $this->_createdBy;
	}

	/**
	 * @param int $createdBy
	 * @return Model_Position
	 */
	public function setCreatedBy($createdBy) {
		$this->_createdBy = $createdBy;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getChangedBy() {
		return $this->_changedBy;
	}

	/**
	 * @param int $changedBy
	 * @return Model_Position
	 */
	public function setChangedBy($changedBy) {
		$this->_changedBy = $changedBy;
		return $this;
	}
}