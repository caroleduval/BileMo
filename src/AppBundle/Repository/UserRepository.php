<?php

namespace AppBundle\Repository;

class UserRepository extends AbstractRepository
{
    public function search($order = 'asc', $limit, $offset)
    {
        $qb = $this
            ->createQueryBuilder('u')
            ->select('u')
            ->orderBy('u.name', $order)
        ;

        return $this->paginate($qb, $limit, $offset);
    }
}