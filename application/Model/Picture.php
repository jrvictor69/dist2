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
 * @Entity(repositoryClass="Model\Repositories\PictureRepository")
 * @Table(name="Picture")
 */
class Picture extends DomainObject {

	/**
	 * @Column(type="string")
	 * @var string
	 */
	private $title;

	/**
	 * @Column(type="text")
	 * @var string
	 */
	private $description;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	private $filename;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	private $mimeType;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	private $src;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	private $filenameCrop;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	private $mimeTypeCrop;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	private $srcCrop;

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
	 * Id of the PictureType this model is associated with.
	 * @Column(type="integer")
	 * @var int
	 */
	private $pictureTypeId;

	/**
	 * PictureType this model is associated with.
	 * @ManyToOne(targetEntity="PictureType")
	 * @JoinColumn(name="pictureTypeId", referencedColumnName="id")
	 * @var PictureType
	 */
	private $pictureType;

	/**
	 * Id of the PictureCategory this model is associated with.
	 * @Column(type="integer")
	 * @var int
	 */
	private $pictureCategoryId;

	/**
	 * PictureCategory this model is associated with.
	 * @ManyToOne(targetEntity="PictureCategory")
	 * @JoinColumn(name="pictureCategoryId", referencedColumnName="id")
	 * @var PictureCategory
	 */
	private $pictureCategory;

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param string $title
	 * @return Picture
	 */
	public function setTitle($title) {
		$this->title = $title;
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
	 * @return Picture
	 */
	public function setDescription($description) {
		$this->description = $description;
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
	 * @return Picture
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
	 * @return Picture
	 */
	public function setMimeType($mimeType) {
		$this->mimeType = $mimeType;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getSrc() {
		return $this->src;
	}

	/**
	 * @param string $src
	 * @return Picture
	 */
	public function setSrc($src) {
		$this->src = $src;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getFilenameCrop() {
		return $this->filenameCrop;
	}

	/**
	 * @param string $filenameCrop
	 * @return Picture
	 */
	public function setFilenameCrop($filenameCrop) {
		$this->filenameCrop = $filenameCrop;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getMimeTypeCrop() {
		return $this->mimeTypeCrop;
	}

	/**
	 * @param string $mimeTypeCrop
	 * @return Picture
	 */
	public function setMimeTypeCrop($mimeTypeCrop) {
		$this->mimeTypeCrop = $mimeTypeCrop;
		return  $this;
	}

	/**
	 * @return string
	 */
	public function getSrcCrop() {
		return $this->srcCrop;
	}

	/**
	 * @param string $srcCrop
	 * @return Picture
	 */
	public function setSrcCrop($srcCrop) {
		$this->srcCrop = $srcCrop;
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
	 * @return Picture
	 */
	public function setClub($club) {
		$this->club = $club;
		return $this;
	}

	/**
	 * @return PictureType
	 */
	public function getPictureType() {
		return $this->pictureType;
	}

	/**
	 * @param PictureType $pictureType
	 * @return Picture
	 */
	public function setPictureType($pictureType) {
		$this->pictureType = $pictureType;
		return $this;
	}

	/**
	 * @return PictureCategory
	 */
	public function getPictureCategory() {
		return $this->pictureCategory;
	}

	/**
	 * @param PictureCategory $pictureCategory
	 * @return Picture
	 */
	public function setPictureCategory($pictureCategory) {
		$this->pictureCategory = $pictureCategory;
		return $this;
	}
}