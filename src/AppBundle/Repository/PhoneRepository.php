<?php

namespace AppBundle\Repository;

class PhoneRepository extends AbstractRepository
{
    public function search($order = 'asc', $limit, $offset)
    {
        $qb = $this
            ->createQueryBuilder('p')
            ->select('p')
            ->orderBy('p.id', $order)
        ;

        return $this->paginate($qb, $limit, $offset);
    }
}