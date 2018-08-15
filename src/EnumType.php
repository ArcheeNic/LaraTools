<?php namespace ArcheeNic\LaraTools;

class EnumType
{
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