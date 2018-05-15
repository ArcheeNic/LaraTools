<?php namespace ArcheeNic\LaraTools;

/**
 * На базе полей
 * Class MainDataObjectGS
 *
 * @package ArcheeNic\LaraTools
 */
class MainDataObjectF
{
    public function __construct($fields = [])
    {
        foreach ($fields as $key => $field) {
            if (!property_exists($this, $key)) {
                continue;
            }
            $this->{$key} = $fields;
        }
    }
}