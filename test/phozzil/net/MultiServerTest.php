<?php

use phozzil\net\MultiServer;

class MultiServerTest extends PHPUnit_Framework_TestCase
{
    private $instance;

    public function setUp()
    {
        $this->instance = new MultiServer();
    }

    /**
     * @test
     */
    public function setTimeout()
    {
        $this->instance->setTimeout(1000);
        $this->assertTrue(true);
    }

    static public function setTimeoutProvider()
    {
        return array(
                        array(-1000),
                        array('foo'),
                        );
    }

    /**
     * @test
     * @dataProvider setTimeoutProvider
     * @expectedException phozzil\lang\IllegalArgumentException
     */
    public function setTimeoutThrowsException($timeout)
    {
        $this->instance->setTimeout($timeout);
    }
}