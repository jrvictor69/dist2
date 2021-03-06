<?php

namespace Model\Proxies\__CG__\Model;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class ImageDataVault extends \Model\ImageDataVault implements \Doctrine\ORM\Proxy\Proxy
{
    private $_entityPersister;
    private $_identifier;
    public $__isInitialized__ = false;
    public function __construct($entityPersister, $identifier)
    {
        $this->_entityPersister = $entityPersister;
        $this->_identifier = $identifier;
    }
    /** @private */
    public function __load()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;

            if (method_exists($this, "__wakeup")) {
                // call this after __isInitialized__to avoid infinite recursion
                // but before loading to emulate what ClassMetadata::newInstance()
                // provides.
                $this->__wakeup();
            }

            if ($this->_entityPersister->load($this->_identifier, $this) === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            unset($this->_entityPersister, $this->_identifier);
        }
    }

    /** @private */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    
    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return (int) $this->_identifier["id"];
        }
        $this->__load();
        return parent::getId();
    }

    public function getExpires()
    {
        $this->__load();
        return parent::getExpires();
    }

    public function setExpires($expires)
    {
        $this->__load();
        return parent::setExpires($expires);
    }

    public function getFilename()
    {
        $this->__load();
        return parent::getFilename();
    }

    public function setFilename($filename)
    {
        $this->__load();
        return parent::setFilename($filename);
    }

    public function getMimeType()
    {
        $this->__load();
        return parent::getMimeType();
    }

    public function setMimeType($mimeType)
    {
        $this->__load();
        return parent::setMimeType($mimeType);
    }

    public function getBinary()
    {
        $this->__load();
        return parent::getBinary();
    }

    public function setBinary($binary)
    {
        $this->__load();
        return parent::setBinary($binary);
    }

    public function getCreated()
    {
        $this->__load();
        return parent::getCreated();
    }

    public function setCreated($created)
    {
        $this->__load();
        return parent::setCreated($created);
    }

    public function getState()
    {
        $this->__load();
        return parent::getState();
    }

    public function setState($state)
    {
        $this->__load();
        return parent::setState($state);
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'expires', 'filename', 'mimeType', 'created', 'state');
    }

    public function __clone()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            $class = $this->_entityPersister->getClassMetadata();
            $original = $this->_entityPersister->load($this->_identifier);
            if ($original === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            foreach ($class->reflFields AS $field => $reflProperty) {
                $reflProperty->setValue($this, $reflProperty->getValue($original));
            }
            unset($this->_entityPersister, $this->_identifier);
        }
        
    }
}