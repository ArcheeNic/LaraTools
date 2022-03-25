<?php namespace ArcheeNic\LaraTools;

/**
 * На базе полей и с приведением к типу
 * Class MainDataObjectGS
 *
 * @package ArcheeNic\LaraTools
 * @deprecated
 */
class MainDataObjectFTyped
{
    public function __construct($fields = [], $exception = false)
    {
        foreach ($fields as $key => $field) {
            if (!property_exists($this, $key)) {
                if($exception){
                    throw new \Exception('Incorrect field '.$key);
                }else{
                    continue;
                }
            }
            switch (gettype($this->{$key})) {
                case 'boolean':
                    $this->{$key} = (boolean)$field;
                    break;
                case 'integer':
                    $this->{$key} = (integer)$field;
                    break;
                case 'double':
                    $this->{$key} = (double)$field;
                    break;
                case 'string':
                    $this->{$key} = (string)$field;
                    break;
                case 'array':
                    $this->{$key} = (array)$field;
                    break;
                default:
                    $this->{$key} = $field;
                    break;
            }
        }
    }

}
