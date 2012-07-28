<?php

namespace phozzil\util;

/**
 * オブジェクトをキーとする値へのマップを表現するクラスです。
 *
 * SplObjectStorage のマップとしてのインタフェースだけを定義したラッパーとして実装されています。
 * @see \SplObjectStorage
 */
class Map implements \ArrayAccess, \Countable
{
    /**
     * @var \SplObjectStorage
     */
    private $instance;

    /**
     * 空のマップをインスタンス化します。
     */
    public function __construct()
    {
        $this->instance = new \SplObjectStorage();
    }

    /**
     * foreach の代替メソッドです。
     *
     * (foreach は key に object を指定するような再定義ができないため)
     * @param callback $function function(key, value)
     */
    public function each($function)
    {
        foreach ($this->instance as $index => $key) {
            $function($key, $this->instance[$key]);
        }
    }

    public function offsetExists($key)
    {
        return $this->instance->offsetExists($key);
    }

    public function offsetGet($key)
    {
        return $this->instance->offsetGet($key);
    }

    public function offsetSet($key, $value)
    {
        $this->instance->offsetSet($key, $value);
    }

    public function offsetUnset($key)
    {
        $this->instance->offsetUnset($key);
    }

    public function count()
    {
        return $this->instance->count();
    }
}