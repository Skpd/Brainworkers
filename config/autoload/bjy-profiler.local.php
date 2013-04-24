<?php

$dbParams = array(
    'database' => 'brainworkers',
    'username' => 'root',
    'password' => 'yt3frjyyj',
    'hostname' => 'localhost',
    // buffer_results - only for mysqli buffered queries, skip for others
    'options'  => array('buffer_results' => true)
);

return array(
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => function ($sm) use ($dbParams) {
                $adapter = new BjyProfiler\Db\Adapter\ProfilingAdapter(
                    array(
                        'driver'   => 'pdo',
                        'dsn'      => 'mysql:dbname=' . $dbParams['database'] . ';host=' . $dbParams['hostname'],
                        'database' => $dbParams['database'],
                        'username' => $dbParams['username'],
                        'password' => $dbParams['password'],
                        'hostname' => $dbParams['hostname'],
                    )
                );

                $adapter->setProfiler(new BjyProfiler\Db\Profiler\Profiler);
                if (isset($dbParams['options']) && is_array($dbParams['options'])) {
                    $options = $dbParams['options'];
                } else {
                    $options = array();
                }
                $adapter->injectProfilingStatementPrototype($options);

                return $adapter;
            },
        ),
    ),
);