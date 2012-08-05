<?php

use phozzil\io\Endianness;

use phozzil\io\DataWriter;
use phozzil\io\FileSystem;
use phozzil\io\FileWriter;

class DataWriterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function writeByte()
    {
        $fileName = FileSystem::createTemporaryFile();
        $writer = new DataWriter(new FileWriter($fileName));
        $writer->writeByte(-128);
        $writer->writeByte(127);
        $writer->writeByte(0);
        $writer->flush();

        $data = file_get_contents($fileName);
        list(, $actual1, $actual2, $actual3) = unpack('c*', $data);
        $this->assertSame(-128, $actual1);
        $this->assertSame( 127, $actual2);
        $this->assertSame(   0, $actual3);
    }

    /**
     * @test
     */
    public function writeUnsignedByte()
    {
        $fileName = FileSystem::createTemporaryFile();
        $writer = new DataWriter(new FileWriter($fileName));
        $writer->writeUnsignedByte(255);
        $writer->writeUnsignedByte(0);
        $writer->flush();

        $data = file_get_contents($fileName);
        list(, $actual1, $actual2) = unpack('C*', $data);
        $this->assertSame(255, $actual1);
        $this->assertSame(  0, $actual2);
    }

    /**
     * @test
     */
    public function writeShort()
    {
        $fileName = FileSystem::createTemporaryFile();
        $writer = new DataWriter(new FileWriter($fileName));
        $writer->writeShort(32767);
        $writer->writeShort(-32768);
        $writer->writeShort(0);
        $writer->flush();

        $data = file_get_contents($fileName);
        list(, $actual1, $actual2, $actual3) = unpack('s*', $data);
        $this->assertSame( 32767, $actual1);
        $this->assertSame(-32768, $actual2);
        $this->assertSame(     0, $actual3);
    }

    /**
     * @test
     */
    public function writeUnsignedShortOnLittleEndian()
    {
        $fileName = FileSystem::createTemporaryFile();
        $writer = new DataWriter(new FileWriter($fileName), Endianness::LITTLE_ENDIAN);
        $writer->writeUnsignedShort(12756);
        $writer->writeUnsignedShort(54321);
        $writer->writeUnsignedShort(65535);
        $writer->flush();

        $data = file_get_contents($fileName);
        list(, $actual1, $actual2, $actual3) = unpack('v*', $data);
        $this->assertSame(12756, $actual1);
        $this->assertSame(54321, $actual2);
        $this->assertSame(65535, $actual3);
    }

    /**
     * @test
     */
    public function writeUnsignedShortOnBigEndian()
    {
        $fileName = FileSystem::createTemporaryFile();
        $writer = new DataWriter(new FileWriter($fileName), Endianness::BIG_ENDIAN);
        $writer->writeUnsignedShort(12756);
        $writer->writeUnsignedShort(54321);
        $writer->writeUnsignedShort(65535);
        $writer->flush();

        $data = file_get_contents($fileName);
        list(, $actual1, $actual2, $actual3) = unpack('n*', $data);
        $this->assertSame(12756, $actual1);
        $this->assertSame(54321, $actual2);
        $this->assertSame(65535, $actual3);
    }

    /**
     * @test
     */
    public function writeInteger()
    {
        $fileName = FileSystem::createTemporaryFile();
        $writer = new DataWriter(new FileWriter($fileName));
        $writer->writeInteger(-2147483647 - 1);
        $writer->writeInteger(123456789);
        $writer->writeInteger(2147483647);
        $writer->flush();

        $data = file_get_contents($fileName);
        list(, $actual1, $actual2, $actual3) = unpack('l*', $data);
        $this->assertSame(-2147483647 - 1, $actual1);
        $this->assertSame(  123456789,     $actual2);
        $this->assertSame( 2147483647,     $actual3);
    }

    /**
     * @test
     */
    public function writeUnsignedIntegerOnLittleEndian()
    {
        $fileName = FileSystem::createTemporaryFile();
        $writer = new DataWriter(new FileWriter($fileName), Endianness::LITTLE_ENDIAN);
        $writer->writeUnsignedInteger(123456789);
        $writer->writeUnsignedInteger(2147483647);
        $writer->flush();

        $data = file_get_contents($fileName);
        list(, $actual1, $actual2) = unpack('V*', $data);
        $this->assertSame( 123456789, $actual1);
        $this->assertSame(2147483647, $actual2);
    }

    /**
     * @test
     */
    public function writeUnsignedIntegerOnBigEndian()
    {
        $fileName = FileSystem::createTemporaryFile();
        $writer = new DataWriter(new FileWriter($fileName), Endianness::BIG_ENDIAN);
        $writer->writeUnsignedInteger(123456789);
        $writer->writeUnsignedInteger(2147483647);
        $writer->flush();

        $data = file_get_contents($fileName);
        list(, $actual1, $actual2) = unpack('N*', $data);
        $this->assertSame( 123456789, $actual1);
        $this->assertSame(2147483647, $actual2);
    }

    /**
     * @test
     */
    public function writeFloat()
    {
        $fileName = FileSystem::createTemporaryFile();
        $writer = new DataWriter(new FileWriter($fileName));
        $writer->writeFloat(1.25);
        $writer->writeFloat(3.1415927410126);

        $data = file_get_contents($fileName);
        list(, $actual1, $actual2) = unpack('f*', $data);
        $this->assertSame(1.25, $actual1);
        $this->assertSame(3.1415927410126, $actual2);
    }

    /**
     * @test
     */
    public function writeDouble()
    {
        $fileName = FileSystem::createTemporaryFile();
        $writer = new DataWriter(new FileWriter($fileName));
        $writer->writeDouble(1.25);
        $writer->writeDouble(3.1415927410126);

        $data = file_get_contents($fileName);
        list(, $actual1, $actual2) = unpack('d*', $data);
        $this->assertSame(1.25, $actual1);
        $this->assertSame(3.1415927410126, $actual2);
    }
}