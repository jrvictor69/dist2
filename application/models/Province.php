<?php
/**
 * Model for Dist 2
 * 
 * @category Dist
 * @package Models
 * @author Victor Villca <victor.villca@swissbytes.ch>
 * @copyright Copyright (c) 2012 GISOFT A/S
 * @license Proprietary
 */

class Model_Province extends Model_Entity {
	
	/**
	 * 
	 * Name province
	 * @var string
	 */
	protected $_name;
	
	/**
	 * 
	 * Description province
	 * @var string
	 */
	protected $_description;
	
	/**
	 * 
	 * Id of the Department this model is associated with.
	 * @var int
	 */
	protected $_departmentId;
	
	/**
	 * 
	 * Department this model is associated with.
	 * @var Model_Department
	 */
	protected $department;
	
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
	 * @return Model_Province
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
	 * @return Model_Province
	 */
	public function setDescription($description) {
		$this->_description = $description;
		return $this;
	}
	
	/**
	 * @return Model_Department
	 */
	public function getDepartment() {
		$departmentMapper = new Model_DepartmentMapper();
		return $departmentMapper->find($this->_departmentId);
	}

	/**
	 * @param Model_Department $department
	 * @return Model_Province
	 */
	public function setDepartment(Model_Department $department) {
		$this->department = $department;
		$this->_departmentId = $department->getId();
		return $this;
	}
}