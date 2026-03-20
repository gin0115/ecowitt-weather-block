<?php

/**
 * Repository for the observation cache table.
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Cache;

use PinkCrab\Perique\Application\App_Config;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

/**
 * CRUD operations for the observation cache table.
 */
class Observation_Cache_Repository {

	/**
	 * The full table name (with WP prefix).
	 *
	 * @var string
	 */
	private string $table_name;

	/**
	 * Constructor.
	 *
	 * @param App_Config $app_config The app config.
	 */
	public function __construct( App_Config $app_config ) {
		$this->table_name = $app_config->db_tables( 'observation_cache' );
	}

	/**
	 * Get cached timestamps in a range for a station/mac.
	 *
	 * @param string $station The connection key.
	 * @param string $mac     The device MAC address.
	 * @param int    $from    Start timestamp (inclusive).
	 * @param int    $to      End timestamp (inclusive).
	 * @return int[] Array of cached timestamps.
	 */
	public function get_timestamps_in_range( string $station, string $mac, int $from, int $to ): array {
		global $wpdb;

		$results = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT `timestamp` FROM {$this->table_name} WHERE station = %s AND mac = %s AND `timestamp` BETWEEN %d AND %d ORDER BY `timestamp` ASC", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$station,
				$mac,
				$from,
				$to
			)
		);

		return array_map( 'intval', $results );
	}

	/**
	 * Get full observation rows in a range.
	 *
	 * @param string $station The connection key.
	 * @param string $mac     The device MAC address.
	 * @param int    $from    Start timestamp (inclusive).
	 * @param int    $to      End timestamp (inclusive).
	 * @return array<int, array<string, mixed>> Array keyed by timestamp => decoded data.
	 */
	public function get_observations_in_range( string $station, string $mac, int $from, int $to ): array {
		global $wpdb;

		$rows = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT `timestamp`, `data` FROM {$this->table_name} WHERE station = %s AND mac = %s AND `timestamp` BETWEEN %d AND %d ORDER BY `timestamp` ASC", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$station,
				$mac,
				$from,
				$to
			),
			ARRAY_A
		);

		$observations = array();
		foreach ( $rows as $row ) {
			$ts   = (int) $row['timestamp'];
			$data = json_decode( $row['data'], true );
			if ( is_array( $data ) ) {
				$observations[ $ts ] = $data;
			}
		}

		return $observations;
	}

	/**
	 * Get cached timestamps that match specific expected timestamps.
	 *
	 * @param string $station    The connection key.
	 * @param string $mac        The device MAC address.
	 * @param int[]  $timestamps The specific timestamps to look for.
	 * @return int[] Array of matching cached timestamps.
	 */
	public function get_matching_timestamps( string $station, string $mac, array $timestamps ): array {
		global $wpdb;

		if ( empty( $timestamps ) ) {
			return array();
		}

		$placeholders = implode( ',', array_fill( 0, count( $timestamps ), '%d' ) );
		$params       = array_merge( array( $station, $mac ), $timestamps );

		$results = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT `timestamp` FROM {$this->table_name} WHERE station = %s AND mac = %s AND `timestamp` IN ({$placeholders}) ORDER BY `timestamp` ASC", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.ReplacementsWrongNumber
				$params
			)
		);

		return array_map( 'intval', $results );
	}

	/**
	 * Get full observation rows for specific timestamps.
	 *
	 * @param string $station    The connection key.
	 * @param string $mac        The device MAC address.
	 * @param int[]  $timestamps The specific timestamps to fetch.
	 * @return array<int, array<string, mixed>> Array keyed by timestamp => decoded data.
	 */
	public function get_observations_by_timestamps( string $station, string $mac, array $timestamps ): array {
		global $wpdb;

		if ( empty( $timestamps ) ) {
			return array();
		}

		$placeholders = implode( ',', array_fill( 0, count( $timestamps ), '%d' ) );
		$params       = array_merge( array( $station, $mac ), $timestamps );

		$rows = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT `timestamp`, `data` FROM {$this->table_name} WHERE station = %s AND mac = %s AND `timestamp` IN ({$placeholders}) ORDER BY `timestamp` ASC", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.ReplacementsWrongNumber
				$params
			),
			ARRAY_A
		);

		$observations = array();
		foreach ( $rows as $row ) {
			$ts   = (int) $row['timestamp'];
			$data = json_decode( $row['data'], true );
			if ( is_array( $data ) ) {
				$observations[ $ts ] = $data;
			}
		}

		return $observations;
	}

	/**
	 * Get the latest observation for a station/mac.
	 *
	 * @param string $station The connection key.
	 * @param string $mac     The device MAC address.
	 * @return array{timestamp: int, data: array<string, mixed>}|null The latest observation or null.
	 */
	public function get_latest( string $station, string $mac ): ?array {
		global $wpdb;

		$row = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT `timestamp`, `data` FROM {$this->table_name} WHERE station = %s AND mac = %s ORDER BY `timestamp` DESC LIMIT 1", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$station,
				$mac
			),
			ARRAY_A
		);

		if ( ! $row ) {
			return null;
		}

		$data = json_decode( $row['data'], true );
		if ( ! is_array( $data ) ) {
			return null;
		}

		return array(
			'timestamp' => (int) $row['timestamp'],
			'data'      => $data,
		);
	}

	/**
	 * Upsert observations into the cache.
	 *
	 * @param string                       $station      The connection key.
	 * @param string                       $mac          The device MAC address.
	 * @param array<int, array<string, mixed>> $observations Keyed by timestamp => data array.
	 * @return void
	 */
	public function upsert_observations( string $station, string $mac, array $observations ): void {
		global $wpdb;

		$now = current_time( 'mysql', true );

		foreach ( $observations as $timestamp => $data ) {
			// Skip if this station+mac+timestamp already exists in the cache.
			$exists = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT 1 FROM {$this->table_name} WHERE station = %s AND mac = %s AND `timestamp` = %d LIMIT 1", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
					$station,
					$mac,
					(int) $timestamp
				)
			);

			if ( $exists ) {
				continue;
			}

			$json = wp_json_encode( $data );

			$wpdb->insert(
				$this->table_name,
				array(
					'station'    => $station,
					'mac'        => $mac,
					'timestamp'  => (int) $timestamp,
					'data'       => $json,
					'created_at' => $now,
				),
				array( '%s', '%s', '%d', '%s', '%s' )
			);
		}
	}
}
