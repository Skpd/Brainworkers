<?php

namespace User\Controller;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use DoctrineORMModule\Form\Annotation\AnnotationBuilder;
use User\Entity\User;
use Zend\Crypt\Password\Bcrypt;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ManagementController extends AbstractActionController
{
    /** @var \Doctrine\ORM\EntityManager */
    private $entityManager;

    public function listAction()
    {
        return new ViewModel(array('users' => $this->getEntityManager()->getRepository('User\Entity\User')->findAll()));
    }

    public function addAction()
    {
        $builder = new AnnotationBuilder($this->getEntityManager());

        /** @var $form \Zend\Form\Form */
        $form = $builder->createForm('User\Entity\User');
        $form->setHydrator(new DoctrineObject($this->getEntityManager(), 'User\Entity\User'));
        $form->bind(new User);

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();

            $form->setData($data);

            if ($form->isValid()) {
                $entity = $form->getData();

                if (!empty($data['password'])) {
                    $entity->setSalt(substr(uniqid('', true), 0, 3));
                    $entity->setPassword(md5(md5($data['password']) . $entity->getSalt()));
                }

                $this->entityManager->persist($entity);
                $this->entityManager->flush();

                $this->flashMessenger()->addSuccessMessage('User created successfully');
                $this->redirect()->toRoute('user/list');
            }
        }

        $form->get('password')->setValue('');

        $formView = new ViewModel();
        $formView->setTemplate('user/management/form');
        $formView->setVariable('form', $form);

        return new ViewModel(array('form' => $formView));
    }

    public function editAction()
    {
        $id = $this->getEvent()->getRouteMatch()->getParam('id', null);
        $entity = $this->getEntityManager()->find('User\Entity\User', $id);

        if (empty($entity)) {
            $this->flashMessenger()->addErrorMessage('User not found');
            $this->redirect()->toRoute('user/list');
        }

        $builder = new AnnotationBuilder($this->getEntityManager());

        /** @var $form \Zend\Form\Form */
        $form = $builder->createForm('User\Entity\User');
        $form->setHydrator(new DoctrineObject($this->getEntityManager(), 'User\Entity\User'));
        $form->bind($entity);

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();

            if (empty($data['password'])) {
                $data['password'] = $entity->getPassword();
            } else {
                $bCrypt = new Bcrypt;
                $bCrypt->setCost($this->serviceLocator->get('zfcuser_module_options')->getPasswordCost());
                $data['password'] = $bCrypt->create($data['password']);
            }

            $form->setData($data);

            if ($form->isValid()) {
                $this->entityManager->persist($form->getData());
                $this->entityManager->flush();

                $this->flashMessenger()->addSuccessMessage('User updated successfully');
                $this->redirect()->toRoute('user/list');
            }
        }

        $form->get('password')->setValue('');

        $formView = new ViewModel();
        $formView->setTemplate('user/management/form');
        $formView->setVariable('form', $form);

        return new ViewModel(array('form' => $formView));
    }

    public function deleteAction()
    {
        $id = $this->getEvent()->getRouteMatch()->getParam('id', null);
        $entity = $this->getEntityManager()->find('User\Entity\User', $id);

        if (!empty($entity)) {
            $this->getEntityManager()->remove($entity);
            $this->getEntityManager()->flush();

            $this->flashMessenger()->addSuccessMessage('User deleted successfully');
        } else {
            $this->flashMessenger()->addErrorMessage('User not found');
        }

        $this->redirect()->toRoute('user/list');
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