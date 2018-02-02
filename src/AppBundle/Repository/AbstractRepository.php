<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

abstract class AbstractRepository extends EntityRepository
{
    protected function paginate(QueryBuilder $qbd, $limit, $offset)
    {
        if ($limit==0 || $offset ==0 ) {
            throw new \LogicException('$limit & $offset must be greater than 0.');
        }

        $pager = new Pagerfanta(new DoctrineORMAdapter($qbd));
        $currentPage = ceil(($offset + 1) / $limit);

        $pager->setCurrentPage($currentPage);
        $pager->setMaxPerPage($limit);

        return $pager;
    }
}