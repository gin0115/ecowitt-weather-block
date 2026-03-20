<?php

/**
 * History Measurement DTO for Ecowitt API v3 history responses.
 *
 * Unlike the real-time Measurement DTO (single value + timestamp),
 * this represents a time series: unit + list of timestamp => value pairs.
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
 * History Measurement DTO class for Ecowitt API v3.
 */
class History_Measurement {

	/**
	 * The unit of the measurement.
	 *
	 * @var string
	 */
	public string $unit;

	/**
	 * The time series data as timestamp => value pairs.
	 *
	 * @var array<string, string>
	 */
	public array $list;

	/**
	 * Create an instance.
	 *
	 * @param string                $unit      The unit of the measurement.
	 * @param array<string, string> $list_data The time series data.
	 */
	public function __construct( string $unit, array $list_data ) {
		$this->unit = esc_attr( $unit );
		$this->list = $list_data;
	}

	/**
	 * Create a new instance from an API response array.
	 *
	 * Expected format: { "unit": "ºF", "list": { "1772323200": "38.6", ... } }
	 *
	 * @param array<mixed> $data The measurement data from the API.
	 * @return History_Measurement The instance.
	 */
	public static function from_array( array $data ): History_Measurement {
		$unit = isset( $data['unit'] ) && is_string( $data['unit'] ) ? $data['unit'] : '';
		$list = isset( $data['list'] ) && is_array( $data['list'] ) ? $data['list'] : array();

		// Sanitise the list values.
		$sanitised_list = array();
		foreach ( $list as $timestamp => $value ) {
			$sanitised_list[ esc_attr( (string) $timestamp ) ] = esc_attr( is_scalar( $value ) ? (string) $value : '' );
		}

		return new self( $unit, $sanitised_list );
	}
}
