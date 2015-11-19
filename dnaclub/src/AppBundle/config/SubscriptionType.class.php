<?php

class SubscriptionType
{
    const CONTRACT    = 0;
    const MAINTENANCE = 1;

    private static $names = array(
        self::CONTRACT    => 'Контракт',
        self::MAINTENANCE => 'Лидерская'
    );

    private static $shortNames = [
        self::CONTRACT    => 'К',
        self::MAINTENANCE => 'Л'
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
        return isset(self::$names[$type]) ? self::$names[$type] : $default;
    }

    public static function getShortName($type, $default = null)
    {
        return isset(self::$shortNames[$type]) ? self::$shortNames[$type] : $default;
    }

    public static function getPrice($type, $default = null)
    {
        return isset(self::$prices[$type]) ? self::$prices[$type] : $default;
    }
}