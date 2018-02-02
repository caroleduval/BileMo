<?php

namespace AppBundle\Repository;

class PhoneRepository extends AbstractRepository
{
    public function search($limit, $offset)
    {
        $qb = $this
            ->createQueryBuilder('p')
            ->select('p')
        ;

        return $this->paginate($qb, $limit, $offset);
    }
}