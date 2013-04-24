<?php

namespace Brainworkers\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation as Form;

/**
 * @ORM\Entity
 * @ORM\Table(name="places")
 * @Form\Name("place")
 * @ORM\HasLifecycleCallbacks
 */
class Place
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
     * @ORM\Column(type="integer")
     */
    private $teamsMax;

    /**
     * @ORM\Column(type="string")
     */
    private $address;

    /**
     * @ORM\Column(type="string")
     */
    private $ip;

    /**
     * @ORM\Column(type="text")
     */
    private $contact;

    /**
     * @ORM\Column(type="text")
     */
    private $addMoney;

    /**
     * @ORM\Column(type="boolean")
     */
    private $videoState;

    /**
     * @ORM\Column(type="string")
     */
    private $eventVk;

    /**
     * @ORM\OneToMany(targetEntity="Brainworkers\Entity\Team", mappedBy="place")
     * @var \Brainworkers\Entity\Team[]
     */
    private $teams;

    /**
     * @ORM\ManyToOne(targetEntity="Brainworkers\Entity\City")
     * @var \Brainworkers\Entity\City
     */
    private $city;

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
     * @var \User\Entity\User
     * @ORM\ManyToOne(targetEntity="\User\Entity\User")
     */
    private $owner;

    /**
     * @ORM\Column(type="boolean", name="state")
     * @var boolean
     */
    private $approved = 0;
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

    /** @ORM\PreRemove */
    public function onPreRemove()
    {
        foreach ($this->teams as $team) {
            $team->setPlace(null);
        }

    }

    public function __construct()
    {
        $this->teams = new ArrayCollection();
    }

    public function addTeams($teams)
    {
        if (!is_array($teams) || !$teams instanceof ArrayCollection) {
            $teams = array($teams);
        }

        // O_o
        foreach ($teams as $team) {
            foreach ($team as $t) {
                $this->teams->add($t);
                $t->setPlace($this);
            }
        }
    }

    public function removeTeams($teams)
    {
        if (!is_array($teams) || !$teams instanceof ArrayCollection) {
            $teams = array($teams);
        }

        // O_o
        foreach ($teams as $team) {
            foreach ($team as $t) {
                $this->teams->removeElement($t);
                $t->setPlace($this);
            }
        }
    }

    #region Getters / Setters

    public function setAddMoney($addMoney)
    {
        $this->addMoney = $addMoney;
    }

    public function getAddMoney()
    {
        return $this->addMoney;
    }

    /**
     * @param boolean $approved
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;
    }

    /**
     * @return boolean
     */
    public function getApproved()
    {
        return $this->approved;
    }

    public function setAddress($address)
    {
        $this->address = $address;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @param \User\Entity\User $owner
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    /**
     * @return \User\Entity\User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setContact($contact)
    {
        $this->contact = $contact;
    }

    public function getContact()
    {
        return $this->contact;
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

    public function setEventVk($eventVk)
    {
        $this->eventVk = $eventVk;
    }

    public function getEventVk()
    {
        return $this->eventVk;
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

    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    public function getIp()
    {
        return $this->ip;
    }

    public function setTeams($teams)
    {
        $this->teams = $teams;
    }

    public function getTeams()
    {
        return $this->teams;
    }

    public function setTeamsMax($teamsMax)
    {
        $this->teamsMax = $teamsMax;
    }

    public function getTeamsMax()
    {
        return $this->teamsMax;
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

    public function setVideoState($videoState)
    {
        $this->videoState = $videoState;
    }

    public function getVideoState()
    {
        return $this->videoState;
    }

    #endregion
}