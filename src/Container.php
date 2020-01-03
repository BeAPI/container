<?php

namespace Beapi\Container;

use Beapi\Container\Exception\IdentifierNotFound;
use Beapi\Container\Exception\OverrideNotAllowed;
use Psr\Container\ContainerInterface;

/**
 * Simple container implementation.
 *
 * @package Beapi\container
 */
class Container implements ContainerInterface {

	/**
	 * @var callable[]
	 */
	private $factories = [];

	/**
	 * @var array
	 */
	private $values = [];

	public function __construct( array $values = [] ) {

		foreach ( $values as $id => $value ) {
			$this->set( $id, $value );
		}
	}

	/**
	 * Retrieve a service or a value from the container.
	 *
	 * @param string $id
	 *
	 * @return mixed
	 * @see ContainerInterface::get()
	 */
	public function get( $id ) {
		if ( ! $this->has( $id ) ) {
			throw IdentifierNotFound::for_identifier( $id );
		}

		if ( ! array_key_exists( $id, $this->values ) ) {
			$this->values[ $id ] = $this->factories[$id]( $this );
		}

		return $this->values[ $id ];
	}

	/**
	 * Check whether a service or a value exist in the container for a particular name.
	 *
	 * @param string $id
	 *
	 * @return bool
	 * @see ContainerInterface::has()
	 */
	public function has( $id ): bool {
		return array_key_exists( $id, $this->values ) || array_key_exists( $id, $this->factories );
	}

	/**
	 * Store a service or a value in the container.
	 *
	 * @param string $id
	 * @param mixed $value
	 *
	 * @return Container
	 */
	public function set( string $id, $value ): self {

		if ( array_key_exists( $id, $this->values ) ) {
			throw OverrideNotAllowed::for_identifier( $id );
		}

		is_callable( $value )
			? $this->factories[ $id ] = $value
			: $this->values[ $id ] = $value;

		return $this;
	}
}
