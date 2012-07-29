<?php

use phozzil\io\FileSystem;
use phozzil\io\FileWriter;

class FileWriterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function write()
    {
        $tmpFile = FileSystem::createTemporaryFile();
        $writer = new FileWriter($tmpFile);
        $writer->write('Hello, world!' . PHP_EOL);
        $actual = file_get_contents($tmpFile);
        $this->assertSame('Hello, world!' . PHP_EOL, $actual);
        $writer->close();

        $writer = new FileWriter($tmpFile, true);
        $writer->write('Good-bye, world!' . PHP_EOL);
        $actual = file_get_contents($tmpFile);
        $this->assertSame('Hello, world!' . PHP_EOL . 'Good-bye, world!' . PHP_EOL, $actual);
        $writer->close();
    }
}