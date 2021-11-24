<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class JobRepository extends EntityRepository
{
    /**
     * @param string $query
     * @return array
     */
    public function searchByQuery(string $query): array
    {
        $qb = $this->createQueryBuilder('j');

        return $qb
            ->where($qb->expr()->like('j.title', ':query'))
            ->setParameter('query', "%{$query}%")
            ->getQuery()
            ->getResult();
    }
}
