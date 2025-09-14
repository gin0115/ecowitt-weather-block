<?php

/**
 * Unit Converter Service for converting measurements between units.
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Observation\Conversion;

use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Base_Measurement;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\Measurement;
use PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Exception\Unit_Conversion_Exception;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

/**
 * Service for converting measurements between different units.
 */
class Unit_Converter_Service {

	/**
	 * The conversion configuration.
	 *
	 * @var Conversion_Config_Interface
	 */
	private Conversion_Config_Interface $config;

	/**
	 * Create a new Unit Converter Service.
	 *
	 * @param Conversion_Config_Interface $config The conversion configuration.
	 */
	public function __construct( Conversion_Config_Interface $config ) {
		$this->config = $config;
	}

	/**
	 * Convert a measurement to a different unit.
	 *
	 * @param Base_Measurement $measurement The measurement to convert.
	 * @param string $target_unit The unit to convert to.
	 * @return Base_Measurement A new measurement object with the converted value.
	 * @throws Unit_Conversion_Exception If conversion is not supported.
	 */
	public function convert( Base_Measurement $measurement, string $target_unit ): Base_Measurement {
		// Sanitize user input
		$target_unit = sanitize_text_field( $target_unit );

		$measurement_type = $measurement->get_type();
		$current_unit     = $measurement->get_unit(); // Already from DTO, no need to sanitize
		$current_value    = $measurement->get_value(); // Keep as string for callables to handle

		// Get configuration for this measurement type
		$config_data = $this->config->get();
		if ( ! isset( $config_data[ $measurement_type ] ) ) {
			throw Unit_Conversion_Exception::unsupported_measurement_type( esc_html( $measurement_type ) );
		}

		// Check if target unit is supported
		if ( ! in_array( $target_unit, $config_data[ $measurement_type ]['units'], true ) ) {
			throw Unit_Conversion_Exception::unsupported_unit( esc_html( $target_unit ), esc_html( $measurement_type ) );
		}

		// If already in target unit, return as-is
		if ( $current_unit === $target_unit ) {
			return $measurement;
		}

		// Convert: current unit → base unit → target unit
		$base_value   = $this->convert_to_base( $current_value, $current_unit, $measurement_type );
		$target_value = $this->convert_from_base( $base_value, $target_unit, $measurement_type );

		// Apply formatting if available
		$formatted_value = $this->apply_formatting( $target_value, $target_unit, $measurement_type );

		// Create new DTO with converted value
		$converted_dto = new Measurement(
			(string) $formatted_value,
			$target_unit,
			$measurement->get_timestamp() ? $measurement->get_timestamp()->format( 'U' ) : ''
		);

		// Return new measurement object of the same type
		$class_name = get_class( $measurement );
		return new $class_name( $converted_dto );
	}

	/**
	 * Convert a value from given unit to base unit.
	 *
	 * @param string $value The value to convert.
	 * @param string $from_unit The unit to convert from.
	 * @param string $measurement_type The measurement type.
	 * @return mixed The value in base unit.
	 */
	private function convert_to_base( string $value, string $from_unit, string $measurement_type ) {
		$config = $this->config->get()[ $measurement_type ];

		if ( ! isset( $config['to_base_conversions'][ $from_unit ] ) ) {
			throw Unit_Conversion_Exception::missing_conversion_formula( esc_html( $from_unit ), 'to_base' );
		}

		$transform = $config['to_base_conversions'][ $from_unit ];

		if ( ! is_callable( $transform ) ) {
			throw Unit_Conversion_Exception::missing_conversion_formula( esc_html( $from_unit ), 'to_base' );
		}

		return $transform( $value );
	}

	/**
	 * Convert a value from base unit to target unit.
	 *
	 * @param mixed $base_value The value in base unit.
	 * @param string $to_unit The unit to convert to.
	 * @param string $measurement_type The measurement type.
	 * @return mixed The value in target unit.
	 */
	private function convert_from_base( $base_value, string $to_unit, string $measurement_type ) {
		$config = $this->config->get()[ $measurement_type ];

		if ( ! isset( $config['from_base_conversions'][ $to_unit ] ) ) {
			throw Unit_Conversion_Exception::missing_conversion_formula( esc_html( $to_unit ), 'from_base' );
		}

		$transform = $config['from_base_conversions'][ $to_unit ];

		if ( ! is_callable( $transform ) ) {
			throw Unit_Conversion_Exception::missing_conversion_formula( esc_html( $to_unit ), 'from_base' );
		}

		return $transform( $base_value );
	}

	/**
	 * Apply formatting to the converted value.
	 *
	 * @param mixed $value The value to format.
	 * @param string $unit The unit to format for.
	 * @param string $measurement_type The measurement type.
	 * @return mixed The formatted value.
	 */
	private function apply_formatting( $value, string $unit, string $measurement_type ) {
		$config = $this->config->get()[ $measurement_type ];

		if ( ! isset( $config['format'][ $unit ] ) ) {
			return $value;
		}

		$format = $config['format'][ $unit ];

		if ( ! is_callable( $format ) ) {
			return $value;
		}

		return $format( $value );
	}
}
