<?php

/**
 * Pressure measurement.
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
 * Pressure measurement class.
 */
class Pressure extends Base_Measurement {

	/**
	 * Unit constants for pressure.
	 */
	public const UNIT_HPA  = 'hPa';
	public const UNIT_INHG = 'inHg';
	public const UNIT_MMHG = 'mmHg';

	/**
	 * Get the measurement type identifier.
	 *
	 * @return string The measurement type.
	 */
	public function get_type(): string {
		return self::TYPE_PRESSURE;
	}
}
