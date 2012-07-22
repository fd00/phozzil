<?php

namespace phozzil\io;

use phozzil\lang\IllegalArgumentException;

/**
 * 入力ストリームを表現する基底となるクラスです。
 */
abstract class Reader
{
    /**
     * 入力となるリソースから指定されたバイト数の文字列を取得します。
     *
     * 引数が指定されなかった場合は読み込める限り読み込みます。
     *
     * 引数で指定したバイト数よりも返り値の文字列が短い場合は EOF に到達したかタイムアウトが発生したものと考えられます。
     * @param int $length リソースから読み込むバイト数
     * @return string リソースから読み込んだ文字列
     * @throws IOException 読み込み時に何らかの例外が発生した場合
     */
    abstract public function read($length);

    /**
     * 入力となるリソースを解放します。
     * @throws IOException 解放時に何らかの例外が発生した場合
     */
    abstract public function close();

    /**
     * 指定されたバイト数だけ入力を読み飛ばします。
     * @param int $length 読み飛ばすバイト数
     * @return int 実際に読み飛ばしたバイト数
     * @throws IllegalArgumentException 引数が正の整数ではない場合
     * @throws IOException 読み込み時に何らかの例外が発生した場合
     */
    public function skip($length)
    {
        if (!is_int($length)) {
            throw new IllegalArgumentException('length must be integer');
        }
        if ($length < 0) {
            throw new IllegalArgumentException('length must be greater than 0');
        }
        if ($length === 0) {
            return 0;
        }
        return strlen($this->read($length));
    }
}