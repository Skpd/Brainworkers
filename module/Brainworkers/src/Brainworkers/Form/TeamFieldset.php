<?php

namespace Brainworkers\Form;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

class TeamFieldset extends Fieldset implements InputFilterProviderInterface, ServiceLocatorAwareInterface
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
            'name'         => array(
                'required'   => true,
                'validators' => array(
                    array('name' => 'StringLength', 'options' => array('max' => 255)),
                    array(
                        'name'    => 'DoctrineModule\Validator\NoObjectExists',
                        'options' => array(
                            'object_repository' => $this->getServiceLocator()->getServiceLocator()->get('doctrine.entity_manager.orm_default')
                                ->getRepository('Brainworkers\Entity\Team'),
                            'fields'            => 'name',
                            'messages' => array(
                                \DoctrineModule\Validator\NoObjectExists::ERROR_OBJECT_FOUND => 'Имя уже занято'
                            )
                        )
                    )
                ),
                'filters'    => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'HtmlEntities'),
                )
            ),
            'makId'        => array(
                'filters' => array(
                    array('name' => 'Int'),
                )
            ),
            'trainer'      => array(
                'required'   => false,
                'validators' => array(
                    array('name' => 'StringLength', 'options' => array('max' => 64)),
                ),
                'filters'    => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'HtmlEntities'),
                )
            ),
            'trainerEmail' => array(
                'required'   => false,
                'validators' => array(
                    array('name' => 'StringLength', 'options' => array('max' => 64)),
                    array('name' => 'EmailAddress'),
                ),
                'filters'    => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'HtmlEntities'),
                )
            ),
            'contacts'     => array(
                'required'   => false,
                'validators' => array(
                    array('name' => 'StringLength', 'options' => array('max' => 64)),
                ),
                'filters'    => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'HtmlEntities'),
                )
            ),
            'contactEmail' => array(
                'required'   => true,
                'validators' => array(
                    array('name' => 'StringLength', 'options' => array('max' => 64)),
                    array('name' => 'EmailAddress'),
                ),
                'filters'    => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'HtmlEntities'),
                )
            ),
            'organization' => array(
                'required'   => false,
                'validators' => array(
                    array('name' => 'StringLength', 'options' => array('max' => 64)),
                ),
                'filters'    => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'HtmlEntities'),
                )
            ),
            'whence'       => array(
                'required'   => true,
                'validators' => array(
                    array('name' => 'StringLength', 'options' => array('max' => 64)),
                ),
                'filters'    => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'HtmlEntities'),
                )
            ),
            'city'         => array(
                'required' => true
            )
        );
    }

    public function init()
    {
        $this->setHydrator(
            new DoctrineObject($this->getServiceLocator()->getServiceLocator()->get('doctrine.entity_manager.orm_default'), 'Brainworkers\Entity\Team'),
            true
        );

        $this->setObject(new \Brainworkers\Entity\Team);

        $this->setName('team');

        $this->add(array('name' => 'name', 'options' => array('label' => 'Название'), 'attributes' => array('required' => true)));
        $this->add(array('name' => 'makId', 'options' => array('label' => 'МАК ID')));
        $this->add(array('name' => 'trainer', 'options' => array('label' => 'ФИО тренера')));
        $this->add(array('name' => 'trainerEmail', 'options' => array('label' => 'E-mail тренера')));
        $this->add(array('name' => 'contacts', 'options' => array('label' => 'Контактные данные тренера')));
        $this->add(array('name' => 'contactEmail', 'options' => array('label' => 'E-mail для связи с командой'), 'attributes' => array('required' => true)));
        $this->add(array('name' => 'organization', 'options' => array('label' => 'Организация, на базе которой создана команда')));

        $this->add(
            array(
                 'type'       => 'select',
                 'name'       => 'whence',
                 'options'    => array(
                     'label'   => 'Откуда Вы узнали о турнире?',
                     'options' => array(
                         'Рекомендация друзей'                                => 'От организатора игр ЧГК в своем городе (представителя)',
                         'Объявление в Livejournal'                           => 'Объявление в Livejournal',
                         'Получил приглашение играть от Оргкомитета на почту' => 'Получил приглашение играть от Оргкомитета на почту',
                         'Группа ЧГК своего города в социальной сети'         => 'Группа ЧГК своего города в социальной сети',
                         'Группа, посвященная мероприятиям в моем городе'     => 'Группа, посвященная мероприятиям в моем городе',
                         'Группа или афиша в моей школе или вузе'             => 'Группа или афиша в моей школе или вузе',
                         'Комитет по делам молодежи'                          => 'Комитет по делам молодежи',
                         'Другое'                                             => 'Другое',
                     )
                 ),
                 'attributes' => array('required' => true, 'id' => 'whence')
            )
        );

        $this->add(
            array(
                 'name'       => 'city',
                 'type'       => 'text',
                 'options'    => array(
                     'label' => 'Город базирования команды',
                 ),
                 'attributes' => array(
                     'required' => true,
                     'id'       => 'city-dropdown',
                     'class'    => 'remote-dropdown',
                     'data-url' => '/search/city',
                 )
            )
        );

        $this->add(
            array(
                 'type'    => 'collection',
                 'name'    => 'players',
                 'options' => array(
                     'label'                  => 'Игроки',
                     'count'                  => 1,
                     'allow_add'              => true,
                     'allow_remove'           => true,
                     'should_create_template' => true,
                     'target_element'         => array('type' => 'PlayerFieldset',)
                 )
            )
        );
    }
}