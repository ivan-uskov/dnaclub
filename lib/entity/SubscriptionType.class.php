<?php

class SubscriptionType
{
    const CONTRACT    = 0;
    const MAINTENANCE = 1;

    private static $names = array(
        self::CONTRACT    => 'Контракт',
        self::MAINTENANCE => 'Лидерская'
    );

    private static $shortNames = array(
        self::CONTRACT    => 'к',
        self::MAINTENANCE => 'л'
    );

    private static $prices = array(
        self::CONTRACT    => 7000,
        self::MAINTENANCE => 3500
    );

    public static function getNames()
    {
        return self::$names;
    }

    public static function getName($type)
    {
        return isset(self::$names[$type]) ? self::$names[$type] : null;
    }

    public static function getShortName($type)
    {
        return isset(self::$shortNames[$type]) ? self::$shortNames[$type] : null;
    }

    public static function getPrice($type)
    {
        return isset(self::$prices[$type]) ? self::$prices[$type] : null;
    }
}