<?php
/**
 * Model for Dist 2.
 *
 * @category Dist
 * @package Models
 * @author Victor Villca <victor.villca@people-t.com>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class Model_ImageDataVault extends Model_Entity {
	
	/**
	 * 
	 * @var string
	 */
	protected $filename;
	
	/**
	 * 
	 * @var string
	 */
	protected $mimeType;
	
	/**
	 *  
	 * @var date
	 */
	protected $expires;
	
	/**
	 * 
	 * This contains the content of the file
	 * @var string
	 */
	protected $binary;
	
 	public function __construct(array $options = null) {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }
    
	/**
	 * @return string
	 */
	public function getFilename() {
		return $this->filename;
	}

	/**
	 * @param string $filename
	 * @return Model_ImageDataVault
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
	 * @return Model_ImageDataVault
	 */
	public function setMimeType($mimeType) {
		$this->mimeType = $mimeType;
		return $this;
	}

	/**
	 * @return date
	 */
	public function getExpires() {
		return $this->expires;
	}

	/**
	 * @param date $expires
	 * @return Model_ImageDataVault
	 */
	public function setExpires($expires) {
		$this->expires = $expires;
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
	 * @return Model_ImageDataVault
	 */
	public function setBinary($binary) {
		$this->binary = $binary;
		return $this;
	}
}