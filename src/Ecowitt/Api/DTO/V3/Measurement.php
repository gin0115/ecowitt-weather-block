<?php

/**
 * Measurement DTO for Ecowitt API v3 responses.
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

/**
 * Measurement DTO class for Ecowitt API v3.
 */
class Measurement {

	/**
	 * The value of the measurement.
	 *
	 * @var string
	 */
	public string $value;

	/**
	 * The unit of the measurement.
	 *
	 * @var string
	 */
	public string $unit;

	/**
	 * The timestamp of the measurement.
	 *
	 * @var string
	 */
	public string $timestamp;

	/**
	 * Create an instance of
	 *
	 * @param string $value The value of the measurement.
	 * @param string $unit The unit of the measurement.
	 * @param string $timestamp The timestamp of the measurement.
	 */
	public function __construct( string $value, string $unit, string $timestamp ) {
		$this->value     = esc_attr( $value );
		$this->unit      = esc_attr( $unit );
		$this->timestamp = esc_attr( $timestamp );
	}

	/**
	 * Create a new measurement instance from an array.
	 *
	 * @param array<mixed> $data The measurement data from the API.
	 * @return Measurement The measurement instance.
	 */
	public static function from_array( array $data ): Measurement {
		return new self(
			is_string( $data['value'] ) ? esc_attr( $data['value'] ) : '',
			is_string( $data['unit'] ) ? esc_attr( $data['unit'] ) : '',
			is_string( $data['time'] ) ? esc_attr( $data['time'] ) : ''
		);
	}
}
