<?php 
return array(
    'modules' => array(
        'StaticPages',
//        'BjyProfiler',
//        'ZendDeveloperTools',
        'DoctrineModule',
        'DoctrineORMModule',
//        'ZfcTwitterBootstrap',
        'BjyAuthorize',
        'ZfcBase',
        'ZfcUser',
        'ZfcUserDoctrineORM',
        'User',
        'Brainworkers',

    ),
    'module_listener_options' => array(
        'config_glob_paths'    => array(
            'config/autoload/{,*.}{global,local}.php',
        ),
        'module_paths' => array(
            './module',
            './vendor',
        ),
    ),
);
