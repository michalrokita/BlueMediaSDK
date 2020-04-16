<?php


namespace michalrokita\BlueMediaSDK\Helpers;


class ArrayHelper
{
    /**
     * Returns the last key of an array
     * @param array $array
     * @return int|string|null
     */
    public static function arrayLastKey(array $array)
    {
        if (function_exists('array_key_last')) {
            return array_key_last($array);
        }

        end($array);
        return key($array);
    }
}