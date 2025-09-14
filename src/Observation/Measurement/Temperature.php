<?php

/**
 * Temperature measurement with unit conversion.
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Observation\Measurement;

use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\Measurement;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

/**
 * Temperature measurement class with Celsius, Fahrenheit, and Kelvin support.
 */
class Temperature extends Base_Measurement {

	/**
	 * Unit constants for temperature.
	 */
	public const UNIT_CELSIUS    = '℃';
	public const UNIT_FAHRENHEIT = '℉';

	/**
	 * Get the measurement type identifier.
	 *
	 * @return string The measurement type.
	 */
	public function get_type(): string {
		return self::TYPE_TEMPERATURE;
	}
}
