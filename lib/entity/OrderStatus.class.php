<?php

class OrderStatus
{
    const OPEN       = 0;
    const PROCESSING = 1;
    const PAID       = 2;
    const CANCELED   = 3;

    private static $names = array(
        self::OPEN       => '�����',
        self::PROCESSING => '�������� �������',
        self::PAID       => '�������',
        self::CANCELED   => '�������'
    );

    public static function getNames()
    {
        return self::$names;
    }

    public static function getName($status)
    {
        return isset(self::$names[$status]) ? self::$names[$status] : null;
    }
}