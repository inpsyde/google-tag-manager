<?php

namespace Inpsyde\GoogleTagManager\Tests\Unit\Http;

use Inpsyde\GoogleTagManager\Http\Request;
use Inpsyde\GoogleTagManager\Tests\Unit\AbstractTestCase;

class RequestTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function testBasic(): void
    {
        $testee = new Request();

        $this->assertSame(
            [],
            $testee->cookies()
                ->all()
        );
        $this->assertSame(
            [],
            $testee->data()
                ->all()
        );
        $this->assertSame(
            [],
            $testee->query()
                ->all()
        );
        $this->assertSame(
            [],
            $testee->server()
                ->all()
        );
    }

    /**
     * Testing to set query in constructor and access it again.
     *
     * @test
     */
    public function testSetGetQuery(): void
    {
        $key = 'foo';
        $expected_value = 'bar';
        $expected_data = [$key => $expected_value];
        $testee = new Request($expected_data);

        $this->assertSame(
            $expected_data,
            $testee->query()
                ->all()
        );
        $this->assertSame(
            $expected_value,
            $testee->query()
                ->get($key)
        );
    }

    /**
     * Test access data in ServiceRequest after creation from global values.
     *
     * @test
     */
    public function testFromGlobals(): void
    {
        $_GET['foo1'] = 'bar1';
        $_POST['foo2'] = 'bar2';
        $_COOKIE['foo3'] = 'bar3';
        $_SERVER['foo4'] = 'bar4';

        $testee = Request::fromGlobals();

        $this->assertSame(
            'bar1',
            $testee->query()
                ->get('foo1')
        );
        $this->assertSame(
            'bar2',
            $testee->data()
                ->get('foo2')
        );
        $this->assertSame(
            'bar3',
            $testee->cookies()
                ->get('foo3')
        );
        $this->assertSame(
            'bar4',
            $testee->server()
                ->get('foo4')
        );
    }
}