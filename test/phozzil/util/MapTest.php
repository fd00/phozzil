<?php

use phozzil\util\Map;

class MapTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function ArrayAccess()
    {
        $instance = new Map();
        $this->assertSame(0, $instance->count());

        $k1 = new \stdClass();
        $instance[$k1] = 'v1';
        $this->assertSame(1, $instance->count());
        $this->assertSame('v1', $instance[$k1]);

        unset($instance[$k1]);
        $this->assertSame(0, $instance->count());
    }

    /**
     * @test
     */
    public function each()
    {
        $instance = new Map();
        $k1 = new \stdClass();
        $k2 = new \stdClass();
        $instance[$k1] = 'v1';
        $instance[$k2] = 'v2';
        $flipped = array();
        $instance->each(function($key, $value) use(&$flipped)
        {
            $flipped[$value] = $key;
        });
        $this->assertSame(2, count($flipped));
        $this->assertSame($k1, $flipped['v1']);
        $this->assertSame($k2, $flipped['v2']);
    }

    /**
     * @test
     * @expectedException phozzil\lang\IllegalArgumentException
     */
    public function eachThrowIllegalArgumentException()
    {
        $instance = new Map();
        $instance->each(null);
    }
}