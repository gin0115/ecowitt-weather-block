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
 * Temperature measurement class with Celsius and Fahrenheit support.
 */
class Temperature extends Base_Measurement {

	/**
	 * Unit constants for temperature.
	 */
	public const UNIT_CELSIUS    = 'C';
	public const UNIT_FAHRENHEIT = 'F';

	/**
	 * @param Measurement $measurement_dto The measurement DTO from the API.
	 */
	public function __construct( Measurement $measurement_dto ) {
		parent::__construct( $measurement_dto, self::TYPE_TEMPERATURE );
	}
}
