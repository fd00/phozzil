<?php

namespace phozzil\io;

/**
 * fflush を使用してフラッシュするトレイトです。
 */
trait FileFlushable
{
    public function flush()
    {
        fflush($this->resource);
    }
}