<?php

namespace phozzil\io;

/**
 * 出力フィルタを表現する基底となるクラスです。
 */
abstract class FilterWriter extends Writer
{
    /**
     * @var Writer
     */
    private $writer;

    /**
     * 出力フィルタをインスタンス化します。
     * @param Writer $writer 書き出し先となる Writer
     */
    public function __construct($writer)
    {
        $this->writer = $writer;
    }

    public function write($data)
    {
        return $this->writer->write($data);
    }

    public function close()
    {
        $this->writer->close();
    }

    public function flush()
    {
        $this->writer->flush();
    }
}