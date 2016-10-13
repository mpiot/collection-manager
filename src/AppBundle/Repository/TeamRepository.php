<?php

namespace AppBundle\Repository;

/**
 * TeamRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TeamRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllWithMembers()
    {
        $query = $this->createQueryBuilder('team')
            ->leftJoin('team.administrators', 'administrators')
                ->addSelect('administrators')
            ->leftJoin('team.moderators', 'moderators')
                ->addSelect('moderators')
            ->leftJoin('team.members', 'members')
                ->addSelect('members')
            ->orderBy('team.name', 'ASC')
            ->getQuery();

        return $query->getResult();
    }

    public function findOneWithMembers($team)
    {
        $query = $this->createQueryBuilder('team')
            ->leftJoin('team.administrators', 'administrators')
                ->addSelect('administrators')
            ->leftJoin('team.moderators', 'moderators')
                ->addSelect('moderators')
            ->leftJoin('team.members', 'members')
                ->addSelect('members')
            ->where('team = :team')
                ->setParameter('team', $team)
            ->getQuery();

        return $query->getOneOrNullResult();
    }
}
