<?php

use phozzil\io\FileReader;
use phozzil\io\FileSystem;

class FileReaderTest extends \PHPUnit_Framework_TestCase
{
    static private $fixture;

    static public function setUpBeforeClass()
    {
        self::$fixture = FileSystem::createTemporaryFile();
        file_put_contents(self::$fixture, 'Hello, world!' . PHP_EOL . 'Good-bye, world!' . PHP_EOL);
    }

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
        $tmpfile = FileSystem::createTemporaryFile();
        chmod($tmpfile, 0);
        new FileReader($tmpfile);
    }

    /**
     * @test
     */
    public function read()
    {
        $reader = new FileReader(self::$fixture);
        $this->assertSame('Hello', $reader->read(5));
        $this->assertSame(', world!' . PHP_EOL . 'Good-bye, world!' . PHP_EOL, $reader->read());
        $this->assertSame('', $reader->read());
        $this->assertSame('', $reader->read(1));
    }

    /**
     * @test
     */
    public function close()
    {
        $reader = new FileReader(self::$fixture);
        $reader->close();
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function skip()
    {
        $reader = new FileReader(self::$fixture);
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
        $reader = new FileReader(self::$fixture);
        $reader->skip($length);
    }

    /**
     * @test
     */
    public function each()
    {
        $actuals = array();
        $reader = new FileReader(self::$fixture);
        $reader->each(function($line) use(&$actuals)
        {
            $actuals[] = $line;
        });
        $this->assertSame(2, count($actuals));
        $this->assertSame('Hello, world!' . PHP_EOL, $actuals[0]);
        $this->assertSame('Good-bye, world!' . PHP_EOL, $actuals[1]);
    }

    /**
     * @test
     * @expectedException phozzil\lang\IllegalArgumentException
     */
    public function eachThrowsIllegalArgumentException()
    {
        $reader = new FileReader(self::$fixture);
        $reader->each(null);
    }
}