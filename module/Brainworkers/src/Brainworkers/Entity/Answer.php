<?php

namespace Brainworkers\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation as Form;

/**
 * @ORM\Entity
 * @ORM\Table(name="answers",uniqueConstraints={
 *     @ORM\UniqueConstraint(name="unique_answer", columns={"team_id", "question_id"})})
 * @Form\Name("answer")
 */
class Answer
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
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $content;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isDisputable = false;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @var
     */
    private $resolution;

    /**
     * @ORM\ManyToOne(targetEntity="User\Entity\User")
     * @var \User\Entity\User
     */
    private $jury;

    /**
     * @ORM\ManyToOne(targetEntity="Question")
     * @var Question
     **/
    private $question;

    /**
     * @ORM\ManyToOne(targetEntity="Team")
     * @var Team
     **/
    private $team;

    /**
     * @var
     * @ORM\Column(type="integer", nullable=true)
     */
    private $localId;

    /**
     * @param  $localId
     */
    public function setLocalId($localId)
    {
        $this->localId = $localId;
    }

    /**
     * @return
     */
    public function getLocalId()
    {
        return $this->localId;
    }

    public function __toString()
    {
        return $this->content;
    }
    
    /**
     * @param \User\Entity\User $jury
     */
    public function setJury($jury)
    {
        $this->jury = $jury;
    }

    /**
     * @return \User\Entity\User
     */
    public function getJury()
    {
        return $this->jury;
    }

    /**
     * @param Team $team
     */
    public function setTeam(Team $team)
    {
        $this->team = $team;
    }

    /**
     * @return Team
     */
    public function getTeam()
    {
        return $this->team;
    }



    public function setQuestion($question)
    {
        $this->question = $question;
    }

    /**
     * @return Question
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param  $resolution
     */
    public function setResolution($resolution)
    {
        $this->resolution = $resolution;
    }

    /**
     * @return
     */
    public function getResolution()
    {
        return $this->resolution;
    }

    /**
     * @param boolean $isDisputable
     */
    public function setIsDisputable($isDisputable)
    {
        $this->isDisputable = $isDisputable;
    }

    /**
     * @return boolean
     */
    public function getIsDisputable()
    {
        return $this->isDisputable;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
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
}