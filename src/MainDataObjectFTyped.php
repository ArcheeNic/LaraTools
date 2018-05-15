<?php namespace ArcheeNic\LaraTools;

/**
 * На базе полей и с приведением к типу
 * Class MainDataObjectGS
 *
 * @package ArcheeNic\LaraTools
 */
class MainDataObjectFTyped
{
    public function __construct($fields = [])
    {
        foreach ($fields as $key => $field) {
            if (!property_exists($this, $key)) {
                continue;
            }
            switch (gettype($this->{$key})) {
                case 'boolean':
                    $this->{$key} = (boolean)$fields;
                    break;
                case 'integer':
                    $this->{$key} = (integer)$fields;
                    break;
                case 'double':
                    $this->{$key} = (double)$fields;
                    break;
                case 'string':
                    $this->{$key} = (string)$fields;
                    break;
                case 'array':
                    $this->{$key} = (array)$fields;
                    break;
                default:
                    $this->{$key} = $fields;
                    break;
            }
            $this->{$key} = $fields;
        }
    }

}