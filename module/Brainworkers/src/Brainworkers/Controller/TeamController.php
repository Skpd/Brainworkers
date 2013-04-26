<?php

namespace Brainworkers\Controller;

use Brainworkers\Entity\Team;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use DoctrineORMModule\Form\Annotation\AnnotationBuilder;
use Zend\Crypt\Password\Bcrypt;
use Zend\Http\PhpEnvironment\RemoteAddress;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
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

    public function refreshLocalIdAction()
    {
        /** @var $places \Brainworkers\Entity\Place[] */
        $places = $this->getEntityManager()->getRepository('Brainworkers\Entity\Place')->findAll();

        foreach ($places as $place) {
            foreach ($place->getTeams() as $id => $team) {
                $team->setLocalId($id + 1);
                $this->getEntityManager()->persist($team);
            }
        }

        $this->getEntityManager()->flush();

        return false;
    }

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

                    $this->flashMessenger()->addSuccessMessage("Команда '{$team->getName()}' успешно привязана к площадке");
                } else {
                    $this->flashMessenger()->addErrorMessage('Достигнут лимит команд!');
                }
            } else {
                $this->flashMessenger()->addErrorMessage('Доступ запрещен.');
            }
        } else {
            $this->flashMessenger()->addErrorMessage('Команда или площадка не найдена.');
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
        if ($this->getRequest()->isXmlHttpRequest()) {
            /** @var $repository \Brainworkers\Repository\Team */
            $repository = $this->getEntityManager()->getRepository('Brainworkers\Entity\Team');

            $limit        = $this->getRequest()->getPost('iDisplayLength', 30);
            $skip         = $this->getRequest()->getPost('iDisplayStart', 0);
            $columnsCount = $this->getRequest()->getPost('iColumns', 0);

            $columns = array();
            for ($i = 0; $i < $columnsCount; $i++) {
                $column = $this->getRequest()->getPost('mDataProp_' . $i, null);
                if ($column !== null) {
                    $columns[$i] = $column;
                }
            }

            $order = array();
            for ($i = 0; $i < $columnsCount; $i++) {
                $column = $this->getRequest()->getPost('iSortCol_' . $i, null);
                if ($column !== null && isset($columns[$column])) {
                    $order[$columns[$column]] = $this->getRequest()->getPost('sSortDir_' . $i, null);
                }
            }

            $total = $repository->getTotalRecordsCount();

            return new JsonModel(
                array(
                     'teams'                => $repository->getList($limit, $skip, $order),
                     'iTotalRecords'        => $total,
                     'iTotalDisplayRecords' => $total,
                     'sEcho'                => intval($this->getRequest()->getPost('sEcho', 0))
                )
            );
        } else {
            return new ViewModel();
        }
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

                $this->flashMessenger()->addSuccessMessage('Команда создана');
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
            $this->flashMessenger()->addErrorMessage('Команда не найдена');
            $this->redirect()->toRoute('team/list');
        }

        $form = $this->getForm();
        $form->bind($entity);
        $form->getInputFilter()->get('team')->remove('name');

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

                $this->flashMessenger()->addSuccessMessage('Команда обновлена');
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
        /** @var $entity \Brainworkers\Entity\Team */
        $entity = $this->getEntityManager()->find('Brainworkers\Entity\Team', $id);

        if (empty($entity)
            || (!$this->isAllowed('team', 'edit')
                && (!$entity->getOwner() || $entity->getOwner()->getId() != $this->zfcUserAuthentication()->getIdentity()->getId()))
        ) {
            $this->flashMessenger()->addErrorMessage('Team not found');
            $this->redirect()->toRoute('team/list');
        } else {

            foreach ($entity->getPlayers() as $player) {
                $this->getEntityManager()->remove($player);
            }

            $this->getEntityManager()->remove($entity);
            $this->getEntityManager()->flush();

            $this->flashMessenger()->addSuccessMessage('Команда удалена');
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