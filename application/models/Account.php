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

class Model_Account extends Model_Entity {

	const ACCOUNT_TYPE_NONE			  = 100;
	const ACCOUNT_TYPE_ADMIN		  = 1000;
	const ACCOUNT_TYPE_MANAGERIAL	  = 2000;
	const ACCOUNT_TYPE_MEMBER		  = 3000;
	const ACCOUNT_TYPE_GUEST		  = 4000;
	const ACCOUNT_TYPE_CHURCH_CONTACT = 5000;
	
	/**
	 * 
	 * @var string
	 */
    protected $_username;
    
    /**
     * 
     * @var string
     */
    protected $_password;
    
    /**
     * 
     * @var string
     */
    protected $_email;
    
    /**
     * 
     * @var string
     */
	protected $_role;
	
	protected $_accountType;     
	protected $_authenticatorId;
	
  	public function __construct(array $options = null) {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }
	
	/**
	 * @return string
	 */
	public function getUsername() {
		return $this->_username;
	}

	/**
	 * @param string $username
	 * @return Model_Account
	 */
	public function setUsername($username) {
		$this->_username = $username;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPassword() {
		return $this->_password;
	}

	/**
	 * @param string $password
	 * @return Model_Account
	 */
	public function setPassword($password) {
		$this->_password = $password;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getEmail() {
		return $this->_email;
	}

	/**
	 * @param string $email
	 * @return Model_Account
	 */
	public function setEmail($email) {
		$this->_email = $email;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getRole() {
		return $this->_role;
	}

	/**
	 * @param string $role
	 * @return Model_Account
	 */
	public function setRole($role) {
		$this->_role = $role;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getAccountType() {
		return $this->_accountType;
	}

	/**
	 * @param int $accountType
	 * @return Model_Account
	 */
	public function setAccountType($accountType) {
		$this->_accountType = $accountType;
		return $this;
	}

	/**
	 * @return byte
	 */
	public function getAuthenticatorId() {
		return $this->_authenticatorId;
	}

	/**
	 * @param byte $authenticatorId
	 * @return Model_Account
	 */
	public function setAuthenticatorId($authenticatorId) {
		$this->_authenticatorId = $authenticatorId;
		return $this;
	}
}