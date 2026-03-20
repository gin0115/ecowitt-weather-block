<?php

/**
 * Serializes Observation and History_Observation data for REST responses.
 *
 * Handles both live (single measurement per field) and history (time-series
 * array per field), returning all unit variants in both cases.
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Rest;

use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\Observation;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\History_Observation;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Base_Measurement;
use PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Unit_Converter_Service;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

/**
 * Converts Observation or History_Observation into enriched JSON structures
 * with all available unit variants per measurement field.
 */
class Measurement_Serializer {

	/**
	 * Unit converter service.
	 *
	 * @var Unit_Converter_Service
	 */
	private Unit_Converter_Service $converter;

	/**
	 * Constructor.
	 *
	 * @param Unit_Converter_Service $converter Unit converter service.
	 */
	public function __construct( Unit_Converter_Service $converter ) {
		$this->converter = $converter;
	}

	/**
	 * Serialize a live observation with all unit variants per measurement.
	 *
	 * @param Observation $observation The observation to serialize.
	 * @return array<string, array<string, array<int, array{value: string, label: string, unit: string}>>>
	 */
	public function serialize_observation( Observation $observation ): array {
		$result = array();

		foreach ( $observation->observations as $group_key => $group_data ) {
			if ( ! is_array( $group_data ) ) {
				continue;
			}

			$result[ $group_key ] = $this->serialize_group( $group_data );
		}

		return $result;
	}

	/**
	 * Serialize a history observation into time-series format with unit variants.
	 *
	 * @param History_Observation $history The history observation to serialize.
	 * @return array<string, array<string, mixed>>
	 */
	public function serialize( History_Observation $history ): array {
		$result = array();

		foreach ( $history->observations as $group_key => $group_data ) {
			if ( ! is_array( $group_data ) ) {
				continue;
			}

			$result[ $group_key ] = $this->serialize_group( $group_data );
		}

		return $result;
	}

	/**
	 * Serialize a single observation group.
	 *
	 * Handles three cases:
	 * - Single Base_Measurement (live) → get_all_variants()
	 * - Array of Base_Measurement (history time-series) → serialize_series()
	 * - Nested array (channel data) → recurse
	 *
	 * @param array<string, mixed> $group_data The group data.
	 * @return array<string, mixed>
	 */
	private function serialize_group( array $group_data ): array {
		$result = array();

		foreach ( $group_data as $field_key => $field_value ) {
			if ( $field_value instanceof Base_Measurement ) {
				// Single measurement (live) — return all unit variants.
				$result[ $field_key ] = $this->converter->get_all_variants( $field_value );
			} elseif ( is_array( $field_value ) && ! empty( $field_value ) ) {
				$first = reset( $field_value );

				if ( $first instanceof Base_Measurement ) {
					// Array of measurements (history time-series) — serialize with variants.
					$result[ $field_key ] = $this->serialize_series( $field_value );
				} elseif ( is_array( $first ) ) {
					// Nested group (e.g. channel data) — recurse.
					$result[ $field_key ] = $this->serialize_group( $field_value );
				}
			}
		}

		return $result;
	}

	/**
	 * Serialize a time-series array of measurements.
	 *
	 * Returns all unit variants per field so the frontend can offer unit switching.
	 *
	 * @param Base_Measurement[] $measurements Array of measurements over time.
	 * @return array{type: string, variants: array<int, array{unit: string, label: string, data: array<int, array{timestamp: int, value: string}>}>}
	 */
	private function serialize_series( array $measurements ): array {
		$first = reset( $measurements );
		$type  = $first->get_type();

		// Discover all available units for this measurement type.
		$first_variants  = $this->converter->get_all_variants( $first );
		$available_units = array_map(
			function ( $v ) {
				return $v['unit'];
			},
			$first_variants
		);

		// Build a variant entry for each unit.
		$variants = array();
		foreach ( $available_units as $unit ) {
			$data = array();
			foreach ( $measurements as $measurement ) {
				$timestamp = $measurement->get_timestamp();
				$raw_value = $measurement->get_value();

				// Skip invalid/missing data points (API returns '-' for no data).
				if ( ! is_numeric( $raw_value ) ) {
					continue;
				}

				try {
					$converted = $this->converter->convert( $measurement, $unit );
					$value     = $converted->get_value();
				} catch ( \Throwable $e ) {
					$value = $raw_value;
				}

				$data[] = array(
					'timestamp' => $timestamp ? $timestamp->getTimestamp() : 0,
					'value'     => $value,
				);
			}

			$variants[] = array(
				'unit'  => $unit,
				'label' => $this->converter->get_unit_label( $unit ),
				'data'  => $data,
			);
		}

		return array(
			'type'     => $type,
			'variants' => $variants,
		);
	}
}
