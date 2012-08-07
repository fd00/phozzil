<?php

namespace phozzil\lang;

use phozzil\io\FileCloseable;
use phozzil\io\FileFlushable;
use phozzil\io\FileWriteable;
use phozzil\io\Writer;

/**
 * プロセスへの出力ストリームを表現するクラスです。
 * @see Process
 */
class ProcessWriter extends Writer
{
    use FileCloseable;
    use FileFlushable;
    use FileWriteable;

    /**
     * プロセスへの出力ストリームをインスタンス化します。
     * @internal
     * @param resource $resource
     */
    function __construct($resource)
    {
        $this->resource = $resource;
    }
}