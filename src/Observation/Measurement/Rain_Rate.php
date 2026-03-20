<?php

/**
 * Rain Rate measurement.
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
 * Rain Rate measurement class for instantaneous rainfall rates.
 */
class Rain_Rate extends Base_Measurement {

	/**
	 * Unit constants for rain rate.
	 */
	public const UNIT_INCHES_PER_HOUR      = 'in/hr';
	public const UNIT_MILLIMETERS_PER_HOUR = 'mm/hr';

	/**
	 * @param Measurement $measurement_dto The measurement DTO from the API.
	 */
	public function __construct( Measurement $measurement_dto ) {
		parent::__construct( $measurement_dto, self::TYPE_RAIN_RATE );
	}
}
