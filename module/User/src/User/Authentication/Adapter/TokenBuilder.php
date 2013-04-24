<?php

namespace User\Authentication\Adapter;

use User\Entity\Token;
use Zend\Authentication\Storage\Session;
use Zend\Http\Header\SetCookie;
use Zend\Http\Header\Location;
use Zend\Http\Request as HttpRequest;
use Zend\Http\Response as HttpResponse;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\Stdlib\DateTime;
use ZfcUser\Authentication\Adapter\AbstractAdapter;
use Zend\ServiceManager\ServiceManager;
use ZfcUser\Authentication\Adapter\AdapterChainEvent;
use Zend\Authentication\Result;

class TokenBuilder extends AbstractAdapter implements ServiceManagerAwareInterface
{
    /** @var ServiceManager */
    private $serviceManager;

    /** @var \Doctrine\ORM\EntityManager */
    private $entityManager;

    public function authenticate(AdapterChainEvent $e)
    {
        if ($this->isSatisfied()) {
            $request = $e->getRequest();

            if ($request instanceof HttpRequest) {

                $token = new Token();
                $token->setToken(hash('sha512', uniqid('', true)));

                $expire = new DateTime();
                $expire->add(\DateInterval::createFromDateString('+1 month'));

                $token->setExpire($expire);

                $user = $this->getEntityManager()->find('User\Entity\User', $e->getIdentity());

                $token->setUser($user);

                $this->getEntityManager()->persist($token);
                $this->getEntityManager()->flush();

                setcookie('token', $token->getToken());

//                $response = $e->getParam('response');
//                $response->setStatusCode(302);
//                $response->getHeaders()->addHeader(new SetCookie('token', $token->getToken()));
//                $response->getHeaders()->addHeader(
//                    Location::fromString('location:' . $this->serviceManager->get('router')->assemble(
//                        array(),
//                        array('name' => $this->serviceManager->get('zfcuser_module_options')->getLoginRedirectRoute())
//                    ))
//                );

                return true;
            }
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