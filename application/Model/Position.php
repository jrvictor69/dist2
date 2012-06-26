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

/**
 * @Entity(repositoryClass="Model\Repositories\PositionRepository")
 * @Table(name="Position")
 */
class Position {
	
	/**
	 * @Id @GeneratedValue @Column(type="integer")
	 * @var int
	 */
	private $id;
	
	/** 
	 * @Column(type="string")
	 * @var string
	 */
	private $name;
	
	/**
	 * @Column(type="text")
	 * @var string
	 */
	private $description;
	
	/**
	 * @Column(type="datetime")
	 * @var datetime
	 */
	private $created;
	
	/**
	 * @Column(type="datetime")
	 * @var datetime
	 */
	private $changed;
	
	/** 
	 * @Column(type="integer")
	 * @var int
	 */
	private $createdBy;
	
	/**
	 * @Column(type="integer")
	 * @var int
	 */
	private $changedBy;
	
	/**
	 * @Column(type="integer")
	 * @var int
	 */
	protected $state;
	
	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param int $id
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->description = $description;
		return $this;
	}

	/**
	 * @return datetime
	 */
	public function getCreated() {
		return $this->created;
	}

	/**
	 * @param datetime $created
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
	 */
	public function setState($state) {
		$this->state = $state;
		return $this;
	}
}