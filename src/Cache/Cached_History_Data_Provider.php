<?php

/**
 * Cached implementation of History_Data_Provider.
 *
 * Checks the local DB cache first, only hitting the API for missing data.
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Cache;

use DateTime;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connection;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service\History_Data_Provider;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service\Api_History_Data_Provider;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

/**
 * Cached History Data Provider.
 */
class Cached_History_Data_Provider implements History_Data_Provider {

	/**
	 * Cycle type intervals in seconds.
	 *
	 * @var array<string, int>
	 */
	private const CYCLE_INTERVALS = array(
		'5min'  => 300,
		'30min' => 1800,
		'1hour' => 3600,
		'4hour' => 14400,
		'1day'  => 86400,
	);

	/**
	 * Whether the last fetch was served from cache.
	 *
	 * @var bool
	 */
	private bool $last_was_cached = false;

	/**
	 * The API provider (fallback).
	 *
	 * @var Api_History_Data_Provider
	 */
	private Api_History_Data_Provider $api_provider;

	/**
	 * The cache repository.
	 *
	 * @var Observation_Cache_Repository
	 */
	private Observation_Cache_Repository $cache;

	/**
	 * Constructor.
	 *
	 * @param Api_History_Data_Provider    $api_provider The API provider.
	 * @param Observation_Cache_Repository $cache        The cache repository.
	 */
	public function __construct( Api_History_Data_Provider $api_provider, Observation_Cache_Repository $cache ) {
		$this->api_provider = $api_provider;
		$this->cache        = $cache;
	}

	/**
	 * Fetch history data, using cache where possible.
	 *
	 * @param string     $mac        The device MAC address.
	 * @param Connection $connection The API connection credentials.
	 * @param DateTime   $from       The start date.
	 * @param DateTime   $to         The end date.
	 * @param string[]   $groups     The sensor groups to fetch.
	 * @param string     $cycle_type The aggregation interval.
	 * @return array<string, mixed>  The raw data in API response shape.
	 */
	public function fetch_history(
		string $mac,
		Connection $connection,
		DateTime $from,
		DateTime $to,
		array $groups,
		string $cycle_type
	): array {
		$station  = $connection->key();
		$interval = self::CYCLE_INTERVALS[ $cycle_type ] ?? 14400;
		$from_ts  = $this->snap_timestamp( $from->getTimestamp(), $interval );
		$to_ts    = $this->snap_timestamp( $to->getTimestamp(), $interval );

		// Calculate expected timestamps on the interval grid.
		$expected_timestamps = $this->calculate_expected_timestamps( $from_ts, $to_ts, $interval );

		// Query cache for exactly those timestamps.
		$cached_timestamps = $this->cache->get_matching_timestamps( $station, $mac, $expected_timestamps );

		// If we have all expected timestamps cached, return from DB.
		if ( $this->has_all_timestamps( $expected_timestamps, $cached_timestamps ) ) {
			$cached_data           = $this->cache->get_observations_by_timestamps( $station, $mac, $expected_timestamps );
			$this->last_was_cached = true;
			return $this->cache_rows_to_api_shape( $cached_data );
		}

		// Otherwise, fetch from API.
		$this->last_was_cached = false;
		$api_data              = $this->api_provider->fetch_history( $mac, $connection, $from, $to, $groups, $cycle_type );

		// Store the API response as per-timestamp cache rows.
		if ( ! empty( $api_data ) ) {
			$snapshots = $this->api_data_to_snapshots( $api_data );
			$this->cache->upsert_observations( $station, $mac, $snapshots );
		}

		return $api_data;
	}

	/**
	 * Calculate expected timestamps for a range and interval.
	 *
	 * @param int $from     Start timestamp.
	 * @param int $to       End timestamp.
	 * @param int $interval Interval in seconds.
	 * @return int[] Expected timestamps.
	 */
	private function calculate_expected_timestamps( int $from, int $to, int $interval ): array {
		$timestamps = array();
		for ( $ts = $from; $ts <= $to; $ts += $interval ) {
			$timestamps[] = $ts;
		}
		return $timestamps;
	}

