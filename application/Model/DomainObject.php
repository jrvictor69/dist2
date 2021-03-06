<?php
/**
 * Model Doctrine 2 for Dist 2
 *
 * @category Dist
 * @package Models
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2012 Emini A/S
 * @license Proprietary
 */

namespace Model;

class DomainObject {

	/**
	 * 
	 * @Id @Column(type="integer") @GeneratedValue
	 * @var int
	 */
	protected $id;

	/**
	 * 
	 * @Column(type="datetime")
	 * @var datetime
	 */
	protected $created;

	/**
	 * 
	 * @Column(type="datetime")
	 * @var datetime
	 */
	protected $changed;

	/**
	 * 
	 * @Column(type="integer")
	 * @var int
	 */
	protected $createdBy;
	
	/**
	 * 
	 * @Column(type="integer")
	 * @var int
	 */
	protected $changedBy;

	/**
	 * 
	 * @Column(type="integer")
	 * @var int
	 */
	protected $state;

	public function __construct() {
		$this->state = TRUE;
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return datetime
	 */
	public function getCreated() {
		return $this->created;
	}

	/**
	 * @param datetime $created
	 * @return DomainObject
	 */
	public function setCreated($created) {
		$this->created = $created;

		return $this;
	}

	/**
	 * @return datetime
	 */
	public function getChanged() {
		return $this->changed;
	}

	/**
	 * @param datetime $changed
	 * @return DomainObject
	 */
	public function setChanged($changed) {
		$this->changed = $changed;

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
	 * @return DomainObject
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
	 * @return DomainObject
	 */
	public function setChangedBy($changedBy) {
		$this->changedBy = $changedBy;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getState() {
		return $this->state;
	}

	/**
	 * @param int $state
	 * @return DomainObject
	 */
	public function setState($state) {
		$this->state = $state;

		return $this;
	}
}