<?php

namespace phozzil\io;

/**
 * 出力ストリームを表現する基底となるクラスです。
 */
abstract class Writer
{
    /**
     * 出力となるリソースへ指定された文字列を書き出します。
     * @param string $data 書き出す文字列
     * @throws IOException 書き出し時に何らかの例外が発生した場合
     */
    abstract public function write($data);

    /**
     * 出力となるリソースを解放します。
     * @throws IOException 解放時に何らかの例外が発生した場合
     */
    abstract public function close();

    /**
     * フラッシュします。
     * @throws IOException フラッシュ時に何らかの例外が発生した場合
     */
    abstract public function flush();
}