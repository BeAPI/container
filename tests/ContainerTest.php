<?php

namespace Beapi\Container\Test;

use Beapi\Container\Container;
use Beapi\Container\Exception\IdentifierNotFound;
use Beapi\Container\Exception\OverrideNotAllowed;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase {

	private $container;

	public function setUp(): void {
		$this->container = new Container();
	}

	public function test_store_scalar_value() {

		$string  = 'this is a string';
		$integer = 42;
		$boolean = false;
		$array   = [ 'a string', 42, true ];

		$this->container
			->set( 'string', $string )
			->set( 'integer', $integer )
			->set( 'boolean', $boolean )
			->set( 'array', $array );

		$this->assertTrue( $this->container->has( 'string' ) );
		$this->assertTrue( $this->container->has( 'integer' ) );
		$this->assertTrue( $this->container->has( 'boolean' ) );
		$this->assertTrue( $this->container->has( 'array' ) );

		$this->assertSame( $string, $this->container->get( 'string' ) );
		$this->assertSame( $integer, $this->container->get( 'integer' ) );
		$this->assertSame( $boolean, $this->container->get( 'boolean' ) );
		$this->assertSame( $array, $this->container->get( 'array' ) );
	}

	public function test_store_closure() {

		$object = new \stdClass();

		$this->container->set( 'closure', function ( $container ) use ( $object ) {
			return $object;
		} );

		$this->assertTrue( $this->container->has( 'closure' ) );
		$this->assertSame( $object, $this->container->get( 'closure' ) );
	}

	public function test_return_same_instance_for_factory() {

		$object = new \stdClass();

		$this->container->set( 'closure', function ( $container ) use ( $object ) {
			return $object;
		} );

		$this->assertSame( $this->container->get( 'closure' ), $this->container->get( 'closure' ) );
	}

	public function test_throw_exception_for_unknown_id() {

		$this->expectException( IdentifierNotFound::class );

		$this->container->get( 'unkwown_name' );
	}

	public function test_throw_exception_if_value_already_exist() {

		$this->expectException( OverrideNotAllowed::class );

		$this->container->set( 'test', 42 );
		$this->container->set( 'test', 24 );
	}

	public function test_allow_override_for_factory() {

		$obj1 = new \stdClass();
		$obj2 = new \stdClass();

		$this->container->set( 'test', function ( $container ) use ( $obj1 ) {
			return $obj1;
		} );

		$this->assertTrue( $this->container->has( 'test' ) );

		$this->container->set( 'test', function ( $container ) use ( $obj2 ) {
			return $obj2;
		} );

		$this->assertTrue( $this->container->has( 'test' ) );
		$this->assertSame( $obj2, $this->container->get( 'test' ) );
	}
}
