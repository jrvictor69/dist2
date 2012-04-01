<?php

class Model_DataVault extends Model_Entity {
	
	/**
	 * 
	 * Name of the file
	 * @var string
	 */
	protected $_filename;
	
	/**
	 * 
	 * Type of extension
	 * @var string
	 */
	protected $_mimeType;
	
	/**
	 * 
	 * Expires 
	 * @var date
	 */
	protected $_expires;
	
	/**
	 * 
	 * Register binary contains all information
	 * @var string
	 */
	protected $_binary;
	
 	public function __construct(array $options = null) {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }
    
	/**
	 * @return string
	 */
	public function getFilename() {
		return $this->_filename;
	}

	/**
	 * @param string $filename
	 * @return Model_DataVault
	 */
	public function setFilename($filename) {
		$this->_filename = $filename;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getMimeType() {
		return $this->_mimeType;
	}

	/**
	 * @param string $mimeType
	 * @return Model_DataVault
	 */
	public function setMimeType($mimeType) {
		$this->_mimeType = $mimeType;
		return $this;
	}

	/**
	 * @return date
	 */
	public function getExpires() {
		return $this->_expires;
	}

	/**
	 * @param date $expires
	 * @return Model_DataVault
	 */
	public function setExpires($expires) {
		$this->_expires = $expires;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getBinary() {
		return $this->_binary;
	}

	/**
	 * @param string $binary
	 * @return Model_DataVault
	 */
	public function setBinary($binary) {
		$this->_binary = $binary;
		return $this;
	}
}