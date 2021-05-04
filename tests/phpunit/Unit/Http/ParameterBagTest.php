<?php

namespace Inpsyde\GoogleTagManager\Tests\Unit\Http;

use Inpsyde\GoogleTagManager\Http\ParameterBag;
use Inpsyde\GoogleTagManager\Tests\Unit\AbstractTestCase;

class ParameterBagTest extends AbstractTestCase
{

    /**
     * @test
     */
    public function testBasic(): void
    {

        $expected = ['foo' => 'bar'];
        $testee   = new ParameterBag($expected);

        $this->assertEquals($expected, $testee->all());
        $this->assertEquals(['foo'], $testee->keys());
    }

    /**
     * @test
     */
    public function testAdd(): void
    {

        $testee = new ParameterBag(['foo' => 'bar']);
        $testee->add(['bar' => 'bas']);
        $this->assertEquals(['foo' => 'bar', 'bar' => 'bas'], $testee->all());
    }

    /**
     * @test
     */
    public function testGet(): void
    {

        $testee = new ParameterBag(['foo' => 'bar', 'null' => null]);
        $this->assertEquals('bar', $testee->get('foo'));
        $this->assertEquals('default', $testee->get('unknown', 'default'));
        $this->assertNull($testee->get('null', 'default'));
    }

    /**
     * @test
     */
    public function testSet(): void
    {

        $testee = new ParameterBag();

        $testee->set('foo', 'bar');
        $this->assertEquals('bar', $testee->get('foo'));

        $testee->set('foo', 'baz');
        $this->assertEquals('baz', $testee->get('foo'));
    }

    /**
     * @test
     */
    public function testHas(): void
    {

        $testee = new ParameterBag(['foo' => 'bar']);
        $this->assertTrue($testee->has('foo'));
        $this->assertFalse($testee->has('unknown'));
    }

    /**
     * @test
     */
    public function testGetIterator(): void
    {

        $parameters = ['foo' => 'bar', 'hello' => 'world'];
        $testee     = new ParameterBag($parameters);
        $i          = 0;
        foreach ($testee as $key => $val) {
            $i++;
            $this->assertEquals($parameters[ $key ], $val);
        }
        $this->assertCount($i, $parameters);
    }

    /**
     * @test
     */
    public function testCount(): void
    {

        $parameters = ['foo' => 'bar', 'hello' => 'world'];
        $testee     = new ParameterBag($parameters);
        $this->assertEquals(count($parameters), count($testee));
    }
}