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

class Model_MemberFile extends Model_Entity {
	
	/**
	 * 
	 * @var string
	 */
	protected $name;
	
	/**
	 * 
	 * @var string
	 */
	protected $note;
	
	/**
	 * 
	 * @var boolean
	 */
	protected $visibleExtWeb;
	
	/**
	 * 
	 * @var int
	 */
	protected $changedBy;
	
	/**
	 * 
	 * Id of the Member this model is associated with.
	 * @var int
	 */
	protected $memberId;
	
	/**
	 * 
	 * Member this model is associated with.
	 * @var Model_Member
	 */
	protected $member;
	
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
	 * Id of the Account this model is associated with.
	 * @var int
	 */
	protected $createdById;
	
	/**
	 * 
	 * Account this model is associated with.
	 * @var Model_Account
	 */
	protected $createdBy;
	
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
	 * @return Model_MemberFile
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getNote() {
		return $this->note;
	}

	/**
	 * @param string $note
	 * @return Model_MemberFile
	 */
	public function setNote($note) {
		$this->note = $note;
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
	 * @return Model_MemberFile
	 */
	public function setChangedBy($changedBy) {
		$this->changedBy = $changedBy;
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
	 * @return Model_MemberFile
	 */
	public function setVisibleExtWeb($visibleExtWeb) {
		$this->visibleExtWeb = $visibleExtWeb;
		return $this;
	}

	/**
	 * @return Model_Member
	 */
	public function getMember() {
		// is not working
		return $this->member;
	}

	/**
	 * @param Model_Member $member
	 * @return Model_MemberFile
	 */
	public function setMember(Model_Member $member) {
		$this->member = $member;
		$this->memberId = $member->getId();
		return $this;
	}

	/**
	 * @return Model_DataVault
	 */
	public function getFile() {
		if (empty($this->file)) {
			$dataVaultMapper = new Model_DataVaultMapper();
			$this->file = $dataVaultMapper->find($this->fileId);
		}
		return $this->file;
	}

	/**
	 * @param Model_DataVault $file
	 * @return Model_MemberFile
	 */
	public function setFile(Model_DataVault $file) {
		$this->file = $file;
		$this->fileId = $file->getId();
		return $this;
	}

	/**
	 * @return Model_Account
	 */
	public function getCreatedBy() {
		if (empty($this->createdBy)) {
			$accountMapper = new Model_AccountMapper();
			$this->createdBy = $accountMapper->find($this->createdById);
		}
		return $this->createdBy;
	}

	/**
	 * @param Model_Account $createdBy
	 * @return Model_MemberFile
	 */
	public function setCreatedBy(Model_Account $createdBy) {
		$this->createdBy = $createdBy;
		$this->createdById = $createdBy->getId();
		return $this;
	}
}