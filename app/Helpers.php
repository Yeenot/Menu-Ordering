<?php

use Illuminate\Support\Arr;

if (!function_exists('args')) {
    function args($keys)
    {
        $args = collect(request());
        return $args->filter(function ($value, $key) use ($keys) {
            return in_array($key, $keys);
        });
    }
}

if (!function_exists('explodeByLast')) {
    function explodeByLast($delimiter, $value)
    {
        $index = strrpos($value, $delimiter);
        if ($index === false)
            return [$value];
            
        return [
            substr($value, 0, $index),
            substr($value, $index+1, strlen($value))
        ];
    }
}

if (!function_exists('array_undot')) {
    function array_undot($arrayDot)
    {
        $array = [];
        foreach ($arrayDot as $key => $value) {
            Arr::set($array, $key, $value);
        }
        return $array;
    }
}

if (!function_exists('is_assoc')) {
    function is_assoc($array)
    {
        if (!is_array($array))
            return false;

        $keys = array_keys($array);
        return array_keys($keys) !== $keys;
    }
}

if (!function_exists('array_deep_walk')) {
    function assoc_walk_deep($array, $callback, $leafCallback, $path = null)
    {
        foreach($array as $key => $value) {
            $keyPath = implodeNotNull('.', [$path, $key]);
            if (is_string($key)) {
                assoc_walk_deep($value, $callback, $keyPath);
                $callback($value, $keyPath);
            } else 
                $leafCallback($value, $keyPath);
        }
    }
}

if (!function_exists('implodeNotNull')) {
    function implodeNotNull($needle, $arr) {
       return implode($needle, array_filter($arr, function($value) { return !is_null($value); }));
    }
}