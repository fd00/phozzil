<?php

use phozzil\util\Set;

class SetTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function test()
    {
        $instance = new Set();
        $this->assertSame(0, $instance->count());

        $object1 = new stdClass();
        $this->assertFalse($instance->contains($object1));
        $instance->attach($object1);
        $this->assertSame(1, $instance->count());
        $this->assertTrue($instance->contains($object1));

        $object2 = new stdClass();
        $instance->attach($object2);
        $this->assertSame(2, count($instance));

        $flipped = array();
        $instance->each(function($value) use(&$flipped)
        {
            $flipped[spl_object_hash($value)] = $value;
        });

        $this->assertSame(2, count($flipped));
        $this->assertSame($object1, $flipped[spl_object_hash($object1)]);
        $this->assertSame($object2, $flipped[spl_object_hash($object2)]);
    }


    /**
     * @test
     * @expectedException phozzil\lang\IllegalArgumentException
     */
    public function eachThrowIllegalArgumentException()
    {
        $instance = new Set();
        $instance->each(null);
    }
}