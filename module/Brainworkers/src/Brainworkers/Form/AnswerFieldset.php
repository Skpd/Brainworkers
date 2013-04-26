<?php

namespace Brainworkers\Form;

use Brainworkers\Entity\Answer;
use Zend\Form\Fieldset;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AnswerFieldset extends Fieldset implements InputFilterProviderInterface, ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator = null;

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        return $this;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /** @var \Doctrine\ORM\EntityManager */
    private $entityManager;

    public function getInputFilterSpecification()
    {
        return array(
            'content'      => array(
                'required'   => false,
                'filters'    => array(
                    array('name' => 'HtmlEntities'),
                    array('name' => 'StringTrim'),
                )
            ),
            'isDisputable' => array(
                'required' => false,
                'filters'  => array(
                    array('name' => 'Boolean'),
                )
            ),
            'localId'         => array(
                'required'   => true,
            )
        );
    }

    public function init()
    {
        $this->setHydrator(new DoctrineObject($this->entityManager, 'Brainworkers\Entity\Answer'));
        $this->setObject(new Answer);

        $this->setLabel(' ');

        $this->add(
            array(
                 'type'    => 'text',
                 'name'    => 'localId',
                 'options' => array(
                     'label'          => 'Команда',
//                     'object_manager' => $this->entityManager,
//                     'target_class'   => 'Brainworkers\Entity\Team',
//                     'property'       => 'name'
                 )
            )
        );

        $this->add(
            array(
                 'name'       => 'content',
                 'options'    => array('label' => 'Текст'),
                 'attributes' => array('class' => 'answer-content')
            )
        );

//        $this->add(
//            array(
//                 'type'    => 'checkbox',
//                 'name'    => 'isDisputable',
//                 'options' => array('label' => 'Спорный?'),
//            )
//        );
    }

    public function __construct($em)
    {
        $this->entityManager = $em;
        parent::__construct('answer');
    }
}