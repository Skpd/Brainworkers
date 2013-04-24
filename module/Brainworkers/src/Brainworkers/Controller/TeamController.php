<?php

namespace Brainworkers\Controller;

use Brainworkers\Entity\Team;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use DoctrineORMModule\Form\Annotation\AnnotationBuilder;
use Zend\Crypt\Password\Bcrypt;
use Zend\Http\PhpEnvironment\RemoteAddress;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class TeamController
 * @method \Zend\Http\Request getRequest
 * @method \ZfcUser\Controller\Plugin\ZfcUserAuthentication zfcUserAuthentication
 *
 * @package Brainworkers\Controller
 */
class TeamController extends AbstractActionController
{
    /** @var \Doctrine\ORM\EntityManager */
    private $entityManager;

    /** @var \Zend\Form\Form */
    private $form;

    public function assignToAction()
    {
        /** @var $place \Brainworkers\Entity\Place */
        $place = $this->getEntityManager()->find('Brainworkers\Entity\Place', $this->params()->fromRoute('placeId', null));

        /** @var $team \Brainworkers\Entity\Team */
        $team = $this->getEntityManager()->getRepository('Brainworkers\Entity\Team')->findOneBy(array('owner' => $this->zfcUserAuthentication()->getIdentity()));

        if (!empty($team) && !empty($place)) {
            if ($this->isAllowed('team', 'assign')) {
                if ($place->getTeams()->count() < $place->getTeamsMax()) {
                    $place->getTeams()->add($team);
                    $team->setPlace($place);

                    $this->getEntityManager()->persist($place);
                    $this->getEntityManager()->persist($team);
                    $this->getEntityManager()->flush();

                    $this->flashMessenger()->addSuccessMessage('Team assigned successfully');
                } else {
                    $this->flashMessenger()->addErrorMessage('Limit reached.');
                }
            } else {
                $this->flashMessenger()->addErrorMessage('Access denied.');
            }
        } else {
            $this->flashMessenger()->addErrorMessage('Team or place not found.');
        }

        $this->redirect()->toRoute('team/list');
    }

    public function showAction()
    {
        $id     = $this->getEvent()->getRouteMatch()->getParam('id', null);
        $entity = $this->getEntityManager()->find('Brainworkers\Entity\Team', $id);

        return array(
            'team' => $entity
        );
    }

    public function listAction()
    {
//        \Zend\Debug\Debug::dump(
//            $this->getEntityManager()->getRepository('Brainworkers\Entity\Team')
//                ->createQueryBuilder('t')
//                ->join('t.owner', 'u')
//                ->addSelect('u.id-:userId AS HIDDEN isOwner')
//                ->orderBy('isOwner')
//                ->setParameter('userId', $this->zfcUserAuthentication()->getIdentity()->getId())
//                ->getQuery()->getSQL()
//        );
//        return new ViewModel(
//            array(
//                 'teams' => $this->getEntityManager()->getRepository('Brainworkers\Entity\Team')
//                     ->findAll()
//            )
//        );
        return new ViewModel(
            array(
                 'teams' => $this->getEntityManager()->getRepository('Brainworkers\Entity\Team')
                     ->createQueryBuilder('t')
//                     ->join('t.owner', 'u')
//                     ->join('t.city', 'city')
//                     ->join('t.country', 'country')
//                     ->addSelect('u.id-:userId AS HIDDEN isOwner')
//                     ->orderBy('isOwner')
//                     ->setParameter('userId', $this->zfcUserAuthentication()->getIdentity()->getId())
                     ->getQuery()->getResult()
            )
        );
    }

    public function addAction()
    {
        $form = $this->getForm();
        $form->bind(new Team);

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();

            $form->setData($data);

            if ($form->isValid()) {

                /** @var $entity \Brainworkers\Entity\Team */
                $entity = $form->getData();

                $remote = new RemoteAddress;
                $entity->setIp($remote->getIpAddress());

                $entity->setPayed(false);

                if ($entity->getCity()) {
                    $entity->setRegion($entity->getCity()->getRegion());
                    $entity->setCountry($entity->getCity()->getCountry());
                }

                $entity->setOwner($this->zfcUserAuthentication()->getIdentity());

                $this->entityManager->persist($entity);
                $this->entityManager->flush();

                $this->flashMessenger()->addSuccessMessage('Team created successfully');
                $this->redirect()->toRoute('team/list');
            }
        }

        $formView = new ViewModel();
        $formView->setTemplate('brainworkers/team/form');
        $formView->setVariable('form', $form);

        return new ViewModel(array('form' => $formView));
    }

    public function editAction()
    {
        $id     = $this->getEvent()->getRouteMatch()->getParam('id', null);
        $entity = $this->getEntityManager()->find('Brainworkers\Entity\Team', $id);

        if (empty($entity)
            || (!$this->isAllowed('team', 'edit')
                && (!$entity->getOwner() || $entity->getOwner()->getId() != $this->zfcUserAuthentication()->getIdentity()->getId()))
        ) {
            $this->flashMessenger()->addErrorMessage('Team not found');
            $this->redirect()->toRoute('team/list');
        }

        $form = $this->getForm();
        $form->bind($entity);

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();

            $form->setData($data);

            if ($form->isValid()) {

                $entity = $form->getData();

                if ($entity->getCity()) {
                    $entity->setRegion($entity->getCity()->getRegion());
                    $entity->setCountry($entity->getCity()->getCountry());
                }

                $this->entityManager->persist($entity);
                $this->entityManager->flush();

                $this->flashMessenger()->addSuccessMessage('Team updated successfully');
                $this->redirect()->toRoute('team/list');
            }
        }

        $formView = new ViewModel();
        $formView->setTemplate('brainworkers/team/form');
        $formView->setVariable('form', $form);

        return new ViewModel(array('form' => $formView));
    }

    public function deleteAction()
    {
        $id     = $this->getEvent()->getRouteMatch()->getParam('id', null);
        $entity = $this->getEntityManager()->find('Brainworkers\Entity\Team', $id);

        if (empty($entity)
            || (!$this->isAllowed('team', 'edit')
                && (!$entity->getOwner() || $entity->getOwner()->getId() != $this->zfcUserAuthentication()->getIdentity()->getId()))
        ) {
            $this->flashMessenger()->addErrorMessage('Team not found');
            $this->redirect()->toRoute('team/list');
        } else {
            $this->getEntityManager()->remove($entity);
            $this->getEntityManager()->flush();

            $this->flashMessenger()->addSuccessMessage('Team deleted successfully');
        }

        $this->redirect()->toRoute('team/list');
    }

    /**
     * @param \Zend\Form\Form $form
     */
    public function setForm($form)
    {
        $this->form = $form;
    }

    /**
     * @return \Zend\Form\Form
     */
    public function getForm()
    {
        if (null === $this->form) {
            $this->form = $this->getServiceLocator()->get('FormElementManager')->get('TeamForm');
            $this->form->setHydrator(new DoctrineObject($this->getEntityManager(), 'Brainworkers\Entity\Team'));
        }
        return $this->form;
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        if (null === $this->entityManager) {
            $this->entityManager = $this->serviceLocator->get('doctrine.entity_manager.orm_default');
        }

        return $this->entityManager;
    }
}