<?php

namespace phozzil\io;

/**
 * fwrite を使用して書き込むトレイトです。
 */
trait FileWriteable
{
    public function write($data)
    {
        fwrite($this->resource, $data);
    }
}