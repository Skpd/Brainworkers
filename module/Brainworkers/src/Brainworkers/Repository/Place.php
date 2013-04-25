<?php

namespace Brainworkers\Repository;

use Doctrine\ORM\EntityRepository;

class Place extends EntityRepository
{

    public function getTotalRecordsCount()
    {
        return $this->createQueryBuilder('place')->select('COUNT(place)')->getQuery()->getSingleScalarResult();
    }

    public function getList($limit = null, $skip = null, array $order = array())
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select(
            'place.id AS id',
            'city.name AS city_name',
            'country.name AS country_name',
            "CONCAT(CONCAT(CONCAT(CONCAT(user.surname, ' '), user.name), ' '), user.patronymic) AS user_name",
            'COUNT(teams) AS teams_count',
            "CONCAT(CONCAT(COUNT(teams),' / '), place.teamsMax) AS team"
        );
        $qb->from('Brainworkers\Entity\Place', 'place')
            ->leftJoin('place.city', 'city')
            ->leftJoin('city.country', 'country')
            ->leftJoin('place.owner', 'user')
            ->leftJoin('place.teams', 'teams');

        $qb->groupBy('id');

        if (!empty($order)) {
            foreach ($order as $field => $direction) {
                $qb->orderBy('place.teamsMax', $direction);
                switch ($field) {
                    default:
                    case 'id':
                        $qb->orderBy('id', $direction);
                        break;
                    case 'city_name':
                        $qb->orderBy('city.name', $direction);
                        break;
                    case 'country_name':
                        $qb->orderBy('country.name', $direction);
                        break;
                    case 'user_name':
                        $qb->orderBy('user.surname', $direction);
                        $qb->addOrderBy('user.name', $direction);
                        $qb->addOrderBy('user.patronymic', $direction);
                        break;
                    case 'team':
                        $qb->orderBy('teams_count', $direction);
                        $qb->addOrderBy('place.teamsMax', $direction);
                        break;
                }
            }
        }

        $query = $qb->getQuery();

        if ($limit !== null) {
            $query->setMaxResults($limit);
        }

        if ($skip !== null) {
            $query->setFirstResult($skip);
        }

        return $query->getArrayResult();
    }
}