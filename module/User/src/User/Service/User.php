<?php

namespace User\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\Mail;

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

    public function resetPassword(\User\Entity\User $user)
    {
        $password = $this->generateSalt(8);
        $user->setSalt($this->generateSalt());
        $user->setPassword(md5(md5($password) . $user->getSalt()));

        $em = $this->serviceManager->get('doctrine.entity_manager.orm_default');

        $em->persist($user);
        $em->flush();

        $mail = new Mail\Message();
        $mail->setBody('New password: ' . $password);
        $mail->setTo($user->getEmail(), $user->getDisplayName());
        $mail->setFrom('no-reply@brainworkers.ru');
        $mail->setSubject('Password Reset');

        $transport = new Mail\Transport\Sendmail();
        $transport->send($mail);
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