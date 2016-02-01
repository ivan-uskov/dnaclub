<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\utils\DateUtils;

/**
 * MarketingReportRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */

class MarketingReportRepository extends EntityRepository
{
    public function findByDate($date)
    {
        $reportRows = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('r')
            ->from('AppBundle\Entity\MarketingReport', 'r')
            ->where('r.date = :date')
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult();

        $result = array();
        foreach($reportRows as $reportRow)
        {
            $startSum = $reportRow->getStartSum();
            $orderSum = $reportRow->getOrderSum();
            $releaseSum = $startSum + $orderSum;
            $endSum = $releaseSum - $reportRow->getContractSum() - $reportRow->getMaintenanceSum();
            $client = $reportRow->getClient();

            $result[$client->getClientId()] = array(
                'clientId' => $client->getClientId(),
                'name'     => $client->getFullName(),
                'startSum' => $startSum,
                'orderSum' => $orderSum,
                'releaseSum' => $releaseSum,
                'subscrInfo' => $reportRow->getSubscriptionsInfo(),
                'endSum'      => $endSum
            );
        }

        return $result;
    }

    public function getMonthsForSelect()
    {
        $dates = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('DISTINCT r.date')
            ->from('AppBundle\Entity\MarketingReport', 'r')
            ->orderBy('r.date', 'desc')
            ->getQuery()
            ->getResult();

       return DateUtils::getMonthsSelectFromTimestamp($dates);
    }
}
