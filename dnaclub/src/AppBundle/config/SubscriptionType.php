<?php

namespace AppBundle\config;

use AppBundle\utils\ArrayUtils;

class SubscriptionType
{
    const CONTRACT    = 0;
    const MAINTENANCE = 1;

    private static $names = [
        self::CONTRACT    => 'Контракт',
        self::MAINTENANCE => 'Лидерская'
    ];

    private static $shortNames = [
        self::CONTRACT    => 'к',
        self::MAINTENANCE => 'л'
    ];

    private static $prices = [
        self::CONTRACT    => 7000,
        self::MAINTENANCE => 3500
    ];

    public static function getNames()
    {
        return self::$names;
    }

    public static function getName($type, $default = null)
    {
        return ArrayUtils::getParameter(self::$names, $type, $default);
    }

    public static function getShortName($type, $default = null)
    {
        return ArrayUtils::getParameter(self::$shortNames, $type, $default);
    }

    public static function getPrice($type, $default = null)
    {
        return ArrayUtils::getParameter(self::$prices, $type, $default);
    }

    public static function formatSubscriptionInfo($contractCnt, $maintenanceCnt)
    {
        $result = '';
        if ($contractCnt)
        {
            $result = $contractCnt . self::getShortName(self::CONTRACT) . ' ';
        }

        if ($maintenanceCnt)
        {
            $result .= $maintenanceCnt . self::getShortName(self::MAINTENANCE);
        }

        return $result;
    }
}