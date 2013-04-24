<?php

namespace Brainworkers\Form;

use DoctrineModule\Validator\ObjectExists;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class AnswerForm extends Form implements InputFilterProviderInterface
{
    /** @var \Doctrine\ORM\EntityManager */
    private $entityManager;

    public function getInputFilterSpecification()
    {
        return array(
            'question' => array(
                'required'   => false,
                'validators' => array(
                    array(
                        'name'    => 'DoctrineModule\Validator\ObjectExists',
                        'options' => array(
                            'object_repository' => $this->entityManager->getRepository('Brainworkers\Entity\Question'),
                            'fields'            => array('id')
                        ),
                        'messages' => array(
                            ObjectExists::ERROR_NO_OBJECT_FOUND => 'Question was not found.'
                        )
                    )
                ),
                'filters'  => array(
                    array('name' => 'Int'),
                )
            )
        );
    }

    public function init()
    {
        $this->setAttribute('method', 'post');

        $this->add(
            array(
                 'type'    => 'Zend\Form\Element\Collection',
                 'name'    => 'answers',
                 'options' => array(
                     'count'                  => 1,
                     'should_create_template' => true,
                     'target_element'         => array(
                         'type' => 'AnswerFieldset',
                     )
                 )
            )
        );

        $this->add(
            array(
                 'type'    => 'DoctrineModule\Form\Element\ObjectSelect',
                 'name'    => 'question',
                 'options' => array(
                     'label'          => 'Question',
                     'object_manager' => $this->entityManager,
                     'target_class'   => 'Brainworkers\Entity\Question',
                     'property'       => 'name'
                 )
            )
        );

        $this->add(
            array(
                 'type' => 'Zend\Form\Element\Csrf',
                 'name' => 'csrf'
            )
        );

        $this->add(
            array(
                 'name'       => 'submit',
                 'attributes' => array('type' => 'submit', 'value' => 'Send', 'class' => 'btn btn-primary')
            )
        );
    }

    public function __construct($em)
    {
        $this->entityManager = $em;
        parent::__construct('answers');
    }
}