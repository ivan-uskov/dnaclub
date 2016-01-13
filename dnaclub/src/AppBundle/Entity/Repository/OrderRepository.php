<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Form\PreOrderSearchForm;

/**
 * OrderRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OrderRepository extends EntityRepository
{
    public function getDebtors()
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('o')
            ->from('AppBundle\Entity\Order', 'o')
            ->where('o.debt > 0')
            ->getQuery()
            ->getResult();
    }

    public function getPreOrders($searchForm)
    {
        $qb = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('o')
            ->from('AppBundle\Entity\Order', 'o')
            ->where('o.isPreOrder = 1')
            ->orderBy('o.plannedProductDate', 'asc');

        $isReleased    = $searchForm[PreOrderSearchForm::IS_RELEASED_SEARCH_FIELD];
        $isNotReleased = $searchForm[PreOrderSearchForm::IS_NOT_RELEASED_SEARCH_FIELD];

        if ($isReleased && !$isNotReleased)
        {
            $qb->andWhere('o.actualProductDate IS NOT NULL');
        }

        if ($isNotReleased && !$isReleased)
        {
            $qb->andWhere('o.actualProductDate IS NULL');
        }

        return $qb->getQuery()->getResult();
    }
}
