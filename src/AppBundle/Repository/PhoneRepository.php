<?php

namespace AppBundle\Repository;

class PhoneRepository extends AbstractRepository
{
    public function search($limit, $offset)
    {
        $qbd = $this
            ->createQueryBuilder('p')
            ->select('p')
        ;

        return $this->paginate($qbd, $limit, $offset);
    }
}