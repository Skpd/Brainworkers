<?php

namespace Brainworkers\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation as Form;

/**
 * @ORM\Entity
 * @ORM\Table(name="payment")
 * @Form\Name("payment")
 * @ORM\HasLifecycleCallbacks
 */
class Payment
{
    #region Fields
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Form\Attributes({"type":"hidden"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true, unique=false)
     * @var int
     */
    private $amount;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, unique=false)
     * @var string
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, unique=false)
     * @var string
     */
    private $alias;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, unique=false)
     * @var string
     */
    private $baggage;

    /**
     * @ORM\Column(type="boolean", nullable=true, unique=false)
     * @var boolean
     */
    private $state;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, unique=false)
     * @var string
     */
    private $transactionId;

    /**
     * @ORM\Column(type="float", nullable=true, unique=false)
     * @var string
     */
    private $currencyRate;

    /**
     * @ORM\Column(type="integer", nullable=true, unique=false)
     * @var int
     */
    private $feesType;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, unique=false)
     * @var string
     */
    private $sign;

    /**
     * @var \User\Entity\User
     * @ORM\ManyToOne(targetEntity="\User\Entity\User")
     */
    private $payer;

    /**
     * @ORM\Column(type="datetime")
     * @Form\Exclude()
     * @var \DateTime
     */
    private $created;

    /**
     * @ORM\Column(type="datetime")
     * @Form\Exclude()
     * @var \DateTime
     */
    private $updated;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Form\Exclude()
     * @var \DateTime
     */
    private $timestamp;
    #endregion

    /** @ORM\PrePersist */
    public function onPrePersist()
    {
        $this->created = new \DateTime();
        $this->updated = new \DateTime();
    }

    /** @ORM\PreUpdate */
    public function onPreUpdate()
    {
        $this->updated = new \DateTime();
    }

    #region Getters / Setters
    /**
     * @param string $alias
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param int $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param string $baggage
     */
    public function setBaggage($baggage)
    {
        $this->baggage = $baggage;
    }

    /**
     * @return string
     */
    public function getBaggage()
    {
        return $this->baggage;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param string $currencyRate
     */
    public function setCurrencyRate($currencyRate)
    {
        $this->currencyRate = $currencyRate;
    }

    /**
     * @return string
     */
    public function getCurrencyRate()
    {
        return $this->currencyRate;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param int $feesType
     */
    public function setFeesType($feesType)
    {
        $this->feesType = $feesType;
    }

    /**
     * @return int
     */
    public function getFeesType()
    {
        return $this->feesType;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function setPayer($payer)
    {
        $this->payer = $payer;
    }

    public function getPayer()
    {
        return $this->payer;
    }

    /**
     * @param string $sign
     */
    public function setSign($sign)
    {
        $this->sign = $sign;
    }

    /**
     * @return string
     */
    public function getSign()
    {
        return $this->sign;
    }

    /**
     * @param boolean $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return boolean
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param \DateTime $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @return \DateTime
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param string $transactionId
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;
    }

    /**
     * @return string
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * @param \DateTime $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }
    #endregion
}