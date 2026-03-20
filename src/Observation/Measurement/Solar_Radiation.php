<?php

/**
 * Solar Radiation measurement.
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
 * Solar Radiation measurement class.
 */
class Solar_Radiation extends Base_Measurement {

	/**
	 * Unit constants for solar radiation.
	 */
	public const UNIT_LUX                    = 'lux';
	public const UNIT_FOOT_CANDLES           = 'fc';
	public const UNIT_WATTS_PER_SQUARE_METER = 'W/m2';

	/**
	 * @param Measurement $measurement_dto The measurement DTO from the API.
	 */
	public function __construct( Measurement $measurement_dto ) {
		parent::__construct( $measurement_dto, self::TYPE_SOLAR_RADIATION );
	}
}
