<?php namespace ArcheeNic\LaraTools;

/**
 * На базе сеттеров
 * Class MainDataObjectGS
 *
 * @package ArcheeNic\LaraTools
 */
class MainDataObjectS
{
    public function __construct($fields = [])
    {
        foreach ($fields as $key => $field) {
            $ucKey      = str_replace('_', ' ', $key);
            $ucKey      = ucwords($ucKey);
            $ucKey      = explode(' ', $ucKey);
            $ucKey      = implode('', $ucKey);
            $methodName = 'set'.$ucKey;
            if (is_callable([$this, $methodName])) {
                call_user_func([$this, $methodName], $field);
            }
        }
    }

}