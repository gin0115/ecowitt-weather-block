<?php

/**
 * Cached implementation of Live_Data_Provider.
 *
 * Returns cached data if a recent observation exists (within 5 minutes),
 * otherwise fetches from the API and stores the result.
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Cache;

use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connection;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service\Live_Data_Provider;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service\Api_Live_Data_Provider;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

/**
 * Cached Live Data Provider.
 */
class Cached_Live_Data_Provider implements Live_Data_Provider {

	/**
	 * Cache staleness threshold in seconds (5 minutes).
	 *
	 * @var int
	 */
	private const CACHE_TTL = 300;

	/**
	 * The API provider (fallback).
	 *
	 * @var Api_Live_Data_Provider
	 */
	private Api_Live_Data_Provider $api_provider;

	/**
	 * The cache repository.
	 *
	 * @var Observation_Cache_Repository
	 */
	private Observation_Cache_Repository $cache;

	/**
	 * Constructor.
	 *
	 * @param Api_Live_Data_Provider       $api_provider The API provider.
	 * @param Observation_Cache_Repository $cache        The cache repository.
	 */
	public function __construct( Api_Live_Data_Provider $api_provider, Observation_Cache_Repository $cache ) {
		$this->api_provider = $api_provider;
		$this->cache        = $cache;
	}

	/**
	 * Fetch live observation data, using cache if fresh enough.
	 *
	 * @param string     $mac        The device MAC address.
	 * @param Connection $connection The API connection credentials.
	 * @return array<string, mixed>  The raw data in API response shape.
	 */
	public function fetch_live( string $mac, Connection $connection ): array {
		$station = $connection->key();
		$now     = time();

		// Check cache for a recent observation.
		$latest = $this->cache->get_latest( $station, $mac );

		if ( $latest && ( $now - $latest['timestamp'] ) < self::CACHE_TTL ) {
			return $this->snapshot_to_live_shape( $latest['data'], $latest['timestamp'] );
		}

		// Fetch from API.
		$api_data = $this->api_provider->fetch_live( $mac, $connection );

		if ( ! empty( $api_data ) ) {
			// Extract timestamp from any field and store as snapshot.
			$timestamp = $this->extract_timestamp( $api_data );
			$snapshot  = $this->live_data_to_snapshot( $api_data );

			$this->cache->upsert_observations( $station, $mac, array( $timestamp => $snapshot ) );
		}

		return $api_data;
	}

	/**
	 * Extract the common timestamp from live API data.
	 *
	 * Each field has a "time" key. We take the first one found.
	 *
	 * @param array<string, mixed> $api_data The raw API data.
	 * @return int The unix timestamp.
	 */
	private function extract_timestamp( array $api_data ): int {
		foreach ( $api_data as $group_fields ) {
			if ( ! is_array( $group_fields ) ) {
				continue;
			}
			foreach ( $group_fields as $field_data ) {
				if ( is_array( $field_data ) && isset( $field_data['time'] ) ) {
					return (int) $field_data['time'];
				}
			}
		}

		return time();
	}

	/**
	 * Transform live API data into a cache snapshot.
	 *
	 * Live shape:  { "outdoor": { "temperature": { "time": "123", "unit": "ºF", "value": "50.4" } } }
	 * Cache shape:  { "outdoor": { "temperature": { "unit": "ºF", "value": "50.4" } } }
	 *
	 * @param array<string, mixed> $api_data The raw API data.
	 * @return array<string, mixed> The cache snapshot.
	 */
	private function live_data_to_snapshot( array $api_data ): array {
		$snapshot = array();

		foreach ( $api_data as $group_key => $group_fields ) {
			if ( ! is_array( $group_fields ) ) {
				continue;
			}

			$snapshot[ $group_key ] = array();

			foreach ( $group_fields as $field_key => $field_data ) {
				if ( ! is_array( $field_data ) || ! isset( $field_data['value'] ) ) {
					continue;
				}

				$snapshot[ $group_key ][ $field_key ] = array(
					'unit'  => $field_data['unit'] ?? '',
					'value' => (string) $field_data['value'],
				);
			}
		}

		return $snapshot;
	}

	/**
	 * Transform a cache snapshot back into live API response shape.
	 *
	 * Cache shape: { "outdoor": { "temperature": { "unit": "ºF", "value": "50.4" } } }
	 * Live shape:  { "outdoor": { "temperature": { "time": "123", "unit": "ºF", "value": "50.4" } } }
	 *
	 * @param array<string, mixed> $snapshot  The cached snapshot data.
	 * @param int                  $timestamp The observation timestamp.
	 * @return array<string, mixed> Data in live API response shape.
	 */
	private function snapshot_to_live_shape( array $snapshot, int $timestamp ): array {
		$result   = array();
		$time_str = (string) $timestamp;

		foreach ( $snapshot as $group_key => $group_fields ) {
			if ( ! is_array( $group_fields ) ) {
				continue;
			}

			$result[ $group_key ] = array();

			foreach ( $group_fields as $field_key => $field_data ) {
				if ( ! is_array( $field_data ) ) {
					continue;
				}

				$result[ $group_key ][ $field_key ] = array(
					'time'  => $time_str,
					'unit'  => $field_data['unit'] ?? '',
					'value' => $field_data['value'] ?? '',
				);
			}
		}

		return $result;
	}
}
