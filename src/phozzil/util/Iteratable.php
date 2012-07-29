<?php

namespace phozzil\util;

/**
 * foreach ($set as $value) の代替メソッドを提供するインタフェースです。
 */
interface Iteratable
{
    /**
     * foreach の代替メソッドです。
     *
     * (foreach は key に object を指定するような再定義ができないため)
     * @param callback $function function(value)
     */
    function each($function);
}