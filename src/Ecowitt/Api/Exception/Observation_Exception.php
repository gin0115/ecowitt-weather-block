<?php

/**
 * Observation Exception.
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Exception;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Observation Exception.
 *
 * Thrown when observation-related errors occur.
 */
class Observation_Exception extends Ecowitt_Exception {

	/**
	 * Create exception for observation not found.
	 *
	 * @param string $device_id
	 * @return self
	 */
	public static function observation_not_found( string $device_id ): self {
		return new self( "No observations found for device '{$device_id}'." );
	}

	/**
	 * Create exception for invalid date range.
	 *
	 * @param string $reason
	 * @return self
	 */
	public static function invalid_date_range( string $reason ): self {
		return new self( "Invalid date range: {$reason}" );
	}

	/**
	 * Create exception for failed observation retrieval.
	 *
	 * @param string $reason
	 * @return self
	 */
	public static function retrieval_failed( string $reason ): self {
		return new self( "Failed to retrieve observations: {$reason}" );
	}

}
