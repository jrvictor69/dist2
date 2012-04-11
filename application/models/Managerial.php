<?php
/**
 * Model for Dist 2.
 *
 * @category Dist
 * @package Models
 * @author Victor Villca <victor.villca@swissbytes.ch>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class Model_Managerial extends Model_Person {
	
	/**
	 * 
	 * it is visible for administration
	 * @var boolean
	 */
	protected $_visible;
	
	/**
	 * 
	 * Id of the Person this model is associated with.
	 * @var int
	 */
	protected $_personId;

	/**
	 * 
	 * Id of the Account this model is associated with.
	 * @var int
	 */
	protected $_accountId;
	
	/**
	 * 
	 * Account this model is associated with.
	 * @var Model_Account
	 */
	protected $account;
	
	/**
	 * 
	 * Id of the Province this model is associated with.
	 * @var int
	 */
	protected $_provinceId;
	
	/**
	 * 
	 * Province this model is associated with.
	 * @var Model_Province
	 */
	protected $province;
	
	/**
	 * 
	 * Id of the User Group this model is associated with.
	 * @var int
	 */
	protected $_userGroupId;
	
	/**
	 * 
	 * User group this model is associated with.
	 * @var Model_UserGroup
	 */
	protected $userGroup;
	
	public function __construct(array $options = null) {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }
    
	/**
	 * @return boolean
	 */
	public function getVisible() {
		return $this->_visible;
	}

	/**
	 * @param boolean $visible
	 * @return Model_Managerial
	 */
	public function setVisible($visible) {
		$this->_visible = $visible;
		return $this;
	}
	
	/**
	 * @return Model_Account
	 */
	public function getAccount() {
		$accountMapper = new Model_AccountMapper();
		return $accountMapper->find($this->_accountId);
	}
	
	/**
	 * @param Model_Account $account
	 * @return Model_Managerial
	 */
	public function setAccount(Model_Account $account) {
		$this->account = $account;
		$this->_accountId = $account->getId();
		return $this;
	}

	/**
	 * @return Model_Province
	 */
	public function getProvince() {
		$provinceMapper = new Model_ProvinceMapper();
		return $provinceMapper->find($this->_provinceId);
	}

	/**
	 * @param Model_Province $province
	 * @return Model_Managerial
	 */
	public function setProvince(Model_Province $province) {
		$this->province = $province;
		$this->_provinceId = $province->getId();
		return $this;
	}
	
	/**
	 * @return Model_UserGroup;
	 */
	public function getUserGroup() {
		$userGroupMapper = new Model_UserGroupMapper();
		return $userGroupMapper->find($this->_userGroupId);
	}

	/**
	 * @param Model_UserGroup $userGroup
	 * @return Model_Managerial
	 */
	public function setUserGroup(Model_UserGroup $userGroup) {
		$this->userGroup = $userGroup;
		$this->_userGroupId = $userGroup->getId();
		return $this;
	}
}