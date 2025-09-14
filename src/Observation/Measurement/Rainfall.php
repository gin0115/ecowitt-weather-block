<?php

/**
 * Rainfall measurement.
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
 * Rainfall measurement class.
 */
class Rainfall extends Base_Measurement {

	/**
	 * Unit constants for rainfall.
	 */
	public const UNIT_MILLIMETERS = 'mm';
	public const UNIT_INCHES      = 'in';

	/**
	 * Get the measurement type identifier.
	 *
	 * @return string The measurement type.
	 */
	public function get_type(): string {
		return self::TYPE_RAINFALL;
	}
}
