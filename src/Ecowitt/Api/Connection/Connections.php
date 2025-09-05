<?php

/**
 * Holds the connection data.
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 *
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection;

use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connection;
use JsonSerializable;

/**
 * Holds the connection data.
 */
class Connections implements JsonSerializable {

	/**
	 * Holds the connection data.
	 *
	 * @var array<int, array{key: string, api_key: string, api_secret: string, mac_address: string}>
	 */
	protected array $connections = array();

	/**
	 * Create a new instance of the connections.
	 *
	 * @param array<int, Connection> $connections
	 */
	public function __construct( array $connections = array() ) {
		$this->connections = array_filter( $connections, fn( $connection ) => $connection instanceof Connection );
	}

	/**
	 * Creates an instance of the Connections from arrays
	 *
	 * @param array<int, array{key: string, api_key: string, api_secret: string, mac_address: string, ?de}> $connections
	 *
	 * @return self
	 */
	public static function from_array( array $connections ): self {
		// Map the connections to Connection objects.
		$connections = array_map(
			function ( $data ) {
				// If we dont have an array, return null.
				if ( ! is_array( $data ) ) {
					return null;
				}

				// If dont have the required keys, return null.
				$required = array( 'key', 'api_key', 'api_secret', 'mac_address' );
				if ( count( array_intersect( $required, array_keys( $data ) ) ) !== count( $required ) ) {
					return null;
				}

				return new Connection(
					esc_attr( $data['key'] ),
					esc_attr( $data['api_key'] ),
					esc_attr( $data['api_secret'] ),
					esc_attr( $data['mac_address'] ),
					isset( $data['description'] ) ? esc_attr( $data['description'] ) : '',
					isset( $data['name'] ) ? esc_attr( $data['name'] ) : ''
				);
			},
			$connections
		);

		return new self( $connections );
	}

	/**
	 * Adds a connection.
	 *
	 * @param string $key         The key for the connection.
	 * @param string $api_key     The API key.
	 * @param string $api_secret  The API secret.
	 * @param string $mac_address The MAC address.
	 * @param string $description The description.
	 * @param string $name        The name.
	 *
	 * @return void
	 */
	public function add(
		string $key,
		string $api_key,
		string $api_secret,
		string $mac_address,
		string $description = '',
		string $name = ''
	): void {
		$this->connections[] = new Connection( $key, $api_key, $api_secret, $mac_address, $description, $name );
	}

	/**
	 * Gets a connections index based on the key.
	 *
	 * @param string $key The key for the connection.
	 *
	 * @return integer|null
	 */
	public function index( string $key ): ?int {
		foreach ( $this->connections as $index => $connection ) {
			if ( $connection->key() === $key ) {
				return $index;
			}
		}
		return null;
	}

	/**
	 * Checks if we have a connection.
	 *
	 * @param string $key The key for the connection.
	 *
	 * @return boolean
	 */
	public function has( string $key ): bool {
		$connection = $this->index( $key );
		return $connection !== null;
	}

	/**
	 * Gets a connections details.
	 *
	 * @param string $key The key for the connection.
	 *
	 * @return Connection|null
	 */
	public function get( string $key ): ?Connection {
		$index = $this->index( $key );
		if ( $index === null ) {
			return null;
		}
		return $this->connections[ $index ];
	}

	/**
	 * Gets all connections.
	 *
	 * @return array<int, array{key: string, api_key: string, api_secret: string, mac_address: string}>
	 */
	public function all(): array {
		return $this->connections;
	}

	/**
	 * Removes a connection.
	 *
	 * @param string $key The key for the connection.
	 *
	 * @return void
	 */
	public function remove( string $key ): void {
		$index = $this->index( $key );
		if ( $index !== null ) {
			unset( $this->connections[ $index ] );
		}
	}

	/**
	 * Returns the settings as an array.
	 *
	 * @return array<int, array{key: string, api_key: string, api_secret: string, mac_address: string, description: string, name: string}>
	 */
	public function jsonSerialize(): array {
		return $this->connections;
	}
}
