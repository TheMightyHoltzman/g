<?php

namespace AppBundle\Repository;

/**
 * PostRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PostRepository extends \Doctrine\ORM\EntityRepository
{
    const MAX_RESULTS = 15;

    public function getBlogPaginator($page)
    {
        $page -= $page;

        return $this->createQueryBuilder('b')
            ->andWhere('b.isPublished = 1')
            ->addOrderBy('b.publishedAt', 'DESC')
            ->setMaxResults(self::MAX_RESULTS)
            ->setFirstResult($page*self::MAX_RESULTS)
            ->getQuery()->getResult();
    }
}
