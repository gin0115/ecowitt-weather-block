<?php

/**
 * Base Measurement class for unit conversions.
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Observation\Measurement;

use DateTime;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\Measurement;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

/**
 * Abstract base class for all measurement objects with unit conversion capabilities.
 */
abstract class Base_Measurement {

	/**
	 * Measurement type constants.
	 */
	public const TYPE_TEMPERATURE     = 'temperature';
	public const TYPE_HUMIDITY        = 'humidity';
	public const TYPE_PRESSURE        = 'pressure';
	public const TYPE_WIND_SPEED      = 'wind_speed';
	public const TYPE_WIND_DIRECTION  = 'wind_direction';
	public const TYPE_RAINFALL        = 'rainfall';
	public const TYPE_SOLAR_RADIATION = 'solar_radiation';
	public const TYPE_UV_INDEX        = 'uv_index';
	public const TYPE_DISTANCE        = 'distance';
	public const TYPE_AIR_QUALITY     = 'air_quality';
	public const TYPE_CO2             = 'co2';
	public const TYPE_SOIL_MOISTURE   = 'soil_moisture';
	public const TYPE_LEAF_WETNESS    = 'leaf_wetness';
	public const TYPE_BATTERY         = 'battery';
	public const TYPE_VOLTAGE         = 'voltage';
	public const TYPE_POWER           = 'power';
	public const TYPE_ENERGY          = 'energy';
	public const TYPE_FLOW_RATE       = 'flow_rate';
	public const TYPE_VOLUME          = 'volume';
	public const TYPE_PERCENTAGE      = 'percentage';
	public const TYPE_COUNT           = 'count';


	/**
	 * The value of the measurement.
	 *
	 * @var string
	 */
	protected readonly string $value;

	/**
	 * The unit of the measurement.
	 *
	 * @var string
	 */
	protected readonly string $unit;

	/**
	 * The timestamp when the measurement was taken.
	 *
	 * @var DateTime|null
	 */
	protected readonly ?DateTime $timestamp;

	/**
	 * Create a new measurement instance from a DTO.
	 *
	 * @param Measurement $measurement_dto The measurement DTO from the API.
	 */
	public function __construct( Measurement $measurement_dto ) {
		$this->value     = $measurement_dto->value;
		$this->unit      = $measurement_dto->unit;
		$this->timestamp = $this->parse_timestamp( $measurement_dto->timestamp );
	}

	/**
	 * Get the measurement type identifier.
	 *
	 * @return string The measurement type (e.g., 'temperature', 'wind_speed').
	 */
	abstract public function get_type(): string;

	/**
	 * Get the value.
	 *
	 * @return string
	 */
	public function get_value(): string {
		return $this->value;
	}

	/**
	 * Get the unit.
	 *
	 * @return string
	 */
	public function get_unit(): string {
		return $this->unit;
	}

	/**
	 * Get the timestamp.
	 *
	 * @return DateTime|null
	 */
	public function get_timestamp(): ?DateTime {
		return $this->timestamp;
	}



	/**
	 * Parse timestamp string to DateTime object.
	 *
	 * @param string $timestamp The timestamp string from the API.
	 * @return DateTime|null The parsed DateTime or null if invalid.
	 */
	private function parse_timestamp( string $timestamp ): ?DateTime {
		if ( empty( $timestamp ) ) {
			return null;
		}

		// Validate that the timestamp is numeric before casting
		if ( ! is_numeric( $timestamp ) ) {
			return null;
		}

		// API returns Unix timestamps as strings
		$unix_timestamp = (int) $timestamp;

		// Check if the timestamp is reasonable (not 0 or negative)
		if ( $unix_timestamp <= 0 ) {
			return null;
		}

		try {
			$date_time = DateTime::createFromFormat( 'U', (string) $unix_timestamp );
			// createFromFormat returns false on failure, not null
			return $date_time ?: null;
		} catch ( \Exception $e ) {
			return null;
		}
	}
}
