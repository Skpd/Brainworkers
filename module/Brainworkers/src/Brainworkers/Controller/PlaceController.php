<?php

namespace Brainworkers\Controller;

use Brainworkers\Entity\Place;
use Brainworkers\Entity\Team;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use DoctrineORMModule\Form\Annotation\AnnotationBuilder;
use Zend\Crypt\Password\Bcrypt;
use Zend\Http\PhpEnvironment\RemoteAddress;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

/**
 * @method \Zend\Http\Request getRequest
 * @method \ZfcUser\Controller\Plugin\ZfcUserAuthentication zfcUserAuthentication
 * @package Brainworkers\Controller
 */
class PlaceController extends AbstractActionController
{
    /** @var \Doctrine\ORM\EntityManager */
    private $entityManager;

    /** @var \Zend\Form\Form */
    private $form;

    public function showAction()
    {
        $id     = $this->getEvent()->getRouteMatch()->getParam('id', null);
        $entity = $this->getEntityManager()->find('Brainworkers\Entity\Place', $id);

        return array(
            'place' => $entity
        );
    }

    public function listAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            /** @var $repository \Brainworkers\Repository\Place */
            $repository = $this->getEntityManager()->getRepository('Brainworkers\Entity\Place');

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
                     'places'               => $repository->getList($limit, $skip, $order),
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
        $form->bind(new Place);

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();

            $form->setData($data);

            if ($form->isValid()) {

                /** @var $entity Place */
                $entity = $form->getData();

                if ($entity->getTeams()->count() > $entity->getTeamsMax()) {
                    $form->get('teams')->setMessages(array('Too much teams.'));
                } else {
                    $remote = new RemoteAddress;
                    $entity->setIp($remote->getIpAddress());

                    $entity->setOwner($this->zfcUserAuthentication()->getIdentity());

                    $this->entityManager->persist($entity);
                    $this->entityManager->flush();

                    $this->flashMessenger()->addSuccessMessage('Place updated successfully');
                    $this->redirect()->toRoute('place/list');
                }
            }
        }

        $formView = new ViewModel();
        $formView->setTemplate('brainworkers/place/form');
        $formView->setVariable('form', $form);

        return new ViewModel(array('form' => $formView));
    }

    public function editAction()
    {
        $id     = $this->getEvent()->getRouteMatch()->getParam('id', null);
        $entity = $this->getEntityManager()->find('Brainworkers\Entity\Place', $id);

        if (empty($entity)
            || (!$this->isAllowed('team', 'edit')
                && (!$entity->getOwner() || $entity->getOwner()->getId() != $this->zfcUserAuthentication()->getIdentity()->getId()))
        ) {
            $this->flashMessenger()->addErrorMessage('Place not found');
            $this->redirect()->toRoute('place/list');
        }

        $form = $this->getForm();
        $form->bind($entity);

//        $availableTeams = array();
//
//        foreach ($entity->getTeams() as $team) {
//            $availableTeams[$team->getId()] = $team->getName();
//        }
//
//        $options = $form->get('teams')->getProxy()->getValueOptions();
//        foreach ($options as $option) {
//            if (isset($options['value'])) {
//                $availableTeams[$option['value']] = $option['label'];
//            }
//        }


//        $form->get('teams')->setOptions(array('options' => $availableTeams));

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();

            $form->setData($data);

            if ($form->isValid()) {
                /** @var $entity Place */
                $entity = $form->getData();

                if ($entity->getTeams()->count() > $entity->getTeamsMax()) {
                    $form->get('teams')->setMessages(array('Too much teams.'));
                } else {
                    $this->entityManager->persist($entity);
                    $this->entityManager->flush();

                    $this->flashMessenger()->addSuccessMessage('Place updated successfully');
                    $this->redirect()->toRoute('place/list');
                }
            }
        }

        $formView = new ViewModel();
        $formView->setTemplate('brainworkers/place/form');
        $formView->setVariable('form', $form);

        return new ViewModel(array('form' => $formView));
    }

    public function deleteAction()
    {
        $id     = $this->getEvent()->getRouteMatch()->getParam('id', null);
        $entity = $this->getEntityManager()->find('Brainworkers\Entity\Place', $id);

        if (empty($entity)
            || (!$this->isAllowed('team', 'edit')
                && (!$entity->getOwner() || $entity->getOwner()->getId() != $this->zfcUserAuthentication()->getIdentity()->getId()))
        ) {
            $this->flashMessenger()->addErrorMessage('Place not found');
        } else {
            $this->getEntityManager()->remove($entity);
            $this->getEntityManager()->flush();

            $this->flashMessenger()->addSuccessMessage('Place deleted successfully');
        }

        $this->redirect()->toRoute('place/list');
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
            $this->form = $this->getServiceLocator()->get('FormElementManager')->get('PlaceForm');
            $this->form->setHydrator(new DoctrineObject($this->getEntityManager(), 'Brainworkers\Entity\Place'));
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