<?php

/**
 * Wind Speed measurement.
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
 * Wind Speed measurement class.
 */
class Wind_Speed extends Base_Measurement {

	/**
	 * Unit constants for wind speed.
	 */
	public const UNIT_METERS_PER_SECOND   = 'm/s';
	public const UNIT_KILOMETERS_PER_HOUR = 'km/h';
	public const UNIT_KNOTS               = 'knots';
	public const UNIT_MILES_PER_HOUR      = 'mph';
	public const UNIT_BEAUFORT            = 'BFT';
	public const UNIT_FEET_PER_MINUTE     = 'fpm';

	/**
	 * Get the measurement type identifier.
	 *
	 * @return string The measurement type.
	 */
	public function get_type(): string {
		return self::TYPE_WIND_SPEED;
	}
}
