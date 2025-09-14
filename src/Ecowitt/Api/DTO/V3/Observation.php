<?php

/**
 * Observation DTO for Ecowitt API v3 responses.
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

/**
 * Observation DTO class for Ecowitt API v3.
 *
 * TODO: Implement observation properties based on API response structure
 */
class Observation {

	/**
	 * Group of observations.
	 *
	 * @var array<string, array<string, Measurement>>
	 */
	public array $observations;

	/**
	 * Placeholder constructor.
	 * @var array<string, array<string, Measurement>>
	 */

	/**
	 * Placeholder constructor.
	 */
	public function __construct( array $observations ) {
		$this->observations = $observations;
	}

	/**
	 * Create Observation from API v3 response array.
	 *
	 * @param array<string, mixed> $data Observation data from API v3 response.
	 * @return Observation Observation instance.
	 */
	public static function from_array( array $data ): Observation {
		// TODO: Implement observation creation from array
		return new self( $data );
	}
}
