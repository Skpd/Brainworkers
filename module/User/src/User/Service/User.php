<?php

namespace User\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use Doctrine\Common\Collections\Arraycollection;

class User implements ServiceManagerAwareInterface
{
    /**
     * @var ServiceManager
     */
    private $serviceManager;

    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    public function onRegister($e)
    {
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->serviceManager->get('doctrine.entity_manager.orm_default');

        /** @var $user \User\Entity\User */
        $user = $e->getParam('user');
        /** @var $form \Zend\Form\Form */
        $form = $e->getParam('form');

        $defaultRole = $em->find('User\Entity\Role', 'user');

        $user->addUserRoles(new Arraycollection(array($defaultRole)));

        $user->setSalt($this->generateSalt());
        $user->setPassword(md5(md5($form->get('password')->getValue()) . $user->getSalt()));

        $user->setCity($em->find('Brainworkers\Entity\City', $user->getCity()));
        $user->setRegion($user->getCity()->getRegion());
        $user->setCountry($user->getCity()->getCountry());
    }

    public function generateSalt($n = 3)
    {
        $key     = '';
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyz.,*_-=+';
        $counter = strlen($pattern) - 1;

        for ($i = 0; $i < $n; $i++) {
            $key .= $pattern{rand(0, $counter)};
        }

        return $key;
    }
}