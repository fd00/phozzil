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
        $this->assertSame(MultiServer::DEFAULT_TIMEOUT, $this->instance->getTimeout());
        $this->instance->setTimeout(6543210);
        $this->assertSame(6543210, $this->instance->getTimeout());
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

    /**
     * @test
     * @expectedException phozzil\lang\IllegalArgumentException
     */
    public function addListenerThrowsException()
    {
        $listener = $this->getMock('phozzil\net\MultiServerListener');
        $this->instance->addListener($listener);
        $this->instance->addListener($listener);
    }
}