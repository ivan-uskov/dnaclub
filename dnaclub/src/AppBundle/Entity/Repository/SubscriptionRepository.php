<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\utils\DateUtils;

/**
 * SubscriptionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SubscriptionRepository extends EntityRepository
{
    public function findByMonth($date)
    {
        $startDate = DateUtils::getFirstDayOfThisMonth($date);
        $endDate = DateUtils::getFirstDayOfNextMonth($date);

        $result = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('s')
            ->from('AppBundle\Entity\Subscription', 's')
            ->where('s.date >= :start_date')
            ->andWhere('s.date < :end_date')
            ->andWhere('s.isDeleted = 0')
            ->setParameter('start_date', $startDate)
            ->setParameter('end_date', $endDate)
            ->orderBy('s.date', 'desc');

        $result = $result->getQuery()->getResult();

        return $result;
    }

    public function findByClient($client)
    {
        $result = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('s')
            ->from('AppBundle\Entity\Subscription', 's')
            ->andWhere('s.isDeleted = 0')
            ->orderBy('s.date', 'desc');

        if ($client != null)
        {
            $result
                ->andWhere('s.client = :client_id')
                ->setParameter('client_id', $client->getClientId());
        }

        $result = $result->getQuery()->getResult();

        return $result;
    }

    public function getMonthsForSelect()
    {
        $dates = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('DISTINCT s.date')
            ->from('AppBundle\Entity\Subscription', 's')
            ->where('s.isDeleted = 0')
            ->orderBy('s.date', 'desc')
            ->getQuery()
            ->getResult();

        return DateUtils::getMonthsSelectFromTimestamp($dates);
    }

    public function findByMonthGroupedByClient($firstDayOfMonth)
    {
        $endDate = DateUtils::getFirstDayOfNextMonth($firstDayOfMonth);
        $result = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('c.clientId')
            ->addSelect('SUM(CASE s.type WHEN 0 THEN s.sum ELSE 0 END) contractSum')
            ->addSelect('SUM(CASE s.type WHEN 0 THEN s.count ELSE 0 END) contractCnt')
            ->addSelect('SUM(CASE s.type WHEN 1 THEN s.sum ELSE 0 END) maintenanceSum')
            ->addSelect('SUM(CASE s.type WHEN 1 THEN s.count ELSE 0 END) maintenanceCnt')
            ->from('AppBundle:Subscription', 's')
            ->innerJoin('s.client', 'c')
            ->andWhere('s.date >= :startDate')
            ->andWhere('s.date < :endDate')
            ->setParameter('startDate',$firstDayOfMonth)
            ->setParameter('endDate', $endDate)
            ->groupBy('c')
            ->getQuery()
            ->getArrayResult();

        return $result;
    }
}
