<?php

namespace Brainworkers\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Place extends Form implements InputFilterProviderInterface, ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator = null;

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
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
            'teamsMax' => array(
                'required' => true
            ),
            'address'  => array(
                'required' => true
            ),
            'contact'  => array(
                'required' => true
            ),
            'eventVk'  => array(
                'required' => false
            ),
            'teams'    => array(
                'required' => false
            )
        );
    }

    public function init()
    {
        $this->setName('place');

        $this->add(
            array(
                 'name'       => 'city',
                 'type'       => 'text',
                 'options'    => array(
                     'label' => 'Город',
                 ),
                 'attributes' => array(
                     'id'       => 'city-dropdown',
                     'class'    => 'remote-dropdown',
                     'data-url' => '/search/city',
                 )
            )
        );
        $this->add(
            array(
                 'name'    => 'teamsMax',
                 'type'    => 'number',
                 'options' => array(
                     'label' => 'Максимум команд на площадке',
                 ),
            )
        );
        $this->add(
            array(
                 'name'    => 'address',
                 'type'    => 'textarea',
                 'options' => array(
                     'label' => 'Точный адрес места проведения',
                 ),
            )
        );
        $this->add(
            array(
                 'name'    => 'contact',
                 'type'    => 'textarea',
                 'options' => array(
                     'label' => 'Контактные данные организатора площадки',
                 ),
            )
        );
        $this->add(
            array(
                 'name'    => 'addMoney',
                 'type'    => 'textarea',
                 'options' => array(
                     'label' => 'Дополнительный взнос на площадке',
                 ),
            )
        );
        $this->add(
            array(
                 'name'    => 'videoState',
                 'type'    => 'select',
                 'options' => array(
                     'label'   => 'Будет ли на площадке видеосъемка?',
                     'options' => array(
                         0 => 'Нет',
                         1 => 'Да, любительская',
                         2 => 'Да, новостной репортаж',
                         3 => 'Да, полная съемка',
                     )
                 ),
            )
        );
        $this->add(
            array(
                 'name'    => 'EventVk',
                 'type'    => 'text',
                 'options' => array(
                     'label' => 'Событие ВК',
                 ),
            )
        );

        $this->add(
            array(
                 'name'    => 'massMedia',
                 'type'    => 'checkbox',
                 'options' => array(
                     'label' => 'Будут ли СМИ?',
                 ),
            )
        );

        $this->add(
            array(
                 'name'    => 'videoEquipment',
                 'type'    => 'text',
                 'options' => array(
                     'label' => 'Имеющаяся техника для видеосъемки?',
                 ),
            )
        );
    }
}