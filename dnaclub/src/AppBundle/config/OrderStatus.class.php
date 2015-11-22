<?php

class OrderStatus
{
    const OPEN       = 0;
    const PROCESSING = 1;
    const PAID       = 2;
    const CANCELED   = 3;

    private static $names = [
        self::OPEN       => 'Новый',
        self::PROCESSING => 'Частично оплачен',
        self::PAID       => 'Оплачен',
        self::CANCELED   => 'Отменен'
    ];

    public static function getNames()
    {
        return self::$names;
    }

    public static function getName($status, $default = null)
    {
        return ArrayUtils::getParameter(self::$names, $status, $default);
    }
}