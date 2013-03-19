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
 * @Entity(repositoryClass="Model\Repositories\ImageDataVaultRepository")
 * @Table(name="ImageDataVault")
 */
class ImageDataVault {

	/**
	 * 
	 * @Id @Column(type="integer") @GeneratedValue
	 * @var int
	 */
	private $id;

	/**
	 * 
	 * @Column(type="datetime")
	 * @var datetime
	 */
	private $expires;

	/**
	 * 
	 * @Column(type="string")
	 * @var string
	 */
	private $filename;

	/**
	 * 
	 * @Column(type="string")
	 * @var string
	 */
	private $mimeType;

	/**
	 * 
	 * Column(type="blob")
	 * @var string
	 */
	private $binary;

	/**
	 * 
	 * @Column(type="datetime")
	 * @var datetime
	 */
	private $created;

	/**
	 * 
	 * @Column(type="boolean")
	 * @var boolean
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
	public function getExpires() {
		return $this->expires;
	}

	/**
	 * @param datetime $expires
	 * @return ImageDataVault
	 */
	public function setExpires($expires) {
		$this->expires = $expires;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getFilename() {
		return $this->filename;
	}

	/**
	 * @param string $filename
	 * @return ImageDataVault
	 */
	public function setFilename($filename) {
		$this->filename = $filename;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getMimeType() {
		return $this->mimeType;
	}

	/**
	 * @param string $mimeType
	 * @return ImageDataVault
	 */
	public function setMimeType($mimeType) {
		$this->mimeType = $mimeType;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getBinary() {
		return $this->binary;
	}

	/**
	 * @param string $binary
	 * @return ImageDataVault
	 */
	public function setBinary($binary) {
		$this->binary = $binary;

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
	 * @return ImageDataVault
	 */
	public function setCreated($created) {
		$this->created = $created;

		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getState() {
		return $this->state;
	}

	/**
	 * @param boolean $state
	 * @return ImageDataVault
	 */
	public function setState($state) {
		$this->state = $state;

		return $this;
	}
}