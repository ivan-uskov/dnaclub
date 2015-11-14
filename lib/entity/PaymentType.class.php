<?php

class PaymentType
{
    const CASH   = 0;
    const REWARD = 1;

    private static $names = array(
        self::CASH   => 'наличными',
        self::REWARD => 'вознаграждением',
    );

    private static $shortNames = array(
        self::CASH   => 'нал.',
        self::REWARD => 'возн.',
    );

    public static function getNames()
    {
        return self::$names;
    }

    public static function getName($status)
    {
        return isset(self::$names[$status]) ? self::$names[$status] : null;
    }

    public static function getShortName($status)
    {
        return isset(self::$shortNames[$status]) ? self::$shortNames[$status] : null;
    }
}