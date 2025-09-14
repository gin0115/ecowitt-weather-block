<?php

/**
 * Unit Conversion Exception.
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Exception;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

/**
 * Unit Conversion Exception.
 *
 * Thrown when unit conversion errors occur.
 */
class Unit_Conversion_Exception extends Conversion_Exception {

	/**
	 * Create exception for unsupported measurement type.
	 *
	 * @param string $measurement_type
	 * @return self
	 */
	public static function unsupported_measurement_type( string $measurement_type ): self {
		return new self(
			sprintf(
				'No conversion configuration found for measurement type: %s',
				sanitize_text_field( $measurement_type )
			)
		);
	}

	/**
	 * Create exception for unsupported unit.
	 *
	 * @param string $unit
	 * @param string $measurement_type
	 * @return self
	 */
	public static function unsupported_unit( string $unit, string $measurement_type ): self {
		return new self(
			sprintf(
				'Unit \'%s\' is not supported for measurement type \'%s\'',
				sanitize_text_field( $unit ),
				sanitize_text_field( $measurement_type )
			)
		);
	}

	/**
	 * Create exception for missing conversion formula.
	 *
	 * @param string $unit
	 * @param string $direction 'to_base' or 'from_base'
	 * @return self
	 */
	public static function missing_conversion_formula( string $unit, string $direction ): self {
		return new self(
			sprintf(
				'No %s conversion formula found for unit: %s',
				sanitize_text_field( $direction ),
				sanitize_text_field( $unit )
			)
		);
	}

	/**
	 * Create exception for invalid conversion configuration.
	 *
	 * @param string $reason
	 * @return self
	 */
	public static function invalid_configuration( string $reason ): self {
		return new self(
			sprintf(
				'Invalid conversion configuration: %s',
				sanitize_text_field( $reason )
			)
		);
	}

	/**
	 * Create exception for conversion calculation failure.
	 *
	 * @param string $from_unit
	 * @param string $to_unit
	 * @param string $reason
	 * @return self
	 */
	public static function calculation_failed( string $from_unit, string $to_unit, string $reason ): self {
		return new self(
			sprintf(
				'Failed to convert from \'%s\' to \'%s\': %s',
				sanitize_text_field( $from_unit ),
				sanitize_text_field( $to_unit ),
				sanitize_text_field( $reason )
			)
		);
	}
}
