<?php

namespace phozzil\lang;

use phozzil\io\IOException;

/**
 * 閉じることができるプロセスリソースが実装するべきトレイトです。
 */
trait ProcessCloseable
{
    /**
     * @var resource プロセスリソース
     */
    private $resource;

    /**
     * @var array ファイルディスクリプタ (FileCloseable[])
     */
    private $pipes;

    /**
     * @var int 終了ステータス
     */
    private $exitValue;

    /**
     * プロセスリソース (とファイルディスクリプタ) を解放します。
     *
     * プロセスが終了していない場合は終了するまで待ちます。
     * @throws IOException プロセスリソースの解放に失敗した場合
     */
    public function close()
    {
        foreach ($this->pipes as $pipe) {
            try {
                $pipe->close();
            } catch (IOException $e) {
                // 既に解放されている場合は無視
            }
        }
        $exitValue = proc_close($this->resource);
        if ($exitValue === -1) {
            throw new IOException('proc_close failed');
        }

        $this->exitValue = $exitValue;
    }

    /**
     * プロセスの終了ステータスを返します。
     * @return int プロセスの終了ステータス
     */
    public function exitValue()
    {
        return $this->exitValue;
    }
}