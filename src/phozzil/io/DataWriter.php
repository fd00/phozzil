<?php

namespace phozzil\io;

use phozzil\io\FilterWriter;

class DataWriter extends FilterWriter
{
    /**
     * @var Endianness バイトストリームのエンディアン
     */
    private $endianness;

    /**
     * 指定されたエンディアンに基づく出力フィルタをインスタンス化します。
     *
     * エンディアンが指定されなかった場合は処理系のエンディアンを判定して設定します。
     * @param Writer $writer 書き出し先となる Writer
     * @param int $endianness バイトストリームのエンディアン
     */
    public function __construct(Writer $writer, $endianness = Endianness::UNKNOWN_ENDIAN)
    {
        if ($endianness === Endianness::UNKNOWN_ENDIAN) {
            $endianness = Endianness::getMachineEndianness();
        }
        parent::__construct($writer);
        $this->endianness = $endianness;
    }

    /**
     * 符号付き 1 バイト整数値を書き出します。
     * @param int $data 書き込む符号付き 1 バイト整数値
     */
    public function writeByte($data)
    {
        $this->write(pack('c', $data));
    }

    /**
     * 符号なし 1 バイト整数値を書き出します。
     * @param int $data 書き込む符号なし 1 バイト整数値
     */
    public function writeUnsignedByte($data)
    {
        $this->write(pack('C', $data));
    }

    /**
     * 符号付き 2 バイト整数値を書き出します。
     * @return int $data 書き込む符号付き 2 バイト整数値
     */
    public function writeShort($data)
    {
        $this->write(pack('s', $data));
    }

    /**
     * 符号なし 2 バイト整数値を書き出します。
     * @return int $data 書き込む符号なし 2 バイト整数値
     */
    public function writeUnsignedShort($data)
    {
        $format = $this->endianness === Endianness::LITTLE_ENDIAN ? 'v' : 'n';
        $this->write(pack($format, $data));
    }

    /**
     * 符号付き 4 バイト整数値を書き出します。
     * @return int $data 書き込む符号付き 4 バイト整数値
     */
    public function writeInteger($data)
    {
        $this->write(pack('l', $data));
    }

    /**
     * 符号なし 4 バイト整数値を書き出します。
     * @return int $data 書き込む符号なし 4 バイト整数値
     */
    public function writeUnsignedInteger($data)
    {
        $format = $this->endianness === Endianness::LITTLE_ENDIAN ? 'V' : 'N';
        $this->write(pack($format, $data));
    }

    /**
     * 4 バイト浮動小数点数値を書き出します。
     * @param float $data 書き込む 4 バイト浮動小数点数値
     */
    public function writeFloat($data)
    {
        $this->write(pack('f', $data));
    }

    /**
     * 8 バイト浮動小数点数値を書き出します。
     * @param float $data 書き込む 8 バイト浮動小数点数値
     */
    public function writeDouble($data)
    {
        $this->write(pack('d', $data));
    }

    /**
     * この出力フィルタが想定しているバイトストリームのエンディアンを返します。
     * @return int バイトストリームのエンディアン
     */
    protected function getEndianness()
    {
        return $this->endianness;
    }
}