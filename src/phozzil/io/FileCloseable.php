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

    /**
     * ファイルリソースを解放します。
     * @throws IOException ファイルリソースの解放に失敗した場合
     */
    public function close()
    {
        $result = fclose($this->resource);
        if (!$result) {
            throw new IOException('close failed');
        }
    }
}