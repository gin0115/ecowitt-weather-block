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
	 * Holds the API key.
	 *
	 * @var string
	 */
	protected string $api_key;

	/**
	 * Holds the API secret.
	 *
	 * @var string
	 */
	protected string $api_secret;

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
	 * @param string $key         The key.
	 * @param string $api_key     The API key.
	 * @param string $api_secret  The API secret.
	 * @param string $mac_address The MAC address.
	 * @param string $description The description.
	 * @param string $name        The name.
	 */
	public function __construct( string $key, string $api_key, string $api_secret, string $mac_address, string $description, string $name ) {
		$this->key         = \esc_attr( $key );
		$this->api_key     = \esc_attr( $api_key );
		$this->api_secret  = \esc_attr( $api_secret );
		$this->mac_address = \esc_attr( $mac_address );
		$this->description = \esc_attr( $description );
		$this->name        = \esc_attr( $name );
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
	 * Access to the API key.
	 *
	 * @return string
	 */
	public function api_key(): string {
		return $this->api_key;
	}

	/**
	 * Access to the API secret.
	 *
	 * @return string
	 */
	public function api_secret(): string {
		return $this->api_secret;
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
	 * @return array{key: string, api_key: string, api_secret: string, mac_address: string, description: string, name: string}
	 */
	public function jsonSerialize(): array {
		return array(
			'key'         => $this->key,
			'api_key'     => $this->api_key,
			'api_secret'  => $this->api_secret,
			'mac_address' => $this->mac_address,
			'description' => $this->description,
			'name'        => $this->name,
		);
	}
}
