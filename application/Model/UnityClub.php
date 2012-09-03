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
 * @Entity(repositoryClass="Model\Repositories\UnityClubRepository")
 * @Table(name="UnityClub")
 */
class UnityClub extends DomainObject {

	/**
	 * @Column(type="string")
	 * @var string
	 */
	private $name;

	/**
	 * @Column(type="text")
	 * @var string
	 */
	private $motto;

	/**
	 * @Column(type="text")
	 * @var string
	 */
	private $description;

	/**
	 * Id of the ClubPathfinder this model is associated with.
	 * @Column(type="integer")
	 * @var int
	 */
	private $clubId;

	/**
	 * ClubPathfinder this model is associated with.
	 * @ManyToOne(targetEntity="ClubPathfinder")
	 * @JoinColumn(name="clubId", referencedColumnName="id")
	 * @var ClubPathfinder
	 */
	private $club;

	/**
	 * @Column(type="integer")
	 * @var int
	 */
	private $logoId;

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return UnityClub
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getMotto() {
		return $this->motto;
	}

	/**
	 * @param string $motto
	 * @return UnityClub
	 */
	public function setMotto($motto) {
		$this->motto = $motto;
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
	 * @return UnityClub
	 */
	public function setDescription($description) {
		$this->description = $description;
		return $this;
	}

	/**
	 * @return ClubPathfinder
	 */
	public function getClub() {
		return $this->club;
	}

	/**
	 * @param ClubPathfinder $club
	 * @return UnityClub
	 */
	public function setClub($club) {
		$this->club = $club;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getLogoId() {
		return $this->logoId;
	}

	/**
	 * @param int $logoId
	 * @return UnityClub
	 */
	public function setLogoId($logoId) {
		$this->logoId = $logoId;
		return $this;
	}
}