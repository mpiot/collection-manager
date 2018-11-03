<?php

/*
 * Copyright 2016-2018 Mathieu Piot.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Repository;

use App\Entity\Strain;

/**
 * Strain Repository.
 */
class StrainRepository extends \Doctrine\ORM\EntityRepository
{
    public function findOneById($id)
    {
        $query = $this->createQueryBuilder('strain')
            ->leftJoin('strain.strainPlasmids', 'strainPlasmids')
                ->addSelect('strainPlasmids')
            ->leftJoin('strainPlasmids.plasmid', 'plasmid')
                ->addSelect('plasmid')
            ->leftJoin('strain.species', 'species')
                ->addSelect('species')
            ->leftJoin('species.genus', 'genus')
                ->addSelect('genus')
            ->leftJoin('strain.tubes', 'tubes')
                ->addSelect('tubes')
            ->leftJoin('tubes.box', 'boxes')
                ->addSelect('boxes')
            ->where('strain.id = :id')
                ->setParameter('id', $id)
            ->getQuery();

        return $query->getOneOrNullResult();
    }

    public function findParents(Strain $strain)
    {
        $query = $this->createQueryBuilder('strain')
            ->leftJoin('strain.parents', 'parents')
                ->addSelect('parents')
            ->where('strain = :strain')
            ->setParameter('strain', $strain)
            ->getQuery();

        return $query->getOneOrNullResult();
    }

    public function findChildren(Strain $strain)
    {
        $query = $this->createQueryBuilder('strain')
            ->leftJoin('strain.children', 'children')
            ->addSelect('children')
            ->where('strain = :strain')
            ->setParameter('strain', $strain)
            ->getQuery();

        return $query->getOneOrNullResult();
    }
}
