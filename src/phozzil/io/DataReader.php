<?php

namespace phozzil\io;

use phozzil\io\FilterReader;
use phozzil\lang\IllegalArgumentException;

/**
 * バイトストリームからプリミティブなデータを読み出すための入力フィルタです。
 */
class DataReader extends FilterReader
{
    /**
     * @var Endianness バイトストリームのエンディアン
     */
    private $endianness;

    /**
     * 指定されたエンディアンに基づく入力フィルタをインスタンス化します。
     *
     * エンディアンが指定されなかった場合は処理系のエンディアンを判定して設定します。
     * @param Reader $reader 読み出し元となる
     * @param Endianness $endianness バイトストリームのエンディアン
     * @throws IllegalArgumentException
     */
    public function __construct($reader, $endianness = Endianness::UNKNOWN_ENDIAN)
    {
        if ($endianness === Endianness::UNKNOWN_ENDIAN) {
            $endianness = Endianness::getMachineEndianness();
        }
        parent::__construct($reader);
        $this->endianness = $endianness;
    }

    /**
     * 読み込んだ 1 バイトを符号付き整数値として解釈して返します。
     * @return int 1 バイトの入力データが表現する符号付き整数値
     */
    public function readByte()
    {
        list(, $data) = unpack('c', $this->read(1));
        return $data;
    }

    /**
     * 読み込んだ 1 バイトを符号なし整数値として解釈して返します。
     * @return int 1 バイトの入力データが表現する符号なし整数値
     */
    public function readUnsignedByte()
    {
        list(, $data) = unpack('C', $this->read(1));
        return $data;
    }

    /**
     * 読み込んだ 2 バイトを符号付き整数値として解釈して返します。
     * @return int 2 バイトの入力データが表現する符号付き整数値
     */
    public function readShort()
    {
        list(, $data) = unpack('s', $this->read(2));
        return $data;
    }

    /**
     * 読み込んだ 2 バイトを符号なし整数値として解釈して返します。
     * @return int 2 バイトの入力データが表現する符号なし整数値
     */
    public function readUnsignedShort()
    {
        $format = $this->endianness === Endianness::LITTLE_ENDIAN ? 'v' : 'n';
        list(, $data) = unpack($format, $this->read(2));
        return $data;
    }

    /**
     * 読み込んだ 4 バイトを符号付き整数値として解釈して返します。
     * @return int 4 バイトの入力データが表現する符号付き整数値
     */
    public function readInteger()
    {
        list(, $data) = unpack('l', $this->read(4));
        return $data;
    }

    /**
     * 読み込んだ 4 バイトを符号なし整数値として解釈して返します。
     * @return int 4 バイトの入力データが表現する符号なし整数値
     */
    public function readUnsignedInteger()
    {
        $format = $this->endianness === Endianness::LITTLE_ENDIAN ? 'V' : 'N';
        list(, $data) = unpack($format, $this->read(4));
        return $data;
    }

    /**
     * 読み込んだ 4 バイトを浮動小数点数値として解釈して返します。
     * @return int 4 バイトの入力データが表現する浮動小数点数値
     */
    public function readFloat()
    {
        list(, $data) = unpack('f', $this->read(4));
        return $data;
    }

    /**
     * 読み込んだ 8 バイトを浮動小数点数値として解釈して返します。
     * @return int 8 バイトの入力データが表現する浮動小数点数値
     */
    public function readDouble()
    {
        list(, $data) = unpack('d', $this->read(8));
        return $data;
    }

    /**
     * この入力フィルタが想定しているバイトストリームのエンディアンを返します。
     * @return int バイトストリームのエンディアン
     */
    protected function getEndianness()
    {
        return $this->endianness;
    }
}