<?php

/**
 * Volume measurement.
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
 * Volume measurement class.
 */
class Volume extends Base_Measurement {

	/**
	 * Unit constants for volume.
	 */
	public const UNIT_LITERS       = 'L';
	public const UNIT_CUBIC_METERS = 'm3';
	public const UNIT_GALLONS      = 'gal';

	/**
	 * @param Measurement $measurement_dto The measurement DTO from the API.
	 */
	public function __construct( Measurement $measurement_dto ) {
		parent::__construct( $measurement_dto, self::TYPE_VOLUME );
	}
}
