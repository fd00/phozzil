<?php

namespace phozzil\lang;

use phozzil\io\FileCloseable;
use phozzil\io\FileReadable;
use phozzil\io\Reader;

/**
 * プロセスからの入力ストリームを表現するクラスです。
 * @see Process
 */
class ProcessReader extends Reader
{
    use FileCloseable;
    use FileReadable;

    /**
     * プロセスからの入力ストリームをインスタンス化します。
     * @internal
     * @param resource $resource
     */
    public function __construct($resource)
    {
        $this->resource = $resource;
    }
}