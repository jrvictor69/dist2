<?php

class Model_Person extends Model_Entity {
	
	/**
	 * 
	 * @var string
	 */
	protected $_name;
	
	/**
	 * 
	 * @var string
	 */
	protected $_firstName;
	
	/**
	 * 
	 * @var string
	 */
	protected $_lastName;
	
	/**
	 * 
	 * @var date
	 */
	protected $_dateOfBirth;
	
	/**
	 * 
	 * @var string
	 */
	protected $_phone;
	
	/**
	 * 
	 * @var string
	 */
	protected $_phonework;
	
	/**
	 * 
	 * @var int
	 */
	protected $_phonemobil;
	
	/**
	 * 
	 * @var byte
	 */
	protected $_sex;
	
	/**
	 * 
	 * @var int
	 */
	protected $_type;
	
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
	 * @return Model_Person
	 */
	public function setName($name) {
		$this->_name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getFirstName() {
		return $this->_firstName;
	}

	/**
	 * @param string $firstName
	 * @return Model_Person
	 */
	public function setFirstName($firstName) {
		$this->_firstName = $firstName;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getLastName() {
		return $this->_lastName;
	}

	/**
	 * @param string $lastName
	 * @return Model_Person
	 */
	public function setLastName($lastName) {
		$this->_lastName = $lastName;
		return $this;
	}

	/**
	 * @return date
	 */
	public function getDateOfBirth() {
		return $this->_dateOfBirth;
	}

	/**
	 * @param date $dateOfBirth
	 * @return Model_Person
	 */
	public function setDateOfBirth($dateOfBirth) {
		$this->_dateOfBirth = $dateOfBirth;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPhone() {
		return $this->_phone;
	}

	/**
	 * @param string $phone
	 * @return Model_Person
	 */
	public function setPhone($phone) {
		$this->_phone = $phone;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPhonework() {
		return $this->_phonework;
	}

	/**
	 * @param string $phonework
	 * @return Model_Person
	 */
	public function setPhonework($phonework) {
		$this->_phonework = $phonework;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getPhonemobil() {
		return $this->_phonemobil;
	}

	/**
	 * @param int $phonemobil
	 * @return Model_Person
	 */
	public function setPhonemobil($phonemobil) {
		$this->_phonemobil = $phonemobil;
		return $this;
	}

	/**
	 * @return byte
	 */
	public function getSex() {
		return $this->_sex;
	}

	/**
	 * @param byte $sex
	 * @return Model_Person
	 */
	public function setSex($sex) {
		$this->_sex = $sex;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getType() {
		return $this->_type;
	}

	/**
	 * @param int $type
	 * @return Model_Person
	 */
	public function setType($type) {
		$this->_type = $type;
		return $this;
	}
}