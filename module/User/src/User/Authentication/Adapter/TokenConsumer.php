<?php

namespace User\Authentication\Adapter;

use Zend\Http\Request as HttpRequest;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use ZfcUser\Authentication\Adapter\AbstractAdapter;
use Zend\ServiceManager\ServiceManager;
use ZfcUser\Authentication\Adapter\AdapterChainEvent;
use Zend\Authentication\Result;
use Zend\Authentication\Storage\Session;

class TokenConsumer extends AbstractAdapter implements ServiceManagerAwareInterface
{
    /** @var ServiceManager */
    private $serviceManager;

    /** @var \Doctrine\ORM\EntityManager */
    private $entityManager;

    public function authenticate(AdapterChainEvent $e)
    {
        if ($this->isSatisfied()) {
            $storage = $this->getStorage()->read();
            $e->setIdentity($storage['identity'])
                ->setCode(Result::SUCCESS)
                ->setMessages(array('Authentication successful.'));
            return true;
        }

        $request = $e->getRequest();

        if ($request instanceof HttpRequest) {
            $cookie = $request->getCookie()->getArrayCopy();

            if (empty($cookie['token'])) return false;

            /** @var $token \User\Entity\Token */
            $token = $this->getEntityManager()->getRepository('User\Entity\Token')
                ->findOneBy(array('token'  => $cookie['token']));

            if (null === $token || $token->isExpired()) {
                $request->getCookie()->offsetUnset('token');

                if ($token) {
                    $this->getEntityManager()->remove($token);
                    $this->getEntityManager()->flush();
                }

                return false;
            }

            $e->setIdentity($token->getUser()->getId());
            $this->setSatisfied(true);

            $storage = $this->getStorage()->read();
            $storage['identity'] = $e->getIdentity();
            $this->getStorage()->write($storage);
            $e->setCode(Result::SUCCESS)
                ->setMessages(array('Authentication successful.'));

            return true;
        }

        return false;
    }

    public function getStorage()
    {
        if (null === $this->storage) {
            $this->setStorage(new Session('ZfcUser\Authentication\Adapter\Db'));
        }

        return $this->storage;
    }

    /**
     * Set service manager
     *
     * @param ServiceManager $serviceManager
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        if (null === $this->entityManager) {
            $this->entityManager = $this->serviceManager->get('doctrine.entity_manager.orm_default');
        }

        return $this->entityManager;
    }
}