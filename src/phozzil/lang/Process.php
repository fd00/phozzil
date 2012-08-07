<?php

namespace phozzil\lang;

use phozzil\io\Reader;
use phozzil\io\Writer;

/**
 * OS のプロセスを表現するクラスです。
 */
final class Process
{
    use ProcessCloseable;

    /**
     * @internal
     * @param resource $resource プロセスリソース
     * @param array $pipes ファイルディスクリプタ (resource[])
     */
    public function __construct($resource, array $pipes)
    {
        $this->resource = $resource;
        $this->pipes = array();

        $this->pipes[0] = new ProcessWriter($pipes[0]);
        $this->pipes[1] = new ProcessReader($pipes[1]);
        $this->pipes[2] = new ProcessReader($pipes[2]);
    }

    /**
     * プロセスの標準入力に接続された Writer を返します。
     * @return Writer プロセスの標準入力に接続された Writer
     */
    public function getStandardInput()
    {
        return $this->pipes[0];
    }

    /**
     * プロセスの標準出力に接続された Reader を返します。
     * @return Reader プロセスの標準出力に接続された Reader
     */
    public function getStandardOutput()
    {
        return $this->pipes[1];
    }

    /**
     * プロセスのエラー出力に接続された Reader を返します。
     * @return Reader プロセスのエラー出力に接続された Reader
     */
    public function getStandardError()
    {
        return $this->pipes[2];
    }
}