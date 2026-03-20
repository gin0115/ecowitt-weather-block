<?php

/**
 * Pressure measurement.
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
	 * @param Measurement $measurement_dto The measurement DTO from the API.
	 */
	public function __construct( Measurement $measurement_dto ) {
		parent::__construct( $measurement_dto, self::TYPE_PRESSURE );
	}
}
