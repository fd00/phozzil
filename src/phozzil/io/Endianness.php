<?php

namespace phozzil\io;

/**
 * エンディアンを表現する列挙型です。
 */
class Endianness
{
    /**
     * @var int 不明なエンディアン
     */
    const UNKNOWN_ENDIAN = 0;

    /**
     * @var int リトルエンディアン
     */
    const LITTLE_ENDIAN = 1;

    /**
     * @var int ビッグエンディアン
     */
    const BIG_ENDIAN = 2;

    /**
     * インスタンス化することはできません。
     */
    private function __construct() {}

    /**
     * 処理系のエンディアンを取得します。
     * @return int 処理系のエンディアン
     */
    static public function getMachineEndianness()
    {
        list( ,$word) = unpack('l*', "\x01\x00\x00\x00");
        return $word === 1 ? self::LITTLE_ENDIAN : self::BIG_ENDIAN;
    }
}