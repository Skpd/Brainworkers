<?php

namespace User;

return array(
    'router'          => array(
        'routes' => array(
            'user' => array(
                'type'          => 'literal',
                'options'       => array(
                    'route'    => '/user',
                    'defaults' => array(
                        'controller' => 'User\Controller\Management',
                        'action'     => 'list',
                    ),
                ),
                'may_terminate' => false,
                'child_routes'  => array(
                    'reset-password'    => array(
                        'type'    => 'literal',
                        'options' => array(
                            'route'    => '/reset-password',
                            'defaults' => array(
                                'controller' => 'User\Controller\User',
                                'action'     => 'reset-password',
                            ),
                        )
                    ),
                    'add'    => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/add',
                            'defaults' => array(
                                'controller' => 'User\Controller\Management',
                                'action'     => 'add',
                            ),
                        )
                    ),
                    'edit'   => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/edit/:id',
                            'defaults' => array(
                                'controller' => 'User\Controller\Management',
                                'action'     => 'edit',
                            ),
                        )
                    ),
                    'delete' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/delete/:id',
                            'defaults' => array(
                                'controller' => 'User\Controller\Management',
                                'action'     => 'delete',
                            ),
                        )
                    ),
                    'list'   => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/list',
                            'defaults' => array(
                                'controller' => 'User\Controller\Management',
                                'action'     => 'list',
                            ),
                        )
                    ),
                )
            ),
        )
    ),
    'controllers'     => array(
        'invokables' => array(
            'User\Controller\Management' => 'User\Controller\ManagementController',
            'User\Controller\User' => 'User\Controller\UserController',
        )
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
    ),
    'form_elements'   => array(
        'invokables' => array(
            'ResetPasswordForm'         => 'User\Form\ResetPassword',
        ),
    ),
    'service_manager' => array(
        'factories'  => array(
            'user-authorization'   => 'User\Navigation\Authorization',
            'user-management'      => 'User\Navigation\Management',
            'zfcuser_auth_service' => function ($sm) {
                $storage = new \ZfcUser\Authentication\Storage\Db();
                $storage->setStorage(new \User\Authentication\Storage\RememberableSession());
                $storage->setServiceManager($sm);

                return new \Zend\Authentication\AuthenticationService(
                    $storage,
                    $sm->get('ZfcUser\Authentication\Adapter\AdapterChain')
                );
            }
        ),
        'invokables' => array(
            'user_service'                              => 'User\Service\User',
            'User\Authentication\Adapter\TokenConsumer' => 'User\Authentication\Adapter\TokenConsumer',
            'User\Authentication\Adapter\TokenBuilder'  => 'User\Authentication\Adapter\TokenBuilder',
            'ZfcUser\Authentication\Adapter\Db'         => 'User\Authentication\Adapter\Db'
        )
    ),
    'zfcuser'         => array(
        'user_entity_class'       => 'User\Entity\User',
        'enable_default_entities' => false,
        'enable_display_name'     => true,
        'logout_redirect_route'   => 'home',
        'login_redirect_route'    => 'home',
    ),
    'bjyauthorize'    => array(
        'identity_provider'  => 'BjyAuthorize\Provider\Identity\AuthenticationIdentityProvider',
        'role_providers'     => array(
            'BjyAuthorize\Provider\Role\ObjectRepositoryProvider' => array(
                'object_manager'    => 'doctrine.entity_manager.orm_default',
                'role_entity_class' => 'User\Entity\Role',
            ),
        ),
        'resource_providers' => array(
            'BjyAuthorize\Provider\Resource\Config' => array(
                'user' => array(),
            ),
        ),
        'rule_providers'     => array(
            'BjyAuthorize\Provider\Rule\Config' => array(
                'allow' => array(
                    array(array('guest'), 'user', 'login'),
                    array(array('guest'), 'user', 'register'),
                    array(array('user'), 'user', 'logout'),

                    array(array('guest', 'user'), 'home'),

                    array(array('admin'), 'user', 'edit'),
                    array(array('admin'), 'user', 'list'),
                    array(array('admin'), 'user', 'delete'),
                    array(array('admin'), 'user', 'add'),
                ),
                'deny'  => array(),
            ),
        ),
        'guards'             => array(
            'BjyAuthorize\Guard\Route' => array(
                array('route' => 'zfcuser', 'roles' => array('user')),
                array('route' => 'zfcuser/logout', 'roles' => array('user', 'admin')),
                array('route' => 'zfcuser/login', 'roles' => array('guest')),
                array('route' => 'user/reset-password', 'roles' => array('guest')),
                array('route' => 'zfcuser/register', 'roles' => array('guest')),
                array('route' => 'user/list', 'roles' => array('admin')),
                array('route' => 'user/add', 'roles' => array('admin')),
                array('route' => 'user/edit', 'roles' => array('admin')),
                array('route' => 'user/delete', 'roles' => array('admin')),
            )
        )
    ),
    'navigation'      => array(
        'user-authorization' => array(
            array(
                'label'     => 'Login',
                'route'     => 'zfcuser/login',
                'resource'  => 'user',
                'privilege' => 'login'
            ),
            array(
                'label'     => 'Logout',
                'route'     => 'zfcuser/logout',
                'resource'  => 'user',
                'privilege' => 'logout'
            ),
            array(
                'label'     => 'Register',
                'route'     => 'zfcuser/register',
                'resource'  => 'user',
                'privilege' => 'register'
            ),
        ),
        'default'            => array(
            array(
                'label'     => 'Пользователи',
                'route'     => 'user/list',
                'resource'  => 'user',
                'privilege' => 'list',
                'pages'    => array(
                    array(
                        'label'    => 'Добавить',
                        'route'    => 'user/add',
                        'resource' => 'user',
                    ),
                    array(
                        'label'    => 'Редактировать',
                        'route'    => 'user/edit',
                        'resource' => 'user',
                    ),
                )
            ),
        ),
    ),
    'view_manager'    => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);