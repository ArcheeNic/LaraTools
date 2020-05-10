<?php namespace ArcheeNic\LaraTools;

class EnumType
{

    public static $mapTitles = [];

    public static function getTitle($code)
    {
        return static::$mapTitles[$code] ?? static::getUndefinedTitle();
    }

    public static function getUndefinedTitle(): string
    {
        return '';
    }

    static $statusNames = [];
    /**
     * @param $statusCode
     *
     * @return mixed
     * @throws \Exception
     */
    static function getName($statusCode)
    {
        if (!isset(static::$statusNames[$statusCode])) {
            throw new \Exception('Status name undefined');
        }
        return static::$statusNames[$statusCode];
    }
}