<?php

namespace Brainworkers\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation as Form;

/**
 * @ORM\Entity
 * @ORM\Table(name="questions")
 * @Form\Name("question")
 */
class Question
{
    #region Fields

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Form\Attributes({"type":"hidden"})
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="float", nullable=false)
     * @var float
     */
    private $rating = 0;

    /**
     * @ORM\OneToMany(targetEntity="Answer", mappedBy="question")
     */
    private $answer;

    #endregion

    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }

    public function addAnswers($answers)
    {
        if (!is_array($answers) || !$answers instanceof ArrayCollection) {
            $answers = array($answers);
        }

        foreach ($answers as $answer) {
            $this->answers->add($answer);
        }
    }

    public function removeAnswers($answers)
    {
        if (!is_array($answers) || !$answers instanceof ArrayCollection) {
            $answers = array($answers);
        }

        foreach ($answers as $answer) {
            $this->answers->removeElement($answer);
        }
    }

    #region Getters / Setters

    /**
     * @param float $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    /**
     * @return float
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param $answers
     */
    public function setAnswers($answers)
    {
        $this->answers = $answers;
    }

    /**
     * @return ArrayCollection
     */
    public function getAnswers()
    {
        return $this->answers;
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

    public function setTeams($teams)
    {
        $this->teams = $teams;
    }

    public function getTeams()
    {
        return $this->teams;
    }

    #endregion
}