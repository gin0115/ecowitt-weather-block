<?php

/**
 * Volume measurement.
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
 * Volume measurement class.
 */
class Volume extends Base_Measurement {

	/**
	 * Unit constants for volume.
	 */
	public const UNIT_LITERS       = 'L';
	public const UNIT_CUBIC_METERS = 'm³';
	public const UNIT_GALLONS      = 'gal';

	/**
	 * Get the measurement type identifier.
	 *
	 * @return string The measurement type.
	 */
	public function get_type(): string {
		return self::TYPE_VOLUME;
	}
}
