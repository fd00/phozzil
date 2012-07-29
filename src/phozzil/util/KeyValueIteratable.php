<?php

namespace phozzil\util;

/**
 * foreach ($map as $key => $value) の代替メソッドを提供するインタフェースです。
 */
interface KeyValueIteratable
{
    /**
     * foreach の代替メソッドです。
     *
     * (foreach は key に object を指定するような再定義ができないため)
     * @param callback $function function(key, value)
     */
    function each($function);
}