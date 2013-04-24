<?php

namespace Brainworkers\Form;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

class LocationFieldset extends Fieldset implements InputFilterProviderInterface, ServiceLocatorAwareInterface
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

    public function getInputFilterSpecification()
    {
        return array(
            'city' => array(
                'required'   => true,
                'validators' => array(
                    array(
                        'name'    => 'DoctrineModule\Validator\ObjectExists',
                        'options' => array(
                            'object_repository' => $this->getServiceLocator()->getServiceLocator()->get('doctrine.entity_manager.orm_default')->getRepository(
                                'Brainworkers\Entity\City'
                            ),
                            'fields'            => 'id'
                        )
                    )
                )
            ),
//            'region' => array(
//                'required'   => true,
//                'validators' => array(
//                    array(
//                        'name'    => 'DoctrineModule\Validator\ObjectExists',
//                        'options' => array(
//                            'object_repository' => $this->getServiceLocator()->getServiceLocator()->get('doctrine.entity_manager.orm_default')->getRepository(
//                                'Brainworkers\Entity\Region'
//                            ),
//                            'fields'            => 'id'
//                        )
//                    )
//                )
//            ),
//            'country' => array(
//                'required'   => true,
//                'validators' => array(
//                    array(
//                        'name'    => 'DoctrineModule\Validator\ObjectExists',
//                        'options' => array(
//                            'object_repository' => $this->getServiceLocator()->getServiceLocator()->get('doctrine.entity_manager.orm_default')->getRepository(
//                                'Brainworkers\Entity\Country'
//                            ),
//                            'fields'            => 'id'
//                        )
//                    )
//                )
//            ),
        );
    }

    public function init()
    {
//        $this->add(
//            array(
//                 'name'       => 'country',
//                 'type'       => 'text',
//                 'options'    => array(
//                     'label' => 'Country',
//                 ),
//                 'attributes' => array(
//                     'id'       => 'country-dropdown',
//                     'class'    => 'remote-dropdown',
//                     'data-url' => '/search/country',
//                 )
//            )
//        );
//
//        $this->add(
//            array(
//                 'name'       => 'region',
//                 'type'       => 'text',
//                 'options'    => array(
//                     'label' => 'Region',
//                 ),
//                 'attributes' => array(
//                     'id'       => 'region-dropdown',
//                     'class'    => 'remote-dropdown',
//                     'data-url' => '/search/region',
//                 )
//            )
//        );

        $this->add(
            array(
                 'name'       => 'city',
                 'type'       => 'text',
                 'options'    => array(
                     'label' => 'City',
                 ),
                 'attributes' => array(
                     'id'       => 'city-dropdown',
                     'class'    => 'remote-dropdown',
                     'data-url' => '/search/city',
                 )
            )
        );
    }
}