<?php

namespace Brainworkers\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation as Form;

/**
 * @ORM\Entity
 * @ORM\Table(name="payment_log")
 * @Form\Name("payment_log")
 * @ORM\HasLifecycleCallbacks
 */
class PaymentLog
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Form\Attributes({"type":"hidden"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=7, nullable=false, unique=false)
     * @var
     */
    private $type;

    /**
     * @ORM\Column(type="text", nullable=false, unique=false)
     * @var
     */
    private $params;

    /**
     * @ORM\Column(type="datetime")
     * @Form\Exclude()
     * @var \DateTime
     */
    private $created;

    /**
     * @ORM\ManyToOne(targetEntity="Brainworkers\Entity\Payment")
     * @var \Brainworkers\Entity\Payment
     */
    private $payment;

    /** @ORM\PrePersist */
    public function onPrePersist()
    {
        $this->created = new \DateTime();
        $this->updated = new \DateTime();
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

    /**
     * @param  $payment
     */
    public function setPayment($payment)
    {
        $this->payment = $payment;
    }

    /**
     * @return \Brainworkers\Entity\Payment
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * @param  $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * @return
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param  $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return
     */
    public function getType()
    {
        return $this->type;
    }
}