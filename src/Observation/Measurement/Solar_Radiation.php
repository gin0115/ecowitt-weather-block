<?php

/**
 * Solar Radiation measurement.
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
 * Solar Radiation measurement class.
 */
class Solar_Radiation extends Base_Measurement {

	/**
	 * Unit constants for solar radiation.
	 */
	public const UNIT_LUX                    = 'lux';
	public const UNIT_FOOT_CANDLES           = 'fc';
	public const UNIT_WATTS_PER_SQUARE_METER = 'W/m²';

	/**
	 * Get the measurement type identifier.
	 *
	 * @return string The measurement type.
	 */
	public function get_type(): string {
		return self::TYPE_SOLAR_RADIATION;
	}
}
