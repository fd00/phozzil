<?php

use phozzil\io\FileSystem;
use phozzil\lang\ProcessBuilder;

class ProcessBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function start()
    {
        $builder = new ProcessBuilder(array('php', '-r', '"exit(4);"'));
        $process = $builder->start();
        $process->close();
        $this->assertSame(4, $process->exitValue());
    }

    /**
     * @test
     */
    public function setAndGetDirectory()
    {
        $builder = new ProcessBuilder(array());
        $builder->setDirectory(sys_get_temp_dir());
        $this->assertSame(sys_get_temp_dir(), $builder->getDirectory());
    }

    /**
     * @test
     */
    public function setAndGetAndUnsetEnvironment()
    {
        $builder = new ProcessBuilder(array());
        $this->assertSame('', $builder['undefined_env_bar_baz']);
        $builder['undefined_env_bar_baz'] = 'qwerty';
        $this->assertSame('qwerty', $builder['undefined_env_bar_baz']);
        unset($builder['undefined_env_bar_baz']);
        $this->assertSame('', $builder['undefined_env_bar_baz']);
    }

    /**
     * @test
     * @expectedException phozzil\lang\IllegalArgumentException
     */
    public function setEnvironmentWithKeyObject()
    {
        $builder = new ProcessBuilder(array());
        $builder[new stdClass()] = 'qwerty';
    }

    /**
     * @test
     * @expectedException phozzil\lang\IllegalArgumentException
     */
    public function setEnvironmentWithValueObject()
    {
        $builder = new ProcessBuilder(array());
        $builder['undefined_env_bar_baz'] = new stdClass();
    }

    /**
     * @test
     * @expectedException phozzil\lang\IllegalArgumentException
     */
    public function getEnvironmentWithKeyObject()
    {
        $builder = new ProcessBuilder(array());
        $builder[new stdClass()];
    }
}