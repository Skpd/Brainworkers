<?php

namespace User\Entity;

use BjyAuthorize\Provider\Role\ProviderInterface AS RoleProviderInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use ZfcUser\Entity\UserInterface;
use Zend\Form\Annotation as Form;

/**
 * @ORM\Entity(repositoryClass="User\Repository\User")
 * @ORM\Table(name="users")
 * @ORM\HasLifecycleCallbacks
 * @Form\Name("user")
 */
class User implements UserInterface, RoleProviderInterface
{
    #region Fields
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Form\Attributes({"type":"hidden"})
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, unique=true, nullable=true)
     * @Form\Exclude()
     */
    protected $username;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true,  length=255)
     * @Form\Type("Zend\Form\Element\Email")
     * @Form\Required
     */
    protected $email;

    /**
     * @var string
     * @ORM\Column(type="string", length=50, nullable=true, name="display_name")
     */
    protected $displayName;

    /**
     * @var string
     * @ORM\Column(type="string", length=128)
     * @Form\Type("password")
     */
    protected $password;

    /**
     * @var string
     * @ORM\Column(type="string", length=3)
     * @Form\Exclude()
     */
    protected $salt;

    /**
     * @var int
     * @Form\Exclude()
     */
    protected $state;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="User\Entity\Role", inversedBy="users")
     * @ORM\JoinTable(name="user_role_linker",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     * )
     */
    protected $userRoles;

    /**
     * @ORM\OneToMany(targetEntity="Brainworkers\Entity\Answer", mappedBy="judge")
     * @var \Brainworkers\Entity\Answer
     * @Form\Exclude()
     */
    protected $answer;

    /**
     * @ORM\Column(type="string", length=32)
     * @var string
     */
    protected $surname;

    /**
     * @ORM\Column(type="string", length=32)
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     * @var string
     */
    protected $patronymic;

    /**
     * @ORM\Column(type="datetime")
     * @Form\Exclude()
     * @var \DateTime
     */
    protected $created;

    /**
     * @ORM\Column(type="datetime")
     * @Form\Exclude()
     * @var \DateTime
     */
    protected $updated;

    /**
     * @ORM\Column(type="string", length=1)
     * @var string
     */
    protected $gender;

    /**
     * @ORM\ManyToOne(targetEntity="Brainworkers\Entity\City")
     * @Form\Type("text")
     * @Form\Attributes({"class":"remote-dropdown","data-url":"/search/city"})
     * @var \Brainworkers\Entity\City
     */
    protected $city;

    /**
     * @ORM\ManyToOne(targetEntity="Brainworkers\Entity\Region")
     * @Form\Exclude()
     * @var \Brainworkers\Entity\Region
     */
    protected $region;

    /**
     * @ORM\ManyToOne(targetEntity="Brainworkers\Entity\Country")
     * @Form\Exclude()
     * @var \Brainworkers\Entity\Country
     */
    protected $country;

    /**
     * @ORM\OneToMany(targetEntity="Brainworkers\Entity\Team", mappedBy="owner")
     * @var \Brainworkers\Entity\Team[]
     * @Form\Exclude()
     */
    protected $teams;

    /**
     * @ORM\OneToOne(targetEntity="Brainworkers\Entity\Place", mappedBy="owner")
     * @var \Brainworkers\Entity\Place
     * @Form\Exclude()
     */
    protected $place;

    /**
     * @param \Brainworkers\Entity\Place $place
     */
    public function setPlace($place)
    {
        $this->place = $place;
    }

    /**
     * @return \Brainworkers\Entity\Place
     */
    public function getPlace()
    {
        return $this->place;
    }
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

    /**
     * Initialises the roles variable.
     */
    public function __construct()
    {
        $this->userRoles = new ArrayCollection();
    }

    /**
     * @return \Zend\Permissions\Acl\Role\RoleInterface[]
     */
    public function getRoles()
    {
        return $this->userRoles->getValues();
    }

    public function __toString()
    {
        return (string) $this->id;
    }

    #region Getters / Setters
    public function setTeams($teams)
    {
        $this->teams = $teams;
    }

    /**
     * @return \Brainworkers\Entity\Team[]
     */
    public function getTeams()
    {
        return $this->teams;
    }
    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id.
     *
     * @param int $id
     *
     * @return void
     */
    public function setId($id)
    {
        $this->id = (int) $id;
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set username.
     *
     * @param string $username
     *
     * @return void
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return void
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get displayName.
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->getSurname() . ' ' . $this->getName() . ' ' . $this->getPatronymic();
    }

    /**
     * Set displayName.
     *
     * @param string $displayName
     *
     * @return void
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    /**
     * Get password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set password.
     *
     * @param string $password
     *
     * @return void
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Get state.
     *
     * @return int
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set state.
     *
     * @param int $state
     *
     * @return void
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $roles
     */
    public function addUserRoles($roles)
    {
        foreach ($roles as $role) {
            $this->userRoles->add($role);
        }
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $roles
     */
    public function removeUserRoles($roles)
    {
        foreach ($roles as $role) {
            $this->userRoles->removeElement($role);
        }
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserRoles()
    {
        return $this->userRoles;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $user_roles
     */
    public function setUserRoles($user_roles)
    {
        $this->userRoles = $user_roles;
    }

    /**
     * @param \Brainworkers\Entity\Answer $answer
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;
    }

    /**
     * @return \Brainworkers\Entity\Answer
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * @param string $city
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
     * @param string $country
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
     * @param string $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
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
     * @param string $region
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
     * @param string $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
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
