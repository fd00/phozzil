<?php

use phozzil\io\FileReader;

class FileReaderTest extends \PHPUnit_Framework_TestCase
{
    static public function setFileReaderThrowsFileNotFoundExceptionProvider()
    {
        return array(
                        array('FileNotFound'),
                        array(__DIR__),
        );
    }

    /**
     * @test
     * @dataProvider setFileReaderThrowsFileNotFoundExceptionProvider
     * @expectedException phozzil\io\FileNotFoundException
     */
    public function FileReaderThrowsFileNotFoundException($fileName)
    {
        new FileReader($fileName);
    }

    /**
     * @test
     * @expectedException phozzil\io\IOException
     */
    public function FileReaderThrowsIOException()
    {
        $tmpfile = tempnam(sys_get_temp_dir(), '');
        chmod($tmpfile, 0);
        new FileReader($tmpfile);
        unlink($tmpfile);
    }

    static private function getFixture()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'fixture' . DIRECTORY_SEPARATOR . 'FileReader.read.txt';
    }

    /**
     * @test
     */
    public function read()
    {
        $reader = new FileReader(self::getFixture());
        $this->assertSame('Hello', $reader->read(5));
        $this->assertSame(', world!' . PHP_EOL . 'Good-bye, world!' . PHP_EOL, $reader->read());
        $this->assertSame('', $reader->read());
        $this->assertSame('', $reader->read(1));
    }

    /**
     * @test
     */
    public function readAfterClosing()
    {
        $reader = new FileReader(self::getFixture());
        $reader->close();
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function skip()
    {
        $reader = new FileReader(self::getFixture());
        $this->assertSame(5, $reader->skip(5));
        $this->assertSame(', world!', $reader->read(8));
        $this->assertSame(18, $reader->skip(100));
    }

    static public function skipThrowsIllegalArgumentExceptionProvider()
    {
        return array(
                        array('NotInteger'),
                        array(-1024),
                        );
    }

    /**
     * @test
     * @dataProvider skipThrowsIllegalArgumentExceptionProvider
     * @expectedException phozzil\lang\IllegalArgumentException
     */
    public function skipThrowsIllegalArgumentException($length)
    {
        $reader = new FileReader(self::getFixture());
        $reader->skip($length);
    }
}