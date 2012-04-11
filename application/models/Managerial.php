<?php

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
		$accountMapper->find($this->_accountId, $this->account);
		return $this->account;
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
		$provinceMapper->find($this->_provinceId, $this->province);
		return $this->province;
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
}