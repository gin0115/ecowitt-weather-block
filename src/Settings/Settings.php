<?php

/**
 * Holds the settings for the plugin.
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 *
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Settings;

use JsonSerializable;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connections;

/**
 * Holds the settings for the plugin.
 */
class Settings implements JsonSerializable {

	/**
	 * Holds the connection data.
	 *
	 * @var Connections
	 */
	protected Connections $connections;

	/**
	 * Creates an instance of the Settings.
	 *
	 * @param Connections $connections The connections.
	 */
	public function __construct( Connections $connections ) {
		$this->connections = $connections;
	}


	/**
	 * Creates an instance of the Settings from a JSON string.
	 *
	 * @param string $json
	 */
	public static function from_json( string $json ): self {
		$data        = json_decode( $json, true );
		$connections = is_array( $data ) && isset( $data['connections'] ) && is_array( $data['connections'] )
			? $data['connections']
			: array();
		$connections = Connections::from_array( $connections );
		return new self( $connections );
	}

	/**
	 * Access to the connections.
	 *
	 * @return Connections
	 */
	public function connections(): Connections {
		return $this->connections;
	}


	/**
	 * Returns the settings as an array.
	 *
	 * @return array<string, mixed>
	 */
	public function jsonSerialize(): array {
		return array(
			'connections' => $this->connections,
		);
	}
}
