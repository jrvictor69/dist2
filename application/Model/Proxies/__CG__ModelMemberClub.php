<?php

namespace Model\Proxies\__CG__\Model;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class MemberClub extends \Model\MemberClub implements \Doctrine\ORM\Proxy\Proxy
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

    
    public function getEmail()
    {
        $this->__load();
        return parent::getEmail();
    }

    public function setEmail($email)
    {
        $this->__load();
        return parent::setEmail($email);
    }

    public function getNote()
    {
        $this->__load();
        return parent::getNote();
    }

    public function setNote($note)
    {
        $this->__load();
        return parent::setNote($note);
    }

    public function getHistory()
    {
        $this->__load();
        return parent::getHistory();
    }

    public function setHistory($history)
    {
        $this->__load();
        return parent::setHistory($history);
    }

    public function getClub()
    {
        $this->__load();
        return parent::getClub();
    }

    public function setClub($club)
    {
        $this->__load();
        return parent::setClub($club);
    }

    public function getIdentityCard()
    {
        $this->__load();
        return parent::getIdentityCard();
    }

    public function setIdentityCard($identityCard)
    {
        $this->__load();
        return parent::setIdentityCard($identityCard);
    }

    public function getFirstName()
    {
        $this->__load();
        return parent::getFirstName();
    }

    public function setFirstName($firstName)
    {
        $this->__load();
        return parent::setFirstName($firstName);
    }

    public function getLastName()
    {
        $this->__load();
        return parent::getLastName();
    }

    public function setLastName($lastName)
    {
        $this->__load();
        return parent::setLastName($lastName);
    }

    public function getDateOfBirth()
    {
        $this->__load();
        return parent::getDateOfBirth();
    }

    public function setDateOfBirth($dateOfBirth)
    {
        $this->__load();
        return parent::setDateOfBirth($dateOfBirth);
    }

    public function getPhone()
    {
        $this->__load();
        return parent::getPhone();
    }

    public function setPhone($phone)
    {
        $this->__load();
        return parent::setPhone($phone);
    }

    public function getPhonework()
    {
        $this->__load();
        return parent::getPhonework();
    }

    public function setPhonework($phonework)
    {
        $this->__load();
        return parent::setPhonework($phonework);
    }

    public function getPhonemobil()
    {
        $this->__load();
        return parent::getPhonemobil();
    }

    public function setPhonemobil($phonemobil)
    {
        $this->__load();
        return parent::setPhonemobil($phonemobil);
    }

    public function getSex()
    {
        $this->__load();
        return parent::getSex();
    }

    public function setSex($sex)
    {
        $this->__load();
        return parent::setSex($sex);
    }

    public function getProfilePictureId()
    {
        $this->__load();
        return parent::getProfilePictureId();
    }

    public function setProfilePictureId($profilePictureId)
    {
        $this->__load();
        return parent::setProfilePictureId($profilePictureId);
    }

    public function getName()
    {
        $this->__load();
        return parent::getName();
    }

    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return (int) $this->_identifier["id"];
        }
        $this->__load();
        return parent::getId();
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

    public function getChanged()
    {
        $this->__load();
        return parent::getChanged();
    }

    public function setChanged($changed)
    {
        $this->__load();
        return parent::setChanged($changed);
    }

    public function getCreatedBy()
    {
        $this->__load();
        return parent::getCreatedBy();
    }

    public function setCreatedBy($createdBy)
    {
        $this->__load();
        return parent::setCreatedBy($createdBy);
    }

    public function getChangedBy()
    {
        $this->__load();
        return parent::getChangedBy();
    }

    public function setChangedBy($changedBy)
    {
        $this->__load();
        return parent::setChangedBy($changedBy);
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
        return array('__isInitialized__', 'identityCard', 'firstName', 'lastName', 'dateOfBirth', 'phone', 'phonework', 'phonemobil', 'sex', 'profilePictureId', 'id', 'created', 'changed', 'createdBy', 'changedBy', 'state', 'email', 'note', 'history', 'clubId', 'club');
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