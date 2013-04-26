<?php

namespace Brainworkers\Controller;

use Doctrine\DBAL\DBALException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query;
use Zend\Mvc\Controller\AbstractActionController;
use Brainworkers\Entity\Team;
use Brainworkers\Entity\Question;
use Brainworkers\Entity\Answer;
use Zend\Stdlib\ArrayObject;
use Zend\Session\Container;

/**
 * Class AnswerController
 * @method \Zend\Http\Request getRequest()
 * @method \ZfcUser\Controller\Plugin\ZfcUserAuthentication zfcUserAuthentication
 *
 * @package Brainworkers\Controller
 */
class AnswerController extends AbstractActionController
{
    /** @var \Doctrine\ORM\EntityManager */
    private $entityManager;

    public function updateAction()
    {
        $id = $this->params()->fromRoute('id', null);
        /** @var $answer Answer */
        $answer = $this->getEntityManager()->find('Brainworkers\Entity\Answer', $id);

        if (empty($answer)) {
            $this->flashMessenger()->addErrorMessage('Answer not found');
            $this->redirect()->toRoute('/');
        }

        //todo: form / input filter
        $answer->setResolution($this->params()->fromPost('resolution', null));
        $answer->setJury($this->getEntityManager()->find('User\Entity\User', $this->zfcUserAuthentication()->getIdentity()));

        $this->getEntityManager()->persist($answer);

        /** @var $similar Answer[] */
        $similar = $this->getEntityManager()->getRepository('Brainworkers\Entity\Answer')->findBy(
            array('content' => $answer->getContent(), 'question' => $answer->getQuestion())
        );

        foreach ($similar as $similarAnswer) {
            $similarAnswer->setResolution($answer->getResolution());
            $this->getEntityManager()->persist($similarAnswer);
        }

        try {
            $this->getEntityManager()->flush();
        } catch (DBALException $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage() . ' : ' . $e->getPrevious()->getMessage());
            $this->redirect()->toRoute('/');
        }

