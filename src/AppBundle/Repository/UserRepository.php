<?php

namespace AppBundle\Repository;

class UserRepository extends AbstractRepository
{
    public function search($id, $limit, $offset)
    {
        $qbd = $this->createQueryBuilder('u');

        $qbd ->where('u.client = :id')
            ->setParameter('id', $id)
        ;

        return $this->paginate($qbd, $limit, $offset);
    }
}
