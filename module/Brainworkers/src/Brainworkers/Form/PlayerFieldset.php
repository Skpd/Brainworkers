<?php

namespace Brainworkers\Form;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

class PlayerFieldset extends Fieldset implements InputFilterProviderInterface, ServiceLocatorAwareInterface
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
            'ranking'    => array(
                'required' => false
            ),
            'birthDate'  => array(
                'required' => true,
                'validators' => array(
                    array('name' => 'date', 'options' => array('format' => 'd.m.Y'))
                ),
            ),
            'vk'         => array(
                'required'   => false,
                'validators' => array(
                    array('name' => 'StringLength', 'options' => array('max' => 32)),
                ),
                'filters'    => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'HtmlEntities'),
                )
            ),
            'surname'    => array(
                'required'   => true,
                'validators' => array(
                    array('name' => 'StringLength', 'options' => array('max' => 32)),
                ),
                'filters'    => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'HtmlEntities'),
                )
            ),
            'name'       => array(
                'required'   => true,
                'validators' => array(
                    array('name' => 'StringLength', 'options' => array('max' => 32)),
                ),
                'filters'    => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'HtmlEntities'),
                )
            ),
            'patronymic' => array(
                'required'   => true,
                'validators' => array(
                    array('name' => 'StringLength', 'options' => array('max' => 32)),
                ),
                'filters'    => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'HtmlEntities'),
                )
            ),
            'email'      => array(
                'required'   => false,
                'validators' => array(
                    array('name' => 'StringLength', 'options' => array('max' => 32)),
                    array('name' => 'EmailAddress'),
                ),
                'filters'    => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'HtmlEntities'),
                )
            ),
        );
    }

    public function init()
    {
        $this->setHydrator(
            new DoctrineObject($this->getServiceLocator()->getServiceLocator()->get('doctrine.entity_manager.orm_default'), 'Brainworkers\Entity\Player'),
            true
        );

        $this->setObject(new \Brainworkers\Entity\Player);
        $this->setName('players');

        $this->add(array('name' => 'ranking', 'options' => array('label' => 'ID игрока в рейтинге МАК')));
        $this->add(
            array(
                 'type'       => 'text',
                 'name'       => 'birthDate',
                 'attributes' => array('class' => 'datepicker-aware'),
                 'options'    => array('label' => 'Дата рождения')
            )
        );
        $this->add(array('name' => 'vk', 'options' => array('label' => 'ВК')));
        $this->add(array('name' => 'surname', 'options' => array('label' => 'Фамилия')));
        $this->add(array('name' => 'name', 'options' => array('label' => 'Имя')));
        $this->add(array('name' => 'patronymic', 'options' => array('label' => 'Отчество')));
        $this->add(array('name' => 'email', 'type' => 'email', 'options' => array('label' => 'E-mail')));

        $this->add(
            array(
                 'name'       => 'flag',
                 'type'       => 'select',
                 'attributes' => array(),
                 'options'    => array(
                     'label'   => 'Статус',
                     'options' => array(
                         1 => 'Капитан',
                         2 => 'Базовый состав',
                         3 => 'Легионер',
                     )
                 )
            )
        );

        $this->add(
            array(
                 'name'       => 'user',
                 'type'       => 'text',
                 'label'      => 'Пользователь',
                 'options' => array(
                     'label' => 'Пользователь'
                 ),
                 'attributes' => array(
                     'id'       => 'user-dropdown',
                     'class'    => 'remote-dropdown',
                     'data-url' => '/search/user',
                 ),
            )
        );
    }
}