<?php

namespace User\Form;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use ZfcUser\Form\Register;

class ResetPassword extends \Zend\Form\Form implements InputFilterProviderInterface, ServiceLocatorAwareInterface
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
            'email' => array(
                'required'   => true,
                'validators' => array(
                    array(
                        'name'    => 'emailaddress',
                    )
                )
            ),
        );
    }

    public function init()
    {
        $this->setHydrator(
            new DoctrineObject($this->getServiceLocator()->getServiceLocator()->get('doctrine.entity_manager.orm_default'), 'User\Entity\User'),
            true
        );
        $this->add(array('name' => 'email', 'type' => 'email', 'options' => array('label' => 'Email')));
    }
}
