<?php

namespace User\Controller;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use User\Entity\User;
use Zend\Mvc\Controller\AbstractActionController;

class UserController extends AbstractActionController
{
    /** @var \Zend\Form\Form */
    private $form;
    /** @var \Doctrine\ORM\EntityManager */
    private $entityManager;

    public function resetPasswordAction()
    {
        $form = $this->getForm();
        $form->bind(new User);

        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());

            if ($form->isValid()) {
                $entity = $this->getEntityManager()->getRepository('User\Entity\User')->findOneBy(array('email' => $form->getData()->getEmail()));

                if (!$entity) {
                    $this->flashMessenger()->addErrorMessage('Email не найден');

                    $this->redirect()->toRoute('user/reset-password');
                    return false;
                }

                $userService = $this->serviceLocator->get('user_service');

                $userService->resetPassword($entity);

                $this->flashMessenger()->addSuccessMessage('Пароль выслан на email');
                $this->redirect()->toRoute('zfcuser/login');
            }
        }

        return array(
            'form' => $form
        );
    }

    /**
     * @return \Zend\Form\Form
     */
    public function getForm()
    {
        if (null === $this->form) {
            $this->form = $this->getServiceLocator()->get('FormElementManager')->get('ResetPasswordForm');
            $this->form->setHydrator(new DoctrineObject($this->getEntityManager(), 'User\Entity\User'));
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