<?php

use phozzil\io\DataReader;
use phozzil\io\Endianness;
use phozzil\io\FileReader;
use phozzil\io\FileSystem;

class DataReaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function readByte()
    {
        $fileName = FileSystem::createTemporaryFile();
        file_put_contents($fileName, pack('c', -128), FILE_APPEND);
        file_put_contents($fileName, pack('c', 127), FILE_APPEND);

        $dr = new DataReader(new FileReader($fileName));
        $this->assertSame(-128, $dr->readByte());
        $this->assertSame(127, $dr->readByte());
    }

    /**
     * @test
     */
    public function readUnsignedByte()
    {
        $fileName = FileSystem::createTemporaryFile();
        file_put_contents($fileName, pack('C', 0), FILE_APPEND);
        file_put_contents($fileName, pack('C', 255), FILE_APPEND);

        $dr = new DataReader(new FileReader($fileName));
        $this->assertSame(0, $dr->readUnsignedByte());
        $this->assertSame(255, $dr->readUnsignedByte());
    }

    /**
     * @test
     */
    public function readShort()
    {
        $fileName = FileSystem::createTemporaryFile();
        file_put_contents($fileName, pack('s', -32768), FILE_APPEND);
        file_put_contents($fileName, pack('s', 1024), FILE_APPEND);
        file_put_contents($fileName, pack('s', 32767), FILE_APPEND);

        $dr = new DataReader(new FileReader($fileName));
        $this->assertSame(-32768, $dr->readShort());
        $this->assertSame(1024, $dr->readShort());
        $this->assertSame(32767, $dr->readShort());
    }

    /**
     * @test
     */
    public function readUnsignedShortOnLittleEndian()
    {
        $fileName = FileSystem::createTemporaryFile();
        file_put_contents($fileName, pack('v', 0), FILE_APPEND);
        file_put_contents($fileName, pack('v', 12756), FILE_APPEND);
        file_put_contents($fileName, pack('v', 54321), FILE_APPEND);
        file_put_contents($fileName, pack('v', 65535), FILE_APPEND);

        $dr = new DataReader(new FileReader($fileName), Endianness::LITTLE_ENDIAN);
        $this->assertSame(0, $dr->readUnsignedShort());
        $this->assertSame(12756, $dr->readUnsignedShort());
        $this->assertSame(54321, $dr->readUnsignedShort());
        $this->assertSame(65535, $dr->readUnsignedShort());
    }

    /**
     * @test
     */
    public function readUnsignedShortOnBigEndian()
    {
        $fileName = FileSystem::createTemporaryFile();
        file_put_contents($fileName, pack('n', 0), FILE_APPEND);
        file_put_contents($fileName, pack('n', 12756), FILE_APPEND);
        file_put_contents($fileName, pack('n', 54321), FILE_APPEND);
        file_put_contents($fileName, pack('n', 65535), FILE_APPEND);

        $dr = new DataReader(new FileReader($fileName), Endianness::BIG_ENDIAN);
        $this->assertSame(0, $dr->readUnsignedShort());
        $this->assertSame(12756, $dr->readUnsignedShort());
        $this->assertSame(54321, $dr->readUnsignedShort());
        $this->assertSame(65535, $dr->readUnsignedShort());
    }

    /**
     * @test
     */
    public function readInteger()
    {
        $fileName = FileSystem::createTemporaryFile();
        file_put_contents($fileName, pack('l', -2147483647 - 1), FILE_APPEND);
        file_put_contents($fileName, pack('l', 123456789), FILE_APPEND);
        file_put_contents($fileName, pack('l', 2147483647), FILE_APPEND);

        $dr = new DataReader(new FileReader($fileName));
        $this->assertSame(-2147483647 - 1, $dr->readInteger());
        $this->assertSame(123456789, $dr->readInteger());
        $this->assertSame(2147483647, $dr->readInteger());
    }

    /**
     * @test
     */
    public function readUnsignedIntegerOnLittleEndian()
    {
        $fileName = FileSystem::createTemporaryFile();
        file_put_contents($fileName, pack('V', 123456789), FILE_APPEND);
        file_put_contents($fileName, pack('V', 2147483647), FILE_APPEND);

        $dr = new DataReader(new FileReader($fileName), Endianness::LITTLE_ENDIAN);
        $this->assertSame(123456789, $dr->readUnsignedInteger());
        $this->assertSame(2147483647, $dr->readUnsignedInteger());
    }

    /**
     * @test
     */
    public function readUnsignedIntegerOnBigEndian()
    {
        $fileName = FileSystem::createTemporaryFile();
        file_put_contents($fileName, pack('N', 123456789), FILE_APPEND);
        file_put_contents($fileName, pack('N', 2147483647), FILE_APPEND);

        $dr = new DataReader(new FileReader($fileName), Endianness::BIG_ENDIAN);
        $this->assertSame(123456789, $dr->readUnsignedInteger());
        $this->assertSame(2147483647, $dr->readUnsignedInteger());
    }

    /**
     * @test
     */
    public function readFloat()
    {
        $fileName = FileSystem::createTemporaryFile();
        file_put_contents($fileName, pack('f', 0.125), FILE_APPEND);

        $dr = new DataReader(new FileReader($fileName));
        $this->assertSame(0.125, $dr->readFloat());
    }

    /**
     * @test
     */
    public function readDouble()
    {
        $fileName = FileSystem::createTemporaryFile();
        file_put_contents($fileName, pack('d', 0.125), FILE_APPEND);

        $dr = new DataReader(new FileReader($fileName));
        $this->assertSame(0.125, $dr->readDouble());
    }
}