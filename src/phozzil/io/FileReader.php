<?php

namespace phozzil\io;

use phozzil\io\Reader;

/**
 * ファイルからの入力ストリームを表現するクラスです。
 */
class FileReader extends Reader
{
    use FileCloseable;

    /**
     * 指定されたファイルからの入力ストリームをインスタンス化します。
     * @param string $fileName ファイルパス
     * @throws FileNotFoundException ファイルが見つからなかった場合
     * @throws IOException ファイルを読み込むことができなかった場合
     */
    public function __construct($fileName)
    {
        if (!is_file($fileName)) {
            throw new FileNotFoundException($fileName);
        }
        $resource = @fopen($fileName, 'rb');
        if ($resource === false) {
            throw new IOException('fopen failed');
        }
        $this->resource = $resource;
    }

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