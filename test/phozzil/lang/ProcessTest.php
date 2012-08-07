<?php

use phozzil\io\FileSystem;
use phozzil\lang\ProcessBuilder;

class ProcessTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function io()
    {
        $script = FileSystem::join(__DIR__, 'fixture', 'echo.php');
        $builder = new ProcessBuilder(array('php', $script));
        $process = $builder->start();

        $inWriter = $process->getStandardInput();
        $outReader = $process->getStandardOutput();
        $errReader = $process->getStandardError();

        $message = 'Hello, World!';
        $length = strlen($message);

        $inWriter->write($message);
        $inWriter->close();
        $this->assertSame($message, $outReader->read($length));
        $this->assertSame($message, $errReader->read($length));

        $process->close();
    }
}