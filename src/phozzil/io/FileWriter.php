<?php

namespace phozzil\io;

use phozzil\io\Writer;

/**
 * ファイルへの出力ストリームを表現するクラスです。
 */
class FileWriter extends Writer
{
    use FileCloseable;

    /**
     * 指定されたファイルへの出力ストリームをインスタンス化します。
     * @param string $fileName ファイルパス
     * @param bool $append データを末尾に追加するかどうか
     * @throws IOException 何らかの理由でファイルに書き出すことができない場合
     */
    public function __construct($fileName, $append = false)
    {
        $resource = @fopen($fileName, $append ? 'a' : 'w' . 'b');
        if ($resource === false) {
            throw new IOException($fileName);
        }
        $this->resource = $resource;
    }

    public function write($data)
    {
        fwrite($this->resource, $data);
    }

    public function flush()
    {
        fflush($this->resource);
    }
}