<?php

namespace DoctrineORMModule\Proxy\__CG__\Brainworkers\Entity;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Team extends \Brainworkers\Entity\Team implements \Doctrine\ORM\Proxy\Proxy
{
    /**
     * @var \Closure the callback responsible for loading properties in the proxy object. This callback is called with
     *      three parameters, being respectively the proxy object to be initialized, the method that triggered the
     *      initialization process and an array of ordered parameters that were passed to that method.
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setInitializer
     */
    public $__initializer__;

    /**
     * @var \Closure the callback responsible of loading properties that need to be copied in the cloned object
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setCloner
     */
    public $__cloner__;

    /**
     * @var boolean flag indicating if this object was already initialized
     *
     * @see \Doctrine\Common\Persistence\Proxy::__isInitialized
     */
    public $__isInitialized__ = false;

    /**
     * @var array properties to be lazy loaded, with keys being the property
     *            names and values being their default values
     *
     * @see \Doctrine\Common\Persistence\Proxy::__getLazyProperties
     */
    public static $lazyPropertiesDefaults = array();



    /**
     * @param \Closure $initializer
     * @param \Closure $cloner
     */
    public function __construct($initializer = null, $cloner = null)
    {

        $this->__initializer__ = $initializer;
        $this->__cloner__      = $cloner;
    }







