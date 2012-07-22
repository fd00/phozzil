<?php

namespace phozzil\io;

/**
 * 入力フィルタを表現する基底となるクラスです。
 */
abstract class FilterReader extends Reader
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * 入力フィルタをインスタンス化します。
     * @param Reader $reader 読み出し元となる Reader
     */
    public function __construct($reader)
    {
        $this->reader = $reader;
    }

    public function read($length)
    {
        return $this->reader->read($length);
    }

    public function close()
    {
        $this->reader->close();
    }
}