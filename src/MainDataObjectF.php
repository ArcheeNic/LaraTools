<?php namespace ArcheeNic\LaraTools;

/**
 * На базе полей
 * Class MainDataObjectGS
 *
 * @package ArcheeNic\LaraTools
 */
class MainDataObjectF
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
            $this->{$key} = $field;
        }
    }
}