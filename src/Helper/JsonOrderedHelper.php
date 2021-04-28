<?php

namespace ArcheeNic\LaraTools\Helper;

class JsonOrderedHelper
{
    private static function convertToKeyValueRecursive($key, $value, $iterator = 0)
    : array {
        $array = ['key' => $key];
        if (!is_array($value)) {
            $array['value'] = $value;
        } else {
            $array['value'] = collect($value)->mapWithKeys(
                function ($value, $key) use (&$iterator) {
                    return [$iterator=>static::convertToKeyValueRecursive($key, $value, $iterator++)];
                }
            )->toArray();
        }

        return $array;
    }

    public static function convertToKeyValue(array $value)
    : string {

        $iterator = 0;

        $array = collect($value)->mapWithKeys(
            function ($value, $key) use (&$iterator) {
                $array = [$iterator=>self::convertToKeyValueRecursive($key, $value, $iterator++)];
                return $array;
            }
        )->toArray();
        return json_encode($array);
    }

    public static function convertFromKeyValue(string $json)
    : array {
        $value = json_decode($json, true);

        return collect($value)->mapWithKeys(
            function ($value) {
                return [$value['key']=>static::convertFromKeyValueRecursive($value['value'])];
            }
        )->toArray();

    }

    public static function convertFromKeyValueRecursive($value){
        if(is_scalar($value)){
            return $value;
        }
        if(!is_array($value)){
            throw new \RuntimeException('incorrect kyValue array');
        }

        return collect($value)->mapWithKeys(
            function ($value) {
                return [$value['key']=>static::convertFromKeyValueRecursive($value['value'])];
            }
        )->toArray();
    }
}
