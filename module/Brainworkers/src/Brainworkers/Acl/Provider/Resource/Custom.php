<?php

namespace Brainworkers\Acl\Provider\Resource;

use BjyAuthorize\Provider\Resource\ProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Custom implements ProviderInterface, ServiceLocatorAwareInterface
{
    /** @var ServiceLocatorInterface */
    protected $serviceLocator;

    /** @var \Zend\Permissions\Acl\Resource\ResourceInterface[] */
    protected $resources = array();

    protected $config = array(
        'home'       => array(),
        'randomize'  => array(),
        'answer'     => array(),
        'resolution' => array(),
        'pages'      => array(),
        'team'       => array(),
        'Brainworkers\Acl\Resource\Team' => array(),
        'place'      => array(),
    );

    /** @return \Zend\Permissions\Acl\Resource\ResourceInterface[] */
    public function getResources()
    {
        foreach ($this->config as $k => $v) {
//            if ($this->serviceLocator->has($k)) {
//                $this->resources[$this->serviceLocator->get($k)] = $v;
//            } else {
                $this->resources[$k] = $v;
//            }
        }

        return $this->resources;
    }

    /** @param ServiceLocatorInterface $serviceLocator */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /** @return ServiceLocatorInterface */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}