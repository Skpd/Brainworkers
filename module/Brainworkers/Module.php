<?php

namespace Brainworkers;

use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\Mvc\MvcEvent;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface, BootstrapListenerInterface
{
    /**
     * Listen to the bootstrap event
     *
     * @param EventInterface $e
     *
     * @return array
     */
    public function onBootstrap(EventInterface $e)
    {
        $routes = array('foo/bar', 'foo/baz');

        $app = $e->getApplication();
        $em  = $app->getEventManager();
        $sm  = $app->getServiceManager();

        $em->attach(
            MvcEvent::EVENT_ROUTE,
            function ($e) use ($sm) {
                if (!$e) {
                    return;
                }
                /** @var $e \Zend\Mvc\MvcEvent  */

                $action = $e->getRouteMatch()->getParam('action', null);

                if ($action && $action == 'connecting-the-past') {
                    /** @var $response \Zend\Http\PhpEnvironment\Response */
                    $response = $e->getResponse();
                    $response->setStatusCode(303);
                    $response->getHeaders()->addHeaderLine('location', '/tournament');
                    return $response;
                }

                $route = $e->getRouteMatch()->getMatchedRouteName();
                $cache = $sm->get('page-cache');
                $key   = 'route-cache-' . $route;

                if ($cache->hasItem($key)) {
                    // Handle response
                    $content = $cache->getItem($key);

                    $response = $e->getResponse();
                    $response->setContent($content);

                    return $response;
                }
            },
            -1000 // Late, then routing has happened
        );

        $em->attach(
            MvcEvent::EVENT_RENDER,
            function ($e) use ($sm, $routes) {
                if (!$e) {
                    return;
                }
                $route = $e->getRouteMatch()->getMatchedRouteName();
                if (!in_array($route, $routes)) {
                    return;
                }

                $response = $e->getResponse();
                $content  = $response->getContent();

                $cache = $sm->get('cache-service');
                $key   = 'route-cache-' . $route;
                $cache->setItem($key, $content);
            },
            -1000 // Late, then rendering has happened
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
