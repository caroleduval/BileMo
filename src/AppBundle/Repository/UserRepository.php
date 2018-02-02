<?php

namespace AppBundle\Repository;

class UserRepository extends AbstractRepository
{
    public function search($id, $limit, $offset)
    {
        $qb = $this->createQueryBuilder('u');

        $qb ->where('u.client = :id')
            ->setParameter('id', $id)
        ;

        return $this->paginate($qb, $limit, $offset);
    }
}
