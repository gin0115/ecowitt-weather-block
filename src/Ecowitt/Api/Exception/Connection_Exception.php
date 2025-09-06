<?php

/**
 * Connection Exception.
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Exception;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Connection Exception.
 *
 * Thrown when connection-related errors occur.
 */
class Connection_Exception extends Ecowitt_Exception {

	/**
	 * Create exception for missing connection.
	 *
	 * @return self
	 */
	public static function missing_connection(): self {
		return new self( 'No connection has been set. Please use with_connection() method first.' );
	}

	/**
	 * Create exception for invalid connection.
	 *
	 * @param string $reason
	 * @return self
	 */
	public static function invalid_connection( string $reason ): self {
		return new self( "Invalid connection: {$reason}" );
	}

}
