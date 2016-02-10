<?php

namespace AppBundle\utils;

class DateUtils
{
    /**
     * @param array[\DateTime] $dates
     * @return array
     */
    public static function getMonthsSelectFromTimestamp($dates)
    {
        $formatter = new \IntlDateFormatter(\Locale::getDefault(), \IntlDateFormatter::NONE, \IntlDateFormatter::NONE, 'UTC');
        $formatter->setPattern('LLLL y');

        $months = array();
        foreach ($dates as $date)
        {
            $dateObj = $date['date'];
            $months[$dateObj->format('Y-m-01')] = $formatter->format($dateObj);
        }

        $now = new \DateTime();
        $months[$now->format('Y-m-01')] = $formatter->format($now);

        krsort($months);
        return $months;
    }

    /**
     * @param string $date
     * @return \DateTime
     */
    public static function getFirstDayOfThisMonth($date = null)
    {
        return (new \DateTime($date))->modify('first day of this month midnight');
    }

    /**
     * @param string $date
     * @return \DateTime
     */
    public static function getFirstDayOfNextMonth($date = null)
    {
        return (new \DateTime($date))->modify('first day of next month midnight');
    }
}