<?php

namespace Model;

/**
 * @Entity(repositoryClass="Model\Repositories\DirectiveRepository")
 * @Table(name="Directive")
 */
class Directive extends Person {

	/**
	 * @Column(type="string")
	 * @var string
	 */
	private $email;

	/**
	 * @Column(type="text")
	 * @var string
	 */
	private $note;

	/**
	 * Id of the Position this model is associated with.
	 * @Column(type="integer")
	 * @var int
	 */
	private $positionId;

	/**
	 * Position this model is associated with.
	 * @ManyToOne(targetEntity="Position")
	 * @JoinColumn(name="positionId", referencedColumnName="id")
	 * @var Position
	 */
	private $position;

	/**
	 * Id of the ClubPathfinder this model is associated with.
	 * @Column(type="integer")
	 * @var int
	 */
	private $clubId;

	/**
	 * Pathfinder this model is associated with.
	 * @ManyToOne(targetEntity="ClubPathfinder")
	 * @JoinColumn(name="clubId", referencedColumnName="id")
	 * @var ClubPathfinder
	 */
	private $club;

	/**
	 * @return string
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * @param string $email
	 * @return Directive
	 */
	public function setEmail($email) {
		$this->email = $email;
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
	 * @return Directive
	 */
	public function setNote($note) {
		$this->note = $note;
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
	 * @return Directive;
	 */
	public function setClub($club) {
		$this->club = $club;
		return $this;
	}

	/**
	 * @return Position
	 */
	public function getPosition() {
		return $this->position;
	}

	/**
	 * @param Position $position
	 * @return Directive
	 */
	public function setPosition($position) {
		$this->position = $position;
		return $this;
	}
}