    /**
     * 
     * @return array
     */
    public function __sleep()
    {
        if ($this->__isInitialized__) {
            return array('__isInitialized__', 'id', 'name', 'makId', 'trainer', 'trainerEmail', 'contacts', 'contactEmail', 'organization', 'whence', 'ip', 'owner', 'city', 'region', 'country', 'created', 'updated', 'answers');
        }

        return array('__isInitialized__', 'id', 'name', 'makId', 'trainer', 'trainerEmail', 'contacts', 'contactEmail', 'organization', 'whence', 'ip', 'owner', 'city', 'region', 'country', 'created', 'updated', 'answers');
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Team $proxy) {
                $proxy->__setInitializer(null);
                $proxy->__setCloner(null);

                $existingProperties = get_object_vars($proxy);

                foreach ($proxy->__getLazyProperties() as $property => $defaultValue) {
                    if ( ! array_key_exists($property, $existingProperties)) {
                        $proxy->$property = $defaultValue;
                    }
                }
            };

        }
    }

    /**
     * 
     */
    public function __clone()
    {
        $this->__cloner__ && $this->__cloner__->__invoke($this, '__clone', array());
    }

    /**
     * Forces initialization of the proxy
     */
    public function __load()
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__load', array());
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitialized($initialized)
    {
        $this->__isInitialized__ = $initialized;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitializer(\Closure $initializer = null)
    {
        $this->__initializer__ = $initializer;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __getInitializer()
    {
        return $this->__initializer__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setCloner(\Closure $cloner = null)
    {
        $this->__cloner__ = $cloner;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific cloning logic
     */
    public function __getCloner()
    {
        return $this->__cloner__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     * @static
     */
    public function __getLazyProperties()
    {
        return self::$lazyPropertiesDefaults;
    }

    
    /**
     * {@inheritDoc}
     */
    public function setAnswers($answers)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setAnswers', array($answers));

        return parent::setAnswers($answers);
    }

    /**
     * {@inheritDoc}
     */
    public function getAnswers()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAnswers', array());

        return parent::getAnswers();
    }

    /**
     * {@inheritDoc}
     */
    public function addQuestions($questions)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addQuestions', array($questions));

        return parent::addQuestions($questions);
    }

    /**
     * {@inheritDoc}
     */
    public function removeQuestions($questions)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeQuestions', array($questions));

        return parent::removeQuestions($questions);
    }

    /**
     * {@inheritDoc}
     */
    public function setId($id)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setId', array($id));

        return parent::setId($id);
    }

    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return (int)  parent::getId();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getId', array());

        return parent::getId();
    }

    /**
     * {@inheritDoc}
     */
    public function setName($name)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setName', array($name));

        return parent::setName($name);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getName', array());

        return parent::getName();
    }

    /**
     * {@inheritDoc}
     */
    public function setQuestions($questions)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setQuestions', array($questions));

        return parent::setQuestions($questions);
    }

    /**
     * {@inheritDoc}
     */
    public function getQuestions()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getQuestions', array());

        return parent::getQuestions();
    }

    /**
     * {@inheritDoc}
     */
    public function setCity($city)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCity', array($city));

        return parent::setCity($city);
    }

    /**
     * {@inheritDoc}
     */
    public function getCity()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCity', array());

        return parent::getCity();
    }

    /**
     * {@inheritDoc}
     */
    public function setContactEmail($contactEmail)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setContactEmail', array($contactEmail));

        return parent::setContactEmail($contactEmail);
    }

    /**
     * {@inheritDoc}
     */
    public function getContactEmail()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getContactEmail', array());

        return parent::getContactEmail();
    }

    /**
     * {@inheritDoc}
     */
    public function setContacts($contacts)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setContacts', array($contacts));

        return parent::setContacts($contacts);
    }

    /**
     * {@inheritDoc}
     */
    public function getContacts()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getContacts', array());

        return parent::getContacts();
    }

    /**
     * {@inheritDoc}
     */
    public function setCountry($country)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCountry', array($country));

        return parent::setCountry($country);
    }

    /**
     * {@inheritDoc}
     */
    public function getCountry()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCountry', array());

        return parent::getCountry();
    }

    /**
     * {@inheritDoc}
     */
    public function setCreated($created)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCreated', array($created));

        return parent::setCreated($created);
    }

    /**
     * {@inheritDoc}
     */
    public function getCreated()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCreated', array());

        return parent::getCreated();
    }

    /**
     * {@inheritDoc}
     */
    public function setIp($ip)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setIp', array($ip));

        return parent::setIp($ip);
    }

    /**
     * {@inheritDoc}
     */
    public function getIp()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getIp', array());

        return parent::getIp();
    }

    /**
     * {@inheritDoc}
     */
    public function setMakId($makId)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMakId', array($makId));

        return parent::setMakId($makId);
    }

    /**
     * {@inheritDoc}
     */
    public function getMakId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMakId', array());

        return parent::getMakId();
    }

    /**
     * {@inheritDoc}
     */
    public function setOrganization($organization)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setOrganization', array($organization));

        return parent::setOrganization($organization);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrganization()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getOrganization', array());

        return parent::getOrganization();
    }

    /**
     * {@inheritDoc}
     */
    public function setOwner($owner)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setOwner', array($owner));

        return parent::setOwner($owner);
    }

    /**
     * {@inheritDoc}
     */
    public function getOwner()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getOwner', array());

        return parent::getOwner();
    }

    /**
     * {@inheritDoc}
     */
    public function setRegion($region)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setRegion', array($region));

        return parent::setRegion($region);
    }

    /**
     * {@inheritDoc}
     */
    public function getRegion()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRegion', array());

        return parent::getRegion();
    }

    /**
     * {@inheritDoc}
     */
    public function setTrainer($trainer)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTrainer', array($trainer));

        return parent::setTrainer($trainer);
    }

    /**
     * {@inheritDoc}
     */
    public function getTrainer()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTrainer', array());

        return parent::getTrainer();
    }

    /**
     * {@inheritDoc}
     */
    public function setTrainerEmail($trainerEmail)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTrainerEmail', array($trainerEmail));

        return parent::setTrainerEmail($trainerEmail);
    }

    /**
     * {@inheritDoc}
     */
    public function getTrainerEmail()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTrainerEmail', array());

        return parent::getTrainerEmail();
    }

    /**
     * {@inheritDoc}
     */
    public function setUpdated($updated)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setUpdated', array($updated));

        return parent::setUpdated($updated);
    }

    /**
     * {@inheritDoc}
     */
    public function getUpdated()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUpdated', array());

        return parent::getUpdated();
    }

    /**
     * {@inheritDoc}
     */
    public function setWhence($whence)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setWhence', array($whence));

        return parent::setWhence($whence);
    }

    /**
     * {@inheritDoc}
     */
    public function getWhence()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getWhence', array());

        return parent::getWhence();
    }

}
