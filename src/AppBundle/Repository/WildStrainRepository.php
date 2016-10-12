<?php

namespace AppBundle\Repository;
use AppBundle\Entity\User;

/**
 * WildRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class WildStrainRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllUsualName()
    {
        $query = $this->createQueryBuilder('gmo')
            ->select('gmo.usualName')
            ->orderBy('gmo.usualName', 'ASC')
            ->distinct()
            ->getQuery();

        return $query->getArrayResult();
    }

    public function findAllForUser(User $user, $limit = 10)
    {
        $query = $this->createQueryBuilder('strain')
            ->leftJoin('strain.tubes', 'tubes')
            ->leftJoin('tubes.box', 'boxes')
            ->leftJoin('boxes.project', 'projects')
            ->leftJoin('projects.teams', 'teams')
            ->leftJoin('teams.members', 'members')
            ->where('members = :user')
                ->setParameter('user', $user)
            ->distinct(true)
            ->orderBy('strain.id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery();

        return $query->getArrayResult();
    }

    public function findOneWithAll($strain)
    {
        $query = $this->createQueryBuilder('wild')
            ->leftJoin('wild.species', 'species')
                ->addSelect('species')
            ->leftJoin('species.genus', 'genus')
                ->addSelect('genus')
            ->leftJoin('wild.biologicalOriginCategory', 'category')
                ->addSelect('category')
            ->leftJoin('wild.tubes', 'tubes')
                ->addSelect('tubes')
            ->leftJoin('tubes.box', 'boxes')
                ->addSelect('boxes')
            ->leftJoin('boxes.project', 'projects')
                ->addSelect('projects')
            ->leftJoin('boxes.type', 'types')
                ->addSelect('types')
            ->where('wild = :strain')
                ->setParameter('strain', $strain)
            ->getQuery();

        return $query->getOneOrNullResult();
    }
}
