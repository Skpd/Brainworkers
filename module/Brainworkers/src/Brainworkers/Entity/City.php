<?php

namespace Brainworkers\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation as Form;

/**
 * @ORM\Entity()
 * @ORM\Table(name="city")
 * @Form\Name("city")
 */
class City
{

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
     * @ORM\Column(type="string", length=64, unique=false, nullable=false)
     */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="Region")
     * @var string
     */
    protected $region;

    /**
     * @ORM\ManyToOne(targetEntity="Country")
     * @var string
     */
    protected $country;

    public function __toString()
    {
        return (string) $this->id;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
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
     * @param string $region
     */
    public function setRegion($region)
    {
        $this->region = $region;
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }


}