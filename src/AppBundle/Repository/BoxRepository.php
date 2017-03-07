<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;

/**
 * BoxRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BoxRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllAuthorizedForCurrentUserWithType(User $user)
    {
        $query = $this->createQueryBuilder('box')
            ->leftJoin('box.project', 'project')
            ->leftJoin('project.members', 'members')
            ->leftJoin('project.team', 'team')
            ->leftJoin('team.administrators', 'administrators')
            ->where('administrators = :user')
            ->orWhere('members = :user')
                ->setParameter('user', $user)
            ->orderBy('box.project', 'ASC')
            ->addOrderBy('box.boxLetter', 'ASC')
            ->getQuery();

        return $query->getResult();
    }

    public function findOneWithProjectTypeTubesStrains($box)
    {
        $query = $this->createQueryBuilder('box')
            ->leftJoin('box.project', 'project')
                ->addSelect('project')
            ->leftJoin('box.tubes', 'tubes')
                ->addSelect('tubes')
            ->leftJoin('tubes.strain', 'strain')
                ->addSelect('strain')
            ->where('box = :box')
                ->setParameter('box', $box)
            ->orderBy('tubes.cell', 'ASC')
            ->getQuery();

        return $query->getOneOrNullResult();
    }
}
