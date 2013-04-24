<?php

namespace User\Form;

use Doctrine\ORM\EntityManager;
use ZfcUser\Options\RegistrationOptionsInterface;

class Register extends \ZfcUser\Form\Register
{

    public function __construct($name = null, RegistrationOptionsInterface $options, EntityManager $em)
    {
        parent::__construct($name, $options);

        //surname, name, patronymic, type, timestamp, gender, city_id, region_id, country_id
        $this->add(
            array(
                 'name' => 'surname',
                 'options' => array('label' => 'Surname'),
                 'attributes' => array('type' => 'text','required' => true)
            )
        );

        $this->add(
            array(
                 'name' => 'name',
                 'options' => array('label' => 'Name'),
                 'attributes' => array('type' => 'text','required' => true)
            )
        );

        $this->add(
            array(
                 'name' => 'gender',
                 'type' => 'Zend\Form\Element\Select',
                 'attributes' =>  array(
                     'options' => array(
                         'm' => 'Male',
                         'f' => 'Female'
                     ),
                    'required' => true
                 ),
                 'options' => array('label' => 'Gender')
            )
        );

        $this->add(
            array(
                 'type'    => 'DoctrineModule\Form\Element\ObjectSelect',
                 'name'    => 'city',
                 'options' => array(
                     'label'          => 'City',
                     'object_manager' => $em,
                     'target_class'   => 'Brainworkers\Entity\City',
                     'property'       => 'name'
                 )
            )
        );
    }

    public function init()
    {

    }
}
