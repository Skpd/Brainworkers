<?php

namespace Brainworkers;

return array(
    'router'          => array(
        'routes' => array(
            'refresh-local-id' => array(
                'type'         => 'literal',
                'options'      => array(
                    'route'    => '/refresh-local-id',
                    'defaults' => array(
                        'controller' => 'Brainworkers\Controller\Team',
                        'action' => 'refresh-local-id'
                    ),
                ),
            ),
            'payment'   => array(
                'type'         => 'literal',
                'options'      => array(
                    'route'    => '/payment',
                    'defaults' => array(
                        'controller' => 'Brainworkers\Controller\Payment',
                    ),
                ),
                'child_routes' => array(
                    'create'  => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/create',
                            'defaults' => array(
                                'action' => 'create',
                            ),
                        )
                    ),
                    'success' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/success',
                            'defaults' => array(
                                'action' => 'success',
                            ),
                        )
                    ),
                    'fail'    => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/fail',
                            'defaults' => array(
                                'action' => 'fail',
                            ),
                        )
                    ),
                    'status'  => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/status',
                            'defaults' => array(
                                'action' => 'status',
                            ),
                        )
                    ),
                )
            ),
            'home'      => array(
                'type'          => 'literal',
                'may_terminate' => true,
                'options'       => array(
                    'route'         => '/',
                    'may_terminate' => true,
                    'defaults'      => array(
                        'controller' => 'Brainworkers\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
                'child_routes'  => array()
            ),
            'search'    => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/search/:type',
                    'defaults' => array(
                        'controller' => 'Brainworkers\Controller\Index',
                        'action'     => 'search',
                    ),
                ),
            ),
            'team'      => array(
                'type'          => 'literal',
                'options'       => array(
                    'route'    => '/team',
                    'defaults' => array(
                        'controller' => 'Brainworkers\Controller\Team',
                        'action'     => 'list',
                    ),
                ),
                'may_terminate' => true,
                'child_routes'  => array(
                    'assign-to' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/assign-to/:placeId',
                            'defaults' => array(
                                'controller' => 'Brainworkers\Controller\Team',
                                'action'     => 'assign-to',
                            ),
                        )
                    ),
                    'show'      => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/show[/:id]',
                            'defaults' => array(
                                'controller' => 'Brainworkers\Controller\Team',
                                'action'     => 'show',
                            ),
                        )
                    ),
                    'add'       => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/add',
                            'defaults' => array(
                                'controller' => 'Brainworkers\Controller\Team',
                                'action'     => 'add',
                            ),
                        )
                    ),
                    'edit'      => array(
                        'type'          => 'segment',
                        'may_terminate' => true,
                        'options'       => array(
                            'route'    => '/edit/:id',
                            'defaults' => array(
                                'controller' => 'Brainworkers\Controller\Team',
                                'action'     => 'edit',
                            ),
                        )
                    ),
                    'delete'    => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/delete/:id',
                            'defaults' => array(
                                'controller' => 'Brainworkers\Controller\Team',
                                'action'     => 'delete',
                            ),
                        )
                    ),
                    'list'      => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/list',
                            'defaults' => array(
                                'controller' => 'Brainworkers\Controller\Team',
                                'action'     => 'list',
                            ),
                        )
                    ),
                )
            ),
            'place'     => array(
                'type'          => 'literal',
                'options'       => array(
                    'route'    => '/place',
                    'defaults' => array(
                        'controller' => 'Brainworkers\Controller\Place',
                        'action'     => 'list',
                    ),
                ),
                'may_terminate' => true,
                'child_routes'  => array(
                    'add'    => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/add',
                            'defaults' => array(
                                'controller' => 'Brainworkers\Controller\Place',
                                'action'     => 'add',
                            ),
                        )
                    ),
                    'show'   => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/show[/:id]',
                            'defaults' => array(
                                'controller' => 'Brainworkers\Controller\Place',
                                'action'     => 'show',
                            ),
                        )
                    ),
                    'edit'   => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/edit/:id',
                            'defaults' => array(
                                'controller' => 'Brainworkers\Controller\Place',
                                'action'     => 'edit',
                            ),
                        )
                    ),
                    'delete' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/delete/:id',
                            'defaults' => array(
                                'controller' => 'Brainworkers\Controller\Place',
                                'action'     => 'delete',
                            ),
                        )
                    ),
                    'list'   => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/list',
                            'defaults' => array(
                                'controller' => 'Brainworkers\Controller\Place',
                                'action'     => 'list',
                            ),
                        )
                    ),
                )
            ),
            'standings' => array(
                'type'          => 'literal',
                'may_terminate' => true,
                'options'       => array(
                    'route'         => '/standings',
                    'may_terminate' => true,
                    'defaults'      => array(
                        'controller' => 'Brainworkers\Controller\Index',
                        'action'     => 'standings',
                    ),
                ),
            ),
            'save-page' => array(
                'type'          => 'literal',
                'may_terminate' => true,
                'options'       => array(
                    'route'         => '/save-page',
                    'may_terminate' => true,
                    'defaults'      => array(
                        'controller' => 'Brainworkers\Controller\Index',
                        'action'     => 'save-page',
                    ),
                ),
            ),
            'randomize' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/randomize[/:count]',
                    'defaults' => array(
                        'controller' => 'Brainworkers\Controller\Answer',
                        'action'     => 'randomize',
                        'count'      => 1
                    ),
                ),
            ),
            'answer'    => array(
                'type'         => 'literal',
                'options'      => array(
                    'route'         => '/answer',
                    'may_terminate' => true,
                    'defaults'      => array(
                        'controller' => 'Brainworkers\Controller\Answer',
                        'action'     => 'index',
                    ),
                ),
                'child_routes' => array(
                    'stream' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/stream[/:from]',
                            'defaults' => array(
                                'controller' => 'Brainworkers\Controller\Answer',
                                'action'     => 'disputable-stream',
                                'from'       => 0
                            ),
                        ),
                    ),
                    'update' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/update[/:id]',
                            'defaults' => array(
                                'controller' => 'Brainworkers\Controller\Answer',
                                'action'     => 'update',
                            ),
                        ),
                    ),
                    'add'    => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/add',
                            'defaults' => array(
                                'controller' => 'Brainworkers\Controller\Answer',
                                'action'     => 'add',
                            ),
                        ),
                    )
                ),
            )
        )
    ),
    'bjyauthorize'    => array(
        'resource_providers' => array(
            'BjyAuthorize\Provider\Resource\Config' => array(
                'home'       => array(),
                'randomize'  => array(),
                'answer'     => array(),
                'resolution' => array(),
                'pages'      => array(),
                'team'       => array(),
                'place'      => array(),
                'standings'  => array(),
                'live'  => array(),
            ),
        ),
        'rule_providers'     => array(
            'BjyAuthorize\Provider\Rule\Config' => array(
                'allow' => array(
                    array(array('user', 'admin', 'operator', 'jury'), 'home'),
                    array(array('admin'), 'randomize'),
                    array(array('operator', 'admin'), 'answer'),
                    array(array('jury', 'admin'), 'resolution'),
                    array(array('admin'), 'pages', 'edit'),
                    array(array('user'), 'team', 'assign'),
                    array(array('user', 'guest'), 'team', 'list'),
                    array(array('user'), 'team', 'get'),
                    array(array('user', 'admin'), 'team', 'add'),
                    array(array('admin'), 'team', 'edit'),
                    array(array('admin'), 'team', 'delete'),
                    array(array('user', 'guest'), 'place', 'list'),
                    array(array('user'), 'place', 'add'),
                    array(array('user'), 'place', 'get'),
                    array(array('admin'), 'place', 'edit'),
                    array(array('admin'), 'place', 'delete'),
                    array(array('admin', 'jury', 'operator'), 'standings'),
                    array(array('admin', 'operator'), 'live'),
                ),
                'deny'  => array(),
            ),
        ),
        'guards'             => array(
            'BjyAuthorize\Guard\Route' => array(
                array('route' => 'home', 'roles' => array('guest', 'user', 'admin', 'operator', 'jury')),
                array('route' => 'doctrine_orm_module_yuml', 'roles' => array('admin')),
                array('route' => 'answer', 'roles' => array('operator')),
                array('route' => 'answer/add', 'roles' => array('operator')),
                array('route' => 'answer/stream', 'roles' => array('jury')),
                array('route' => 'answer/update', 'roles' => array('jury')),
                array('route' => 'standings', 'roles' => array('admin', 'operator', 'jury')),
                array('route' => 'StaticPages', 'roles' => array('guest', 'user', 'admin', 'operator', 'jury')),
                array('route' => 'search', 'roles' => array('guest', 'user', 'admin', 'operator', 'jury')),
                array('route' => 'save-page', 'roles' => array('admin')),
                array('route' => 'refresh-local-id', 'roles' => array('admin')),

                array('route' => 'team/assign-to', 'roles' => array('user')),
                array('route' => 'team/show', 'roles' => array('admin', 'user', 'guest')),
                array('route' => 'team/list', 'roles' => array('admin', 'user', 'guest')),
                array('route' => 'team/add', 'roles' => array('admin', 'user')),
                array('route' => 'team/edit', 'roles' => array('admin', 'user')),
                array('route' => 'team/delete', 'roles' => array('admin', 'user')),

                array('route' => 'place/show', 'roles' => array('admin', 'user', 'guest')),
                array('route' => 'place/list', 'roles' => array('admin', 'user', 'guest')),
                array('route' => 'place/add', 'roles' => array('admin', 'user')),
                array('route' => 'place/edit', 'roles' => array('admin', 'user')),
                array('route' => 'place/delete', 'roles' => array('admin', 'user')),

                array('route' => 'payment/create', 'roles' => array('user')),
                array('route' => 'payment/success', 'roles' => array('user')),
                array('route' => 'payment/fail', 'roles' => array('user')),
                array('route' => 'payment/status', 'roles' => array('guest')),
            )
        )
    ),
    'navigation'      => array(
        'default' => array(
            array(
                'label'    => 'Главная',
                'route'    => 'home',
                'resource' => 'home',
                'order'    => -10
            ),
            array(
                'label' => 'Новости',
                'uri'   => '/news',
                'type'  => 'uri',
                'order' => -9
            ),
            array(
                'label' => 'Приглашение',
                'uri'   => '/tournament',
                'type'  => 'uri',
                'order' => -8
            ),
            array(
                'label'    => 'Пользователи',
                'route'    => 'user/manage/list',
                'resource' => 'uri',
                'order'    => -7,
                'pages'    => array(
                    array(
                        'label'    => 'Add',
                        'route'    => 'user/manage/add',
                        'resource' => 'user',
                    ),
                    array(
                        'label'    => 'Edit',
                        'route'    => 'user/manage/edit',
                        'resource' => 'user',
                    ),
                )
            ),
            array(
                'label'     => 'Команды',
                'route'     => 'team/list',
                'resource'  => 'team',
                'privilege' => 'list',
                'pages'     => array(
                    array(
                        'label'     => 'Добавить',
                        'route'     => 'team/add',
                        'resource'  => 'team',
                        'privilege' => 'add',
                    ),
                    array(
                        'label'     => 'Редактировать',
                        'route'     => 'team/edit',
                        'resource'  => 'team',
                        'privilege' => 'edit',
                        'params'    => array(
                            'id' => 0
                        )
                    ),
                    array(
                        'label'     => 'Профиль',
                        'route'     => 'team/show',
                        'resource'  => 'team',
                        'privilege' => 'show',
                    ),
                )
            ),
            array(
                'label'     => 'Площадки',
                'route'     => 'place/list',
                'resource'  => 'place',
                'privilege' => 'list',
                'pages'     => array(
                    array(
                        'label'     => 'Добавить',
                        'route'     => 'place/add',
                        'resource'  => 'place',
                        'privilege' => 'add',
                    ),
                    array(
                        'label'     => 'Редактировать',
                        'route'     => 'place/edit',
                        'resource'  => 'place',
                        'privilege' => 'edit',
                        'params'    => array(
                            'id' => 0
                        )
                    ),
                    array(
                        'label'     => 'Профиль',
                        'route'     => 'place/show',
                        'resource'  => 'place',
                        'privilege' => 'show',
                    ),
                )
            ),
            array(
                'label'    => 'Результаты',
                'route'    => 'standings',
                'resource' => 'standings',
            ),
            array(
                'label'    => 'Добавить ответы',
                'route'    => 'answer/add',
                'resource' => 'answer'
            ),
            array(
                'label'    => 'Спорные ответы',
                'route'    => 'answer/stream',
                'resource' => 'resolution'
            ),
            array(
                'label'    => 'Трансляция',
                'uri'    => '/live',
                'resource' => 'live'
            ),
            array(
                'label' => 'О проекте',
                'uri'   => '/about',
                'type'  => 'uri',
            ),
        )
    ),
    'service_manager' => array(
        'factories' => array(
            'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
            'page-cache' => function () {
                $cache = \Zend\Cache\StorageFactory::factory(
                    array(
                         'adapter' => 'apc',
                         'plugins' => array(
                             'exception_handler' => array('throw_exceptions' => false),
                         ),
                    )
                );

                return $cache;
            }
        ),
    ),
    'form_elements'   => array(
        'invokables' => array(
            'TeamForm'         => 'Brainworkers\Form\Team',
            'PlaceForm'        => 'Brainworkers\Form\Place',
            'LocationFieldset' => 'Brainworkers\Form\LocationFieldset',
            'PlayerFieldset'   => 'Brainworkers\Form\PlayerFieldset',
            'TeamFieldset'     => 'Brainworkers\Form\TeamFieldset',
        ),
        'factories'  => array(

            'AnswerFieldset' => function ($sm) {
                return new \Brainworkers\Form\AnswerFieldset($sm->getServiceLocator()->get('doctrine.entity_manager.orm_default'));
            },
            'AnswerForm'     => function ($sm) {
                return new \Brainworkers\Form\AnswerForm($sm->getServiceLocator()->get('doctrine.entity_manager.orm_default'));
            }
        ),
    ),
    'controllers'     => array(
        'invokables' => array(
            'Brainworkers\Controller\Index'   => 'Brainworkers\Controller\IndexController',
            'Brainworkers\Controller\Answer'  => 'Brainworkers\Controller\AnswerController',
            'Brainworkers\Controller\Team'    => 'Brainworkers\Controller\TeamController',
            'Brainworkers\Controller\Place'   => 'Brainworkers\Controller\PlaceController',
            'Brainworkers\Controller\Payment' => 'Brainworkers\Controller\PaymentController',
        ),
    ),
    'view_manager'    => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/500',
        'template_map'             => array(
            'layout/layout' => __DIR__ . '/../view/layout/default.phtml',
            'error/404'     => __DIR__ . '/../view/error/404.phtml',
            'error/500'     => __DIR__ . '/../view/error/500.phtml',
            'error/index'   => __DIR__ . '/../view/error/index.phtml',
            'error/403'     => __DIR__ . '/../view/error/403.phtml',
        ),
        'template_path_stack'      => array(
            __DIR__ . '/../view',
        ),
        'strategies'               => array(
            'ViewJsonStrategy',
        ),
    ),
    'doctrine'        => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'apc',
                'paths' => array(
                    __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity'
                ),
            ),
            'orm_default'             => array(
                'drivers' => array(
                    __NAMESPACE__ => __NAMESPACE__ . '_driver'
                )
            )
        )
    )
);
