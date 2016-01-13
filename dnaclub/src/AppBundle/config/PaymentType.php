<?php

namespace AppBundle\config;

use AppBundle\utils\ArrayUtils;

class PaymentType
{
    const CASH   = 0;
    const REWARD = 1;

    private static $names = [
        self::CASH   => 'Наличными',
        self::REWARD => 'Вознаграждением',
    ];

    private static $shortNames = [
        self::CASH   => 'нал.',
        self::REWARD => 'возн.',
    ];

    public static function getNames()
    {
        return self::$names;
    }

    public static function getName($status, $default = null)
    {
        return ArrayUtils::getParameter(self::$names, $status, $default);
    }

    public static function getShortName($status, $default = null)
    {
        return ArrayUtils::getParameter(self::$shortNames, $status, $default);
    }
}