<?php

namespace phozzil\util;

/**
 * オブジェクトの集合を表現するクラスです。
 *
 * SplObjectStorage の集合としてのインタフェースだけを定義したラッパーとして実装されています。
 * @see \SplObjectStorage
 */
class Set implements \Countable, Iteratable
{
    /**
     * @var \SplObjectStorage
     */
    private $instance;

    /**
     * 空の集合をインスタンス化します。
     */
    public function __construct()
    {
        $this->instance = new \SplObjectStorage();
    }

    /**
     * 集合に指定されたオブジェクトを追加します。
     * @param object $object 追加したいオブジェクト
     */
    public function attach($object)
    {
        $this->instance->attach($object);
    }

    /**
     * 集合に指定されたオブジェクトが含まれるかどうかを返します。
     * @param object $object 含まれるかどうかを調べたいオブジェクト
     * @return bool 指定されたオブジェクトが含まれているかどうか
     */
    public function contains($object)
    {
        return $this->instance->contains($object);
    }

    /**
     * 集合から指定されたオブジェクトを取り除きます。
     * @param object $object 取り除きたいオブジェクト
     */
    public function detach($object)
    {
        $this->instance->detach($object);
    }

    public function each($function)
    {
        foreach ($this->instance as $value) {
            $function($value);
        }
    }

    public function count()
    {
        return $this->instance->count();
    }
}