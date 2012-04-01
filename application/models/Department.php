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

class Model_Department extends Model_Entity {
	
	/**
	 * 
	 * Name Department
	 * @var string
	 */
	protected $_name;
	
	/**
	 * 
	 * Description Department
	 * @var string
	 */
	protected $_description;
	
	/**
	 * 
	 * Id of the Country this model is associated with.
	 * @var int
	 */
	protected $_countryId;
	
	/**
	 * 
	 * Country this model is associated with.
	 * @var Model_Country
	 */
	protected $country;
	
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
	 * @return Model_Department
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
	 * @return Model_Department
	 */
	public function setDescription($description) {
		$this->_description = $description;
		return $this;
	}
	
	/**
	 * @return Model_Country
	 */
	public function getCountry() {
		$countryMapper = new Model_CountryMapper();
		return $countryMapper->find($this->_countryId);
	}

	/**
	 * @param Model_Country $country
	 * @return Model_Department
	 */
	public function setCountry(Model_Country $country) {
		$this->country = $country;
		$this->_countryId = $country->getId();
		return $this;
	}
}