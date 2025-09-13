<?php

/**
 * Wind Direction measurement.
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Observation\Measurement;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

/**
 * Wind Direction measurement class.
 */
class Wind_Direction extends Base_Measurement {

	/**
	 * Unit constants for wind direction.
	 */
	public const UNIT_DEGREES  = '°';
	public const UNIT_CARDINAL = 'cardinal';

	/**
	 * Get the measurement type identifier.
	 *
	 * @return string The measurement type.
	 */
	public function get_type(): string {
		return self::TYPE_WIND_DIRECTION;
	}
}
