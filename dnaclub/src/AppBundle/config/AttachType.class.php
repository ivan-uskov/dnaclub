<?php

class AttachType
{
    const UNKNOWN = 0;
    const IMAGE   = 1;
    const WORD    = 2;
    const EXCEL   = 3;

    private static $names = [
        self::UNKNOWN => 'Неизвестный формат',
        self::IMAGE   => 'Изображение',
        self::WORD    => 'MS Word',
        self::EXCEL   => 'MS Excel'
    ];

    public static function getNames()
    {
        return self::$names;
    }

    public static function getName($type, $default = null)
    {
        return ArrayUtils::getParameter(self::$names, $type, $default);
    }
}