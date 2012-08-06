<?php

namespace phozzil\io;

/**
 * fread を使用して読み込むトレイトです。
 */
trait FileReadable
{
    public function read($length = null)
    {
        if (is_null($length)) {
            $result = '';
            while (!feof($this->resource)) {
                $result .= fread($this->resource, 8192);
            }
        } else {
            $result = fread($this->resource, $length);
        }
        return $result;
    }
}