<?php

/**
 * Holds an ecoiwtt API Connection.
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 *
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection;

use JsonSerializable;

/**
 * Holds an ecoiwtt API Connection.
 */
class Connection implements JsonSerializable {

	/**
	 * Holds the key.
	 *
	 * @var string
	 */
	protected string $key;

	/**
	 * Holds the Application key.
	 *
	 * @var string
	 */
	protected string $application_key;

	/**
	 * Holds the API key.
	 *
	 * @var string
	 */
	protected string $api_key;

	/**
	 * Holds the MAC address.
	 *
	 * @var string
	 */
	protected string $mac_address;

	/**
	 * Connection Description.
	 *
	 * @var string
	 */
	protected string $description;

	/**
	 * Connection Name.
	 *
	 * @var string
	 */
	protected string $name;

	/**
	 * Creates an instance of the Credential.
	 *
	 * @param string $key             The key.
	 * @param string $application_key The Application key.
	 * @param string $api_key         The API key.
	 * @param string $mac_address     The MAC address.
	 * @param string $description     The description.
	 * @param string $name            The name.
	 */
	public function __construct( string $key, string $application_key, string $api_key, string $mac_address, string $description, string $name ) {
		$this->key             = \esc_attr( $key );
		$this->application_key = \esc_attr( $application_key );
		$this->api_key         = \esc_attr( $api_key );
		$this->mac_address     = \esc_attr( $mac_address );
		$this->description     = \esc_attr( $description );
		$this->name            = \esc_attr( $name );
	}

	/**
	 * Access to the key.
	 *
	 * @return string
	 */
	public function key(): string {
		return $this->key;
	}

	/**
	 * Access to the Application key.
	 *
	 * @return string
	 */
	public function application_key(): string {
		return $this->application_key;
	}

	/**
	 * Access to the API key.
	 *
	 * @return string
	 */
	public function api_key(): string {
		return $this->api_key;
	}

	/**
	 * Access to the MAC address.
	 *
	 * @return string
	 */
	public function mac_address(): string {
		return $this->mac_address;
	}

	/**
	 * Access to the description.
	 *
	 * @return string
	 */
	public function description(): string {
		return $this->description;
	}

	/**
	 * Access to the name.
	 *
	 * @return string
	 */
	public function name(): string {
		return $this->name;
	}

	/**
	 * Simple serialisation of the connection.
	 *
	 * @return array{key: string, application_key: string, api_key: string, mac_address: string, description: string, name: string}
	 */
	public function jsonSerialize(): array {
		return array(
			'key'             => $this->key,
			'application_key' => $this->application_key,
			'api_key'         => $this->api_key,
			'mac_address'     => $this->mac_address,
			'description'     => $this->description,
			'name'            => $this->name,
		);
	}

	/**
	 * Creates an instance of the Connection from an array.
	 *
	 * @param array{key: string, application_key: string, api_key: string, mac_address: string, description: string, name: string} $data
	 * @return self
	 */
	public static function from_array( array $data ): self {
		return new self(
			$data['key'],
			$data['application_key'],
			$data['api_key'],
			$data['mac_address'],
			$data['description'],
			$data['name']
		);
	}
}
