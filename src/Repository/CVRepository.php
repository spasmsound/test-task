<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class CVRepository extends EntityRepository
{
    /**
     * @param string $workType
     * @return array
     */
    public function findRelevantCVs(string $workType): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.work = :workType')
            ->setParameter('workType', $workType)
            ->orderBy('c.experience', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
