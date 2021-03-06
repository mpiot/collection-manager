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

use App\Entity\User;

/**
 * PrimerRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PrimerRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllForUser(User $user)
    {
        $query = $this->createQueryBuilder('primer')
            ->leftJoin('primer.group', 'g')
            ->leftJoin('g.members', 'members')
            ->where('members = :user')
            ->setParameter('user', $user)
            ->getQuery();

        return $query->getResult();
    }
}
