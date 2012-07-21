<?php

use phozzil\io\Endianness;

class EndiannessTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @todo ビッグエンディアンの方のテスト実績がまだない
     */
    static private function getMachineEndiannessByUname()
    {
        $processorName = php_uname('m');
        if (preg_match('/^i.*86$/', $processorName) === 1) {
            return Endianness::LITTLE_ENDIAN;
        } else if ($processorName === 'x86_64') {
            return Endianness::LITTLE_ENDIAN;
        }

        return null;
    }

    /**
     * @test
     */
    public function getMachineEndian()
    {
        $expected = self::getMachineEndiannessByUname();
        if (is_null($expected)) {
            $this->assertTrue(true, 'skip');
        } else {
            $actual = Endianness::getMachineEndianness();
            $this->assertSame($expected, $actual);
        }
    }
}