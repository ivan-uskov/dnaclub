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
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('r')
            ->from('AppBundle\Entity\MarketingReport', 'r')
            ->where('r.date = :date')
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult();
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
