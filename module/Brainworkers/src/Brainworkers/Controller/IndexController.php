<?php

namespace Brainworkers\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Brainworkers\Entity\Team;

class IndexController extends AbstractActionController
{
    /** @var \Doctrine\ORM\EntityManager */
    private $entityManager;

    public function indexAction()
    {

    }

    public function searchAction()
    {
        $type = $this->params('type', null);
        $term = $this->params()->fromQuery('query', '');
        $term = '%' . $term . '%';
        $id   = $this->params()->fromQuery('id', null);

        switch ($type) {
            case 'country';
            case 'region';
            case 'city';
                $entity       = 'Brainworkers\Entity\\' . ucfirst($type);
                $searchField  = array('name');
                $resultMethod = 'getName';
                break;

            case 'user':
                $entity      = 'User\Entity\User';
                $searchField = array('name', 'surname', 'patronymic');
                $resultMethod = 'getDisplayName';
                break;

            default:
                return false;
                break;
        }

        $builder = $this->getEntityManager()->getRepository($entity)->createQueryBuilder('e');

        if (!empty($id)) {
            $builder->where('e.id = ?1')->setParameter(1, $id);
        } else {
            foreach ($searchField as $field) {
                $builder->orWhere('e.' . $field . ' LIKE :' . $field);
                $builder->setParameter($field, $term);
            }
        }

        $view = $this->acceptableViewModelSelector(
            array('Zend\View\Model\ViewModel' => array('text/html'), 'Zend\View\Model\JsonModel' => array('application/json'))
        );

        $result = array();

        foreach ($builder->getQuery()->getResult() as $entity) {
            $result[] = array(
                'id'   => $entity->getId(),
                'name' => $entity->{$resultMethod}(),
            );
        }

        $view->setVariable('result', $result);

        return $view;
    }

    public function savePageAction()
    {
        /** @var $resolver \Zend\View\Resolver\TemplatePathStack */
        $resolver = $this->getEvent()->getApplication()->getServiceManager()->get('Zend\View\Resolver\TemplatePathStack');
        $filePath = $resolver->resolve($this->params()->fromPost('page'));
        $content  = $this->params()->fromPost('content');

        if (!empty($filePath) && !empty($content)) {
            file_put_contents($filePath, $content);
        }

        return false;
    }

    public function standingsAction()
    {
        return array(
            'teams'     => $this->getEntityManager()->createQuery(
                'SELECT t, SUM(a.resolution) AS HIDDEN points FROM Brainworkers\Entity\Team t
                 LEFT JOIN t.answers a
                 GROUP BY t.id
                 ORDER BY points DESC
                '
            )->getResult(),
            'questions' => $this->getEntityManager()->getRepository('Brainworkers\Entity\Question')->findAll()
        );
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