<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;

/**
 * ProjectRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProjectRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllAuthorizedForCurrentUser(User $user)
    {
        $query = $this->createQueryBuilder('project')
            ->leftJoin('project.members', 'members')
            ->leftJoin('project.administrators', 'administrators')
                ->addSelect('administrators')
            ->leftJoin('project.team', 'team')
            ->leftJoin('team.administrators', 'teamadministrators')
            ->where('members = :user')
            ->orWhere('teamadministrators = :user')
                ->setParameter('user', $user)
            ->getQuery();

        return $query->getResult();
    }

    public function findOneWithAdminsMembers($id)
    {
        $query = $this->createQueryBuilder('p')
            ->leftJoin('p.members', 'm')
                ->addSelect('m')
            ->leftJoin('p.administrators', 'a')
                ->addSelect('a')
            ->where('p.id = :id')
                ->setParameter('id', $id)
            ->getQuery();

        return $query->getSingleResult();
    }
}
