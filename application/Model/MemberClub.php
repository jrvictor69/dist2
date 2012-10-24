<?php

namespace Model;

/**
 * @Entity(repositoryClass="Model\Repositories\MemberClubRepository")
 * @Table(name="MemberClub")
 */
class MemberClub extends Person {

	/**
	 * @Column(type="string")
	 * @var string
	 */
	private $email;

	/**
	 * @Column(type="text")
	 * @var string,
	 */
	private $note;

	/**
	 * @Column(type="text")
	 * @var string
	 */
	private $history;

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
	 * @return MemberClub
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
	 * @return MemberClub
	 */
	public function setNote($note) {
		$this->note = $note;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getHistory() {
		return $this->history;
	}

	/**
	 * @param string $history
	 * @return MemberClub
	 */
	public function setHistory($history) {
		$this->history = $history;
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
	 * @return MemberClub
	 */
	public function setClub($club) {
		$this->club = $club;
		return $this;
	}
}