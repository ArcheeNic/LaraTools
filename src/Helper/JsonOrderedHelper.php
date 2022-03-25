<?php

namespace ArcheeNic\LaraTools\Helper;

use JetBrains\PhpStorm\ArrayShape;

class JsonOrderedHelper
{
    #[ArrayShape(['key' => "", 'value' => "array"])] private static function convertToKeyValueRecursive($key, $value, $iterator = 0)
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

    /**
     * @throws \JsonException
     */
    public static function convertToKeyValue(array $value)
    : string {

        $iterator = 0;

        $array = collect($value)->mapWithKeys(
            function ($value, $key) use (&$iterator) {
                return [$iterator =>self::convertToKeyValueRecursive($key, $value, $iterator++)];
            }
        )->toArray();
        return json_encode($array, JSON_THROW_ON_ERROR);
    }

    /**
     * @throws \JsonException
     */
    public static function convertFromKeyValue(string $json)
    : array {
        $value = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        return collect($value)->mapWithKeys(
            function ($value) {
                return [$value['key']=>static::convertFromKeyValueRecursive($value['value'])];
            }
        )->toArray();

    }

    public static function convertFromKeyValueRecursive($value): float|array|bool|int|string|null
    {
        if(is_scalar($value) || !$value){
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
