<?php

namespace Brainworkers\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation as Form;

/**
 * @ORM\Entity
 * @ORM\Table(name="players")
 * @Form\Name("team")
 */
class Player
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
     * @ORM\Column(type="integer", name="prid")
     * @var int
     */
    private $ranking;

    /**
     * @ORM\Column(type="string", length=32)
     * @var string
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=32)
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=32)
     * @var string
     */
    private $patronymic;

    /**
     * @ORM\Column(type="date", name="birthdate")
     * @var \DateTime
     */
    private $birthDate;

    /**
     * @var string
     * @ORM\Column(type="string", unique=false,  length=255)
     * @Form\Type("Zend\Form\Element\Email")
     * @Form\Required
     */
    private $email;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @var int
     */
    private $flag;

    /**
     * @ORM\Column(type="string", length=32)
     * @var string
     */
    private $vk;

    /**
     * @ORM\ManyToOne(targetEntity="User\Entity\User")
     * @var \User\Entity\User
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Brainworkers\Entity\Team", inversedBy="players")
     * @var Team
     */
    private $team;

    public function isCaptain()
    {
        return $this->flag == 1;
    }

    public function __toString()
    {
        return $this->surname . ' ' . $this->name . ' ' . $this->patronymic;
    }

    /**
     * @param string $birthDate
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;
    }

    /**
     * @return string
     */
    public function getBirthDate()
    {
        return $this->birthDate->format('d.m.Y');
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param int $flag
     */
    public function setFlag($flag)
    {
        $this->flag = $flag;
    }

    /**
     * @return int
     */
    public function getFlag()
    {
        return $this->flag;
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

    /**
     * @param string $patronymic
     */
    public function setPatronymic($patronymic)
    {
        $this->patronymic = $patronymic;
    }

    /**
     * @return string
     */
    public function getPatronymic()
    {
        return $this->patronymic;
    }

    /**
     * @param int $ranking
     */
    public function setRanking($ranking)
    {
        $this->ranking = $ranking;
    }

    /**
     * @return int
     */
    public function getRanking()
    {
        return $this->ranking;
    }

    /**
     * @param string $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param \Brainworkers\Entity\Team $team
     */
    public function setTeam($team)
    {
        $this->team = $team;
    }

    /**
     * @return \Brainworkers\Entity\Team
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * @param \User\Entity\User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return \User\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param string $vk
     */
    public function setVk($vk)
    {
        $this->vk = $vk;
    }

    /**
     * @return string
     */
    public function getVk()
    {
        return $this->vk;
    }



}