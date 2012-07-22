<?php

namespace phozzil\io;

/**
 * 閉じることができるファイルリソースが実装するべきトレイトです。
 */
trait FileCloseable
{
    /**
     * @var resource ファイルリソース
     */
    private $resource;

    public function close()
    {
        $result = fclose($this->resource);
        if (!$result) {
            throw new IOException('close failed');
        }
    }
}