	/**
	 * Snap a timestamp down to the nearest interval boundary.
	 *
	 * @param int $timestamp Unix timestamp.
	 * @param int $interval  Interval in seconds.
	 * @return int Snapped timestamp.
	 */
	private function snap_timestamp( int $timestamp, int $interval ): int {
		return (int) ( floor( $timestamp / $interval ) * $interval );
	}

	/**
	 * Check if all expected timestamps exist in the cached set.
	 *
	 * Uses a tolerance of half the interval to account for API rounding.
	 *
	 * @param int[] $expected Expected timestamps.
	 * @param int[] $cached   Cached timestamps.
	 * @return bool True if all expected timestamps are covered.
	 */
	private function has_all_timestamps( array $expected, array $cached ): bool {
		if ( empty( $expected ) || empty( $cached ) ) {
			return false;
		}

		// Require at least 90% of expected timestamps to exist in cache.
		$threshold = (int) ceil( count( $expected ) * 0.9 );
		return count( $cached ) >= $threshold;
	}

	/**
	 * Transform API history response into per-timestamp snapshots.
	 *
	 * API shape:
	 *   { "outdoor": { "temperature": { "unit": "ºF", "list": { "123": "38.6" } } } }
	 *
	 * Snapshot shape:
	 *   { 123: { "outdoor": { "temperature": { "unit": "ºF", "value": "38.6" } } } }
	 *
	 * @param array<string, mixed> $api_data The raw API data.
	 * @return array<int, array<string, mixed>> Per-timestamp snapshots.
	 */
	private function api_data_to_snapshots( array $api_data ): array {
		$snapshots = array();

		foreach ( $api_data as $group_key => $group_fields ) {
			if ( ! is_array( $group_fields ) ) {
				continue;
			}

			foreach ( $group_fields as $field_key => $field_data ) {
				if ( ! is_array( $field_data ) || ! isset( $field_data['list'] ) || ! is_array( $field_data['list'] ) ) {
					continue;
				}

				$unit = $field_data['unit'] ?? '';

				foreach ( $field_data['list'] as $timestamp => $value ) {
					$ts = (int) $timestamp;

					if ( ! isset( $snapshots[ $ts ] ) ) {
						$snapshots[ $ts ] = array();
					}

					if ( ! isset( $snapshots[ $ts ][ $group_key ] ) ) {
						$snapshots[ $ts ][ $group_key ] = array();
					}

					$snapshots[ $ts ][ $group_key ][ $field_key ] = array(
						'unit'  => $unit,
						'value' => is_scalar( $value ) ? (string) $value : '',
					);
				}
			}
		}

		return $snapshots;
	}

	/**
	 * Transform per-timestamp cache rows back into API response shape.
	 *
	 * Reverse of api_data_to_snapshots().
	 *
	 * @param array<int, array<string, mixed>> $cache_rows Per-timestamp snapshots.
	 * @return array<string, mixed> Data in API response shape.
	 */
	private function cache_rows_to_api_shape( array $cache_rows ): array {
		$result = array();

		foreach ( $cache_rows as $timestamp => $snapshot ) {
			foreach ( $snapshot as $group_key => $group_fields ) {
				if ( ! is_array( $group_fields ) ) {
					continue;
				}

				if ( ! isset( $result[ $group_key ] ) ) {
					$result[ $group_key ] = array();
				}

				foreach ( $group_fields as $field_key => $field_data ) {
					if ( ! is_array( $field_data ) ) {
						continue;
					}

					if ( ! isset( $result[ $group_key ][ $field_key ] ) ) {
						$result[ $group_key ][ $field_key ] = array(
							'unit' => $field_data['unit'] ?? '',
							'list' => array(),
						);
					}

					$result[ $group_key ][ $field_key ]['list'][ (string) $timestamp ] = $field_data['value'] ?? '';
				}
			}
		}

		return $result;
	}

	/**
	 * Whether the last fetch_history() call was served from cache.
	 *
	 * @return bool
	 */
	public function was_cached(): bool {
		return $this->last_was_cached;
	}
}