        return false;
    }

    public function disputableStreamAction()
    {
        $acceptCriteria = array(
            'Zend\View\Model\ViewModel' => array(
                'text/html',
            ),
            'Zend\View\Model\JsonModel' => array(
                'application/json',
            )
        );

        $viewModel = $this->acceptableViewModelSelector($acceptCriteria);

        $query = $this->getEntityManager()->createQuery(
            'SELECT a, q FROM Brainworkers\Entity\Answer a
             JOIN a.question q
             LEFT JOIN a.jury u
             WHERE a.resolution IS NULL AND a.isDisputable = true AND (u.id = :userId OR u IS NULL)
             GROUP BY q, a.content
             ORDER BY a.id ASC'
        )->setParameter('userId', $this->zfcUserAuthentication()->getIdentity())->setMaxResults(30);

        return $viewModel->setVariables(
            array(
                 'answers' => $query->getArrayResult(),
            )
        );
    }

    public function addAction()
    {
        /** @var $session \Zend\Session\AbstractContainer */
        $session = new Container(__CLASS__);

        if ($session->offsetExists('formData')) {
            $data = $session->formData;
        } else {
            $data = new ArrayObject();
        }

        /** @var $form \Brainworkers\Form\AnswerForm */
        $form = $this->getServiceLocator()->get('FormElementManager')->get('AnswerForm');
        $form->bind($data);

        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());

            if ($form->isValid()) {
                /** @var $question Question */
                $question = $this->getEntityManager()->find('Brainworkers\Entity\Question', $data['question']);

                foreach ($data['answers'] as $n => $answer) {
                    /** @var $answer Answer */
                    $answer->setQuestion($question);
                    $answer->setIsDisputable(true);

                    /** @var $teams \Doctrine\Common\Collections\ArrayCollection */
                    $teams = $this->zfcUserAuthentication()->getIdentity()->getPlace()->getTeams();

                    if ($teams->count() < $answer->getLocalId()) {
                        $this->flashMessenger()->addErrorMessage('Команда не найдена');
                        $this->redirect()->toRoute('answer/add');
                        continue;
                    }

                    $answer->setTeam($teams->get($answer->getLocalId() - 1));

                    if ($answer->getIsDisputable()) {
                        /** @var $similar Answer */
                        $similar = $this->getEntityManager()->getRepository('Brainworkers\Entity\Answer')
                            ->findOneBy(array('content' => $answer->getContent(), 'question' => $answer->getQuestion()));

                        if (!empty($similar)) {
                            $answer->setResolution($similar->getResolution());
                            $answer->setJury($similar->getJury());
                        } else {
                            $juryId = $this->getEntityManager()->createQuery(
                                'SELECT u.id, COUNT(a.id) AS num FROM User\Entity\User u
                                 LEFT JOIN Brainworkers\Entity\Answer a WITH (a.jury = u AND a.isDisputable = true)
                                 JOIN u.userRoles r WITH (r.id IN (\'jury\'))
                                 GROUP BY u.id
                                 ORDER BY num ASC'
                            )->setMaxResults(1)->getArrayResult();

                            if (!empty($juryId) && !empty($juryId[0]['id'])) {
                                $jury = $this->getEntityManager()->find('User\Entity\User', $juryId[0]['id']);
                            } else {
                                $jury = $this->getEntityManager()->getRepository('User\Entity\User')->getRandomJury();
                            }

                            $answer->setJury($jury);
                        }
                    }

                    try {
                        $this->getEntityManager()->persist($answer);
                        $this->getEntityManager()->flush();

                        $this->flashMessenger()->addSuccessMessage("Answer '{$answer->getContent()}' was accepted from team '{$answer->getTeam()->getName()}'");
                    } catch (DBALException $e) {
                        if ($e->getPrevious()->getCode() == 23000) {
                            $this->flashMessenger()->addErrorMessage(
                                "Answer '{$answer->getContent()}' on question '{$question->getName()}' already accepted from team '{$answer->getTeam()->getName(
                                )}'"
                            );
                        } else {
                            $this->flashMessenger()->addErrorMessage($e->getMessage() . ' : ' . $e->getPrevious()->getMessage());
                        }

                        $session->formData = $data;
                        $this->redirect()->toRoute('answer/add');
                    }
                }

                $session->offsetUnset('formData');
                $this->redirect()->toRoute('answer/add');
            }
        }

        return array(
            'form' => $form
        );
    }

    public function randomizeAction()
    {
        $count = (int)$this->params()->fromRoute('count', 1);

        for ($i = 0; $i < $count; $i++) {
            $maxId = $this->getEntityManager()
                ->createQuery('SELECT MAX(team.id) FROM Brainworkers\Entity\Team team')
                ->getSingleResult(Query::HYDRATE_SCALAR);

            if (($id = mt_rand(0 - current($maxId), current($maxId))) > 0) {
                /** @var $team Team */
                $team = $this->getEntityManager()->find('Brainworkers\Entity\Team', $id);
            } else {
                $team = new Team();
                $team->setName(uniqid('team_', 1));

                $this->flashMessenger()->addInfoMessage("Created Team '{$team->getName()}'");
            }

            $maxId = $this->getEntityManager()
                ->createQuery('SELECT MAX(question.id) FROM Brainworkers\Entity\Question question')
                ->getSingleResult(Query::HYDRATE_SCALAR);

            if (($id = mt_rand(0 - current($maxId), current($maxId))) > 0) {
                /** @var $question Question */
                $question = $this->getEntityManager()->find('Brainworkers\Entity\Question', $id);
            } else {
                $question = new Question();
                $question->setName(uniqid('question_', 1));

                $team->addQuestions($question);

                $this->flashMessenger()->addInfoMessage("Created Question '{$question->getName()}'");
            }

            if (($id = mt_rand(0, max(count($question->getAnswers()) - 1, 0))) > 0) {
                /** @var $answer Answer */
                $answer = $question->getAnswers()->get($id);

                if ($answer->getIsDisputable()) {
                    $answer->setResolution(mt_rand(0, 1));

                    $this->flashMessenger()->addSuccessMessage("Changed resolution for answer '{$answer->getContent()}'");
                }

            } else {
                $answer = new Answer();
                $answer->setContent(uniqid() . ' ' . uniqid());
                $answer->setIsDisputable(mt_rand(0, 1));

                $question->addAnswers($answer);

                $this->flashMessenger()->addInfoMessage("Created" . ($answer->getIsDisputable() ? ' Disputable ' : ' ') . "Answer '{$answer->getContent()}'");
            }

            $this->getEntityManager()->persist($team);
            $this->getEntityManager()->persist($question);
            $this->getEntityManager()->persist($answer);

            if ($this->getEntityManager()->getUnitOfWork()->size() >= 50) {
                $this->getEntityManager()->flush();
                $this->getEntityManager()->clear();
            }
        }

        $this->getEntityManager()->flush();
        $this->getEntityManager()->clear();

        $this->redirect()->toRoute('home');

        return false;
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