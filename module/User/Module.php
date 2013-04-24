<?php

namespace User;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ViewHelperProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\EventManager\EventInterface;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface, BootstrapListenerInterface, ViewHelperProviderInterface, ServiceProviderInterface
{
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

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'zfcuser_register_form' => function ($sm) {
                    $options = $sm->get('zfcuser_module_options');
                    $form    = new Form\Register(null, $options, $sm->get('Doctrine\ORM\EntityManager'));
                    $form->setInputFilter(
                        new \ZfcUser\Form\RegisterFilter(
                            new \ZfcUser\Validator\NoRecordExists(
                                array(
                                     'mapper' => $sm->get('zfcuser_user_mapper'),
                                     'key'    => 'email'
                                )),
                            new \ZfcUser\Validator\NoRecordExists(
                                array(
                                     'mapper' => $sm->get('zfcuser_user_mapper'),
                                     'key'    => 'username'
                                )),
                            $options
                        )
                    );

                    return $form;
                },
            )
        );
    }

    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'navigation' => function ($sm) {
                    $auth = $sm->getServiceLocator()->get('BjyAuthorize\Service\Authorize');
                    $role = $auth->getIdentityProvider()->getIdentityRoles();
                    $role = !is_array($role) ? : reset($role);

                    $navigation = $sm->get('Zend\View\Helper\Navigation');
                    $navigation->setAcl($auth->getAcl())->setRole($role);

                    return $navigation;
                }
            )
        );

    }

    public function onBootstrap(EventInterface $e)
    {
        $locator = $e->getApplication()->getServiceManager();

        $service          = $locator->get('user_service');
        $zfcServiceEvents = $locator->get('zfcuser_user_service')->getEventManager();
        $zfcAuthEvents    = $locator->get('ZfcUser\Authentication\Adapter\AdapterChain');

        $zfcServiceEvents->attach('register', array($service, 'onRegister'));
//        $zfcAuthEvents->attach('authenticate.pre', array($service, 'preAuthenticate'));

    }
}