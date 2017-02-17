<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;

/**
 * PlasmidRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PlasmidRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllForUser(User $user)
    {
        $query = $this->createQueryBuilder('plasmid')
            ->leftJoin('plasmid.team', 'team')
            ->leftJoin('team.members', 'members')
            ->where('members = :user')
                ->setParameter('user', $user)
            ->getQuery();

        return $query->getResult();
    }

    public function findOneWithAll($id) {
        $query = $this->createQueryBuilder('plasmid')
            ->leftJoin('plasmid.strainPlasmids', 'strainPlasmids')
                ->addSelect('strainPlasmids')
            ->leftJoin('strainPlasmids.gmoStrain', 'gmoStrain')
                ->addSelect('gmoStrain')
            ->leftJoin('gmoStrain.tubes', 'tubes')
                ->addSelect('tubes')
            ->leftJoin('tubes.project', 'project')
                ->addSelect('project')
            ->leftJoin('project.members', 'members')
                ->addSelect('members')
            ->leftJoin('plasmid.primers', 'primers')
                ->addSelect('primers')
            ->where('plasmid.id = :id')
                ->setParameter('id', $id)
            ->getQuery();

        return $query->getOneOrNullResult();
    }
}
