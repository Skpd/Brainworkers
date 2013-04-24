<?php

namespace User\Repository;

use Doctrine\ORM\EntityRepository;

class User extends EntityRepository
{
    public function getRandomJury()
    {
        $users = $this->getEntityManager()
            ->createQuery('SELECT u FROM User\Entity\User u JOIN u.userRoles r WHERE r.id IN (:roles)')
            ->setParameter('roles',array('admin', 'jury'))->getArrayResult();

        $random = $users[array_rand($users, 1)];

        return $this->find($random['id']);
    }
}