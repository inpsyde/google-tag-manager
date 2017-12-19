<?php

namespace Inpsyde\GoogleTagManager\Tests\Unit\Http;

use Inpsyde\GoogleTagManager\Http\ParameterBag;
use Inpsyde\GoogleTagManager\Tests\Unit\AbstractTestCase;

class ParameterBagTest extends AbstractTestCase {

	/**
	 * Basic test for default values in class.
	 */
	public function test_basic() {

		$expected = [ 'foo' => 'bar' ];
		$testee   = new ParameterBag( $expected );

		$this->assertEquals( $expected, $testee->all() );
		$this->assertEquals( [ 'foo' ], $testee->keys() );
	}

	public function test_add() {

		$testee = new ParameterBag( [ 'foo' => 'bar' ] );
		$testee->add( [ 'bar' => 'bas' ] );
		$this->assertEquals( [ 'foo' => 'bar', 'bar' => 'bas' ], $testee->all() );
	}

	public function test_get() {

		$testee = new ParameterBag( [ 'foo' => 'bar', 'null' => NULL ] );
		$this->assertEquals( 'bar', $testee->get( 'foo' ) );
		$this->assertEquals( 'default', $testee->get( 'unknown', 'default' ) );
		$this->assertNull( $testee->get( 'null', 'default' ) );
	}

	public function test_set() {

		$testee = new ParameterBag();

		$testee->set( 'foo', 'bar' );
		$this->assertEquals( 'bar', $testee->get( 'foo' ) );

		$testee->set( 'foo', 'baz' );
		$this->assertEquals( 'baz', $testee->get( 'foo' ) );
	}

	public function test_has() {

		$testee = new ParameterBag( [ 'foo' => 'bar' ] );
		$this->assertTrue( $testee->has( 'foo' ) );
		$this->assertFalse( $testee->has( 'unknown' ) );
	}

	public function test_get_iterator() {

		$parameters = [ 'foo' => 'bar', 'hello' => 'world' ];
		$testee     = new ParameterBag( $parameters );
		$i          = 0;
		foreach ( $testee as $key => $val ) {
			$i ++;
			$this->assertEquals( $parameters[ $key ], $val );
		}
		$this->assertCount( $i, $parameters );
	}

	public function test_count() {

		$parameters = [ 'foo' => 'bar', 'hello' => 'world' ];
		$testee     = new ParameterBag( $parameters );
		$this->assertEquals( count( $parameters ), count( $testee ) );
	}
}