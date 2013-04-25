<?php

namespace Brainworkers\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;

class Team extends EntityRepository
{
    public function getTotalRecordsCount()
    {
        return $this->createQueryBuilder('team')->select('COUNT(team)')->getQuery()->getSingleScalarResult();
    }

    public function getList($limit = null, $skip = null, array $order = array())
    {
        $qb = $this->createQueryBuilder('team');
        $qb->select(
            'team.id AS id',
            'team.name AS team_name',
            'city.name AS city_name',
            'country.name AS country_name',
            "CONCAT(CONCAT(CONCAT(CONCAT(player.surname, ' '), player.name), ' '), player.patronymic) AS captain_name",
            'team.payed AS payed'
        );
        $qb->leftJoin('team.city', 'city')
            ->leftJoin('city.country', 'country')
            ->leftJoin('team.players', 'player', Expr\Join::WITH, 'player.flag = 1');

        $qb->groupBy('id');

        if (!empty($order)) {
            foreach ($order as $field => $direction) {

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
                    case 'captain_name':
                        $qb->orderBy('player.surname', $direction);
                        $qb->addOrderBy('player.name', $direction);
                        $qb->addOrderBy('player.patronymic', $direction);
                        break;
                    case 'function':
                        $qb->orderBy('team.payed', $direction);
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