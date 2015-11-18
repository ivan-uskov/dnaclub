<?php

class PaymentType
{
    const CASH   = 0;
    const REWARD = 1;

    private static $names = array(
        self::CASH   => 'Наличные',
        self::REWARD => 'Вознаграждение',
    );

    private static $shortNames = array(
        self::CASH   => 'руб.',
        self::REWARD => 'нагр.',
    );

    public static function getNames()
    {
        return self::$names;
    }

    public static function getName($status, $default = null)
    {
        return isset(self::$names[$status]) ? self::$names[$status] : $default;
    }

    public static function getShortName($status, $default = null)
    {
        return isset(self::$shortNames[$status]) ? self::$shortNames[$status] : $default;
    }
}