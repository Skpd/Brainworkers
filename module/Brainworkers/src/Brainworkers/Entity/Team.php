<?php

namespace Brainworkers\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation as Form;

/**
 * @ORM\Entity(repositoryClass="Brainworkers\Repository\Team")
 * @ORM\Table(name="teams")
 * @Form\Name("team")
 * @ORM\HasLifecycleCallbacks
 */
class Team
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
     * @ORM\Column(type="integer", nullable=true)
     * @var int
     */
    private $localId;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="integer")
     */
    private $makId;

    /**
     * @var string
     * @ORM\Column(type="string", length=64)
     */
    private $trainer;

    /**
     * @var string
     * @ORM\Column(type="string", length=64)
     */
    private $trainerEmail;

    /**
     * @var string
     * @ORM\Column(type="string", length=64)
     */
    private $contacts;

    /**
     * @var string
     * @ORM\Column(type="string", length=64)
     */
    private $contactEmail;

    /**
     * @var string
     * @ORM\Column(type="string", length=64)
     */
    private $organization;

    /**
     * @var string
     * @ORM\Column(type="string", length=64)
     */
    private $whence;

    /**
     * @var string
     * @ORM\Column(type="string", length=15)
     */
    private $ip;

    /**
     * @var \User\Entity\User
     * @ORM\ManyToOne(targetEntity="\User\Entity\User")
     */
    private $owner;

    /**
     * @ORM\ManyToOne(targetEntity="Brainworkers\Entity\City")
     * @var \Brainworkers\Entity\City
     */
    private $city;

    /**
     * @ORM\ManyToOne(targetEntity="Brainworkers\Entity\Region")
     * @var \Brainworkers\Entity\Region
     */
    private $region;

    /**
     * @ORM\ManyToOne(targetEntity="Brainworkers\Entity\Country")
     * @var \Brainworkers\Entity\Country
     */
    private $country;

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
     * @ORM\OneToMany(targetEntity="Brainworkers\Entity\Player", mappedBy="team" ,cascade={"persist"})
     * @var \Brainworkers\Entity\Player[]
     */
    private $players;

    /**
     * @ORM\OneToMany(targetEntity="Brainworkers\Entity\Answer", mappedBy="team")
     * @Form\Exclude()
     */
    private $answers;

    /**
     * @ORM\ManyToOne(targetEntity="Brainworkers\Entity\Place", inversedBy="teams")
     * @var \Brainworkers\Entity\Place
     */
    private $place;

    /**
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    private $payed;
    #endregion

    /**
     * @return ArrayCollection
     */
    public function getCaptain()
    {
        return $this->getPlayers()->filter(
            function ($player) {
                /** @var $player \Brainworkers\Entity\Player */
                return $player->isCaptain();
            }
        );
    }

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

    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->players   = new ArrayCollection();
    }

    /**
     * @param \Brainworkers\Entity\Player $players
     */
    public function setPlayers($players)
    {
        $this->players = $players;
    }

    /**
     * @return \Brainworkers\Entity\Player[]
     */
    public function getPlayers()
    {
        return $this->players;
    }

    public function addQuestions($questions)
    {
        if (!is_array($questions) || !$questions instanceof ArrayCollection) {
            $questions = array($questions);
        }

        foreach ($questions as $question) {
            $this->questions->add($question);
        }
    }

    public function removeQuestions($questions)
    {
        if (!is_array($questions) || !$questions instanceof ArrayCollection) {
            $questions = array($questions);
        }

        foreach ($questions as $question) {
            $this->questions->removeElement($question);
        }
    }

    public function addPlayers($players)
    {
        if (!is_array($players) || !$players instanceof ArrayCollection) {
            $players = array($players);
        }

        // O_o
        foreach ($players as $player) {
            foreach ($player as $p) {
                $this->players->add($p);
                $p->setTeam($this);
            }
        }
    }

    public function removePlayers($players)
    {
        if (!is_array($players) || !$players instanceof ArrayCollection) {
            $players = array($players);
        }

        // O_o
        foreach ($players as $player) {
            foreach ($player as $p) {
                $this->players->removeElement($p);
            }
        }
    }

    public function __toString()
    {
        return $this->getName();
    }

    #region Getters / Setters
    public function setAnswers($answers)
    {
        $this->answers = $answers;
    }

    /**
     * @return Answer[]
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * @param int $localId
     */
    public function setLocalId($localId)
    {
        $this->localId = $localId;
    }

    /**
     * @return int
     */
    public function getLocalId()
    {
        return $this->localId;
    }

    /**
     * @param boolean $payed
     */
    public function setPayed($payed)
    {
        $this->payed = $payed;
    }

    /**
     * @return boolean
     */
    public function getPayed()
    {
        return $this->payed;
    }

    /**
     * @param \Brainworkers\Entity\Place $place
     */
    public function setPlace($place)
    {
        $this->setLocalId($place->getTeams()->count());
        $this->place = $place;
    }

    /**
     * @return \Brainworkers\Entity\Place
     */
    public function getPlace()
    {
        return $this->place;
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
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function setQuestions($questions)
    {
        $this->questions = $questions;
    }

    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * @param \Brainworkers\Entity\City $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return \Brainworkers\Entity\City
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $contactEmail
     */
    public function setContactEmail($contactEmail)
    {
        $this->contactEmail = $contactEmail;
    }

    /**
     * @return string
     */
    public function getContactEmail()
    {
        return $this->contactEmail;
    }

    /**
     * @param string $contacts
     */
    public function setContacts($contacts)
    {
        $this->contacts = $contacts;
    }

    /**
     * @return string
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * @param \Brainworkers\Entity\Country $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return \Brainworkers\Entity\Country
     */
    public function getCountry()
    {
        return $this->country;
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
     * @param string $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param string $makId
     */
    public function setMakId($makId)
    {
        $this->makId = $makId;
    }

    /**
     * @return string
     */
    public function getMakId()
    {
        return $this->makId;
    }

    /**
     * @param string $organization
     */
    public function setOrganization($organization)
    {
        $this->organization = $organization;
    }

    /**
     * @return string
     */
    public function getOrganization()
    {
        return $this->organization;
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

    /**
     * @param \Brainworkers\Entity\Region $region
     */
    public function setRegion($region)
    {
        $this->region = $region;
    }

    /**
     * @return \Brainworkers\Entity\Region
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param string $trainer
     */
    public function setTrainer($trainer)
    {
        $this->trainer = $trainer;
    }

    /**
     * @return string
     */
    public function getTrainer()
    {
        return $this->trainer;
    }

    /**
     * @param string $trainerEmail
     */
    public function setTrainerEmail($trainerEmail)
    {
        $this->trainerEmail = $trainerEmail;
    }

    /**
     * @return string
     */
    public function getTrainerEmail()
    {
        return $this->trainerEmail;
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

    /**
     * @param string $whence
     */
    public function setWhence($whence)
    {
        $this->whence = $whence;
    }

    /**
     * @return string
     */
    public function getWhence()
    {
        return $this->whence;
    }

    #endregion
}