<?php

namespace AppBundle\Entity\Repository;

use AppBundle\utils\DateUtils;
use Doctrine\ORM\EntityRepository;
use AppBundle\Form\OrderSearchForm;
use AppBundle\Form\PreOrderSearchForm;
use AppBundle\Entity\Client;
use AppBundle\config\OrderStatus;

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
            ->orderBy('o.createdAt', 'asc')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Client $client
     * @return array
     */
    public function getOrdersByClient($client)
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('o')
            ->from('AppBundle\Entity\Order', 'o')
            ->where('o.client = :client')
            ->setParameter('client', $client)
            ->orderBy('o.createdAt', 'desc')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param array $searchForm
     * @return array
     */
    public function getOrders($searchForm)
    {
        $qb = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('o')
            ->from('AppBundle\Entity\Order', 'o')
            ->orderBy('o.createdAt', 'desc');

        if ($searchForm)
        {
            /**
             * @var \DateTime $startDate
             */
            $startDate = $searchForm[OrderSearchForm::START_DATE_SEARCH_FIELD];
            /**
             * @var \DateTime $endDate
             */
            $endDate = $searchForm[OrderSearchForm::END_DATE_SEARCH_FIELD];
            if ($startDate && $endDate)
            {
                date_add($endDate, date_interval_create_from_date_string('1 day'));
                $qb->andWhere('o.createdAt >= :startDate')
                    ->andWhere('o.createdAt < :endDate')
                    ->setParameter('startDate', $startDate->format('Y-m-d'))
                    ->setParameter('endDate', $endDate->format('Y-m-d'));
            }

            /**
             * @var Client $client
             */
            $client = $searchForm[OrderSearchForm::CLIENT_SEARCH_FIELD];
            if ($client)
            {
                $qb->andWhere('o.client = :client')
                    ->setParameter('client', $client);
            }

            $status = $searchForm[OrderSearchForm::STATUS_SEARCH_FIELD];
            if ($status)
            {
                $qb->andWhere('o.status = :status')
                    ->setParameter('status', $status);
            }
        }

        return $qb->getQuery()->getResult();
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

    public function findByMonth($firstDayOfMonth)
    {
        $endDate = DateUtils::getFirstDayOfNextMonth($firstDayOfMonth);
        $result = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('c.clientId, SUM(o.sum) orderSum')
            ->from('AppBundle:Order', 'o')
            ->innerJoin('o.client', 'c')
            ->andWhere('o.status IN (:paidStatuses)')
            ->setParameter('paidStatuses', OrderStatus::getPaidStatuses())
            ->andWhere('o.createdAt >= :startDate')
            ->andWhere('o.createdAt < :endDate')
            ->setParameter('startDate',$firstDayOfMonth)
            ->setParameter('endDate', $endDate)
            ->groupBy('c')
            ->getQuery()
            ->getArrayResult();

        return $result;
    }
}
