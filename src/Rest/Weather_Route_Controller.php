<?php

/**
 * REST Route Controller for weather block endpoints.
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Rest;

use WP_REST_Request;
use WP_REST_Response;
use PinkCrab\Route\Route\Route;
use PinkCrab\Route\Route\Route_Group;
use PinkCrab\Route\Route_Factory;
use PinkCrab\Route\Route_Controller;
use PinkCrab\WP_Rest_Schema\Argument\String_Type;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service\Ecowitt;
use PinkCrab\Ecowitt_Weather_Block\Settings\Settings_Repository;
use PinkCrab\Ecowitt_Weather_Block\Rest\Measurement_Serializer;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connection;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\History_Observation;
// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

/**
 * REST Route Controller for weather block endpoints.
 */
class Weather_Route_Controller extends Route_Controller {

	/**
	 * API namespace.
	 *
	 * @var string|null
	 */
	protected ?string $namespace = 'ecowitt-weather/v1';

	/**
	 * Ecowitt facade.
	 *
	 * @var Ecowitt
	 */
	protected Ecowitt $ecowitt;

	/**
	 * Settings repository.
	 *
	 * @var Settings_Repository
	 */
	protected Settings_Repository $settings_repository;

	/**
	 * Measurement serializer (handles both live and history).
	 *
	 * @var Measurement_Serializer
	 */
	protected Measurement_Serializer $serializer;

	/**
	 * Constructor.
	 *
	 * @param Ecowitt                $ecowitt             Ecowitt facade.
	 * @param Settings_Repository    $settings_repository Settings repository.
	 * @param Measurement_Serializer $serializer          Measurement serializer.
	 */
	public function __construct(
		Ecowitt $ecowitt,
		Settings_Repository $settings_repository,
		Measurement_Serializer $serializer
	) {
		$this->ecowitt             = $ecowitt;
		$this->settings_repository = $settings_repository;
		$this->serializer          = $serializer;
	}

	/**
	 * Define routes.
	 *
	 * @param Route_Factory $factory Route factory.
	 * @return array<Route|Route_Group>
	 */
	protected function define_routes( Route_Factory $factory ): array {
		return array(
			// GET /connections — admin only, list configured connections (safe fields only).
			$factory->get( '/connections', array( $this, 'list_connections' ) )
				->authentication( array( $this, 'check_admin_auth' ) ),

			// GET /devices — admin only, list devices for a connection.
			$factory->get( '/devices', array( $this, 'list_devices' ) )
				->authentication( array( $this, 'check_admin_auth' ) )
				->argument(
					String_Type::on( 'connection' )
						->required()
						->description( 'The connection key.' )
				),

			// GET /live — public, get current observation snapshot.
			$factory->get( '/live', array( $this, 'get_live_observations' ) )
				->argument(
					String_Type::on( 'connection' )
						->required()
						->description( 'The connection key.' )
				)
				->argument(
					String_Type::on( 'mac' )
						->required()
						->description( 'The device MAC address.' )
				),

			// GET /history — public, get historical observation time-series.
			$factory->get( '/history', array( $this, 'get_history_observations' ) )
				->argument(
					String_Type::on( 'connection' )
						->required()
						->description( 'The connection key.' )
				)
				->argument(
					String_Type::on( 'mac' )
						->required()
						->description( 'The device MAC address.' )
				)
				->argument(
					String_Type::on( 'from' )
						->required()
						->description( 'Start date in ISO 8601 format.' )
				)
				->argument(
					String_Type::on( 'to' )
						->description( 'End date in ISO 8601 format. Defaults to now.' )
				)
				->argument(
					String_Type::on( 'cycle_type' )
						->description( 'Aggregation interval: 5min, 1hour, 4hour, 1day. Auto-selects if omitted.' )
				),
		);
	}

	/**
	 * Check admin authentication.
	 *
	 * @param WP_REST_Request<array<string, mixed>> $request REST request.
	 * @return bool
	 */
	public function check_admin_auth( WP_REST_Request $request ): bool {
		return current_user_can( 'manage_options' );
	}

	/**
	 * List configured connections (safe fields only — no API secrets).
	 *
	 * @param WP_REST_Request<array<string, mixed>> $request REST request.
	 * @return WP_REST_Response
	 */
	public function list_connections( WP_REST_Request $request ): WP_REST_Response {
		$settings = $this->settings_repository->load();

		if ( ! $settings ) {
			return new WP_REST_Response( array( 'connections' => array() ), 200 );
		}

		$connections = array_map(
			function ( Connection $connection ): array {
				return array(
					'key'         => $connection->key(),
					'name'        => $connection->name(),
					'description' => $connection->description(),
					'mac_address' => $connection->mac_address(),
				);
			},
			$settings->connections()->all()
		);

		return new WP_REST_Response( array( 'connections' => array_values( $connections ) ), 200 );
	}

	/**
	 * List devices for a connection.
	 *
	 * @param WP_REST_Request<array<string, mixed>> $request REST request.
	 * @return WP_REST_Response
	 */
	public function list_devices( WP_REST_Request $request ): WP_REST_Response {
		$connection_key = $this->get_string_param( $request, 'connection' );

		$settings = $this->settings_repository->load();

		if ( ! $settings || ! $settings->connections()->has( $connection_key ) ) {
			return new WP_REST_Response( array( 'error' => 'Connection not found.' ), 404 );
		}

		$connection = $settings->connections()->get( $connection_key );
		if ( ! $connection ) {
			return new WP_REST_Response( array( 'error' => 'Connection not found.' ), 404 );
		}

		try {
			$devices = $this->ecowitt->with_connection( $connection )->get_devices();
		} catch ( \Exception $e ) {
			return new WP_REST_Response( array( 'error' => esc_html( $e->getMessage() ) ), 500 );
		}

		return new WP_REST_Response( array( 'devices' => $devices ), 200 );
	}

	/**
	 * Get live observation snapshot.
	 *
	 * @param WP_REST_Request<array<string, mixed>> $request REST request.
	 * @return WP_REST_Response
	 */
	public function get_live_observations( WP_REST_Request $request ): WP_REST_Response {
		$connection_key = $this->get_string_param( $request, 'connection' );
		$mac            = $this->get_string_param( $request, 'mac' );

		$settings = $this->settings_repository->load();

		if ( ! $settings || ! $settings->connections()->has( $connection_key ) ) {
			return new WP_REST_Response( array( 'error' => 'Connection not found.' ), 404 );
		}

		$connection = $settings->connections()->get( $connection_key );
		if ( ! $connection ) {
			return new WP_REST_Response( array( 'error' => 'Connection not found.' ), 404 );
		}

		try {
			$observation = $this->ecowitt->with_connection( $connection )->get_live_observations( $mac );
		} catch ( \Exception $e ) {
			return new WP_REST_Response( array( 'error' => esc_html( $e->getMessage() ) ), 500 );
		}

		if ( ! $observation ) {
			return new WP_REST_Response( array( 'error' => 'No observation data available.' ), 404 );
		}

		return new WP_REST_Response(
			array(
				'mac'         => $mac,
				'observation' => $this->serializer->serialize_observation( $observation ),
			),
			200
		);
	}

	/**
	 * Get historical observation time-series.
	 *
	 * @param WP_REST_Request<array<string, mixed>> $request REST request.
	 * @return WP_REST_Response
	 */
	public function get_history_observations( WP_REST_Request $request ): WP_REST_Response {
		$connection_key = $this->get_string_param( $request, 'connection' );
		$mac            = $this->get_string_param( $request, 'mac' );
		$from_str       = $this->get_string_param( $request, 'from' );
		$to_str         = $this->get_string_param( $request, 'to' );
		$cycle_type     = $this->get_string_param( $request, 'cycle_type' );

		$settings = $this->settings_repository->load();

		if ( ! $settings || ! $settings->connections()->has( $connection_key ) ) {
			return new WP_REST_Response( array( 'error' => 'Connection not found.' ), 404 );
		}

		$connection = $settings->connections()->get( $connection_key );
		if ( ! $connection ) {
			return new WP_REST_Response( array( 'error' => 'Connection not found.' ), 404 );
		}

		try {
			$from = new \DateTime( $from_str );
		} catch ( \Exception $e ) {
			return new WP_REST_Response( array( 'error' => 'Invalid "from" date format.' ), 400 );
		}

		try {
			$to = ! empty( $to_str ) ? new \DateTime( $to_str ) : new \DateTime();
		} catch ( \Exception $e ) {
			return new WP_REST_Response( array( 'error' => 'Invalid "to" date format.' ), 400 );
		}

		// Auto-select cycle_type based on range if not provided.
		if ( empty( $cycle_type ) ) {
			$cycle_type = $this->auto_cycle_type( $from, $to );
		}

		$groups = array( 'outdoor', 'indoor', 'wind', 'pressure', 'rainfall', 'solar_and_uvi' );

		try {
			// Split into ≤365 day chunks if range exceeds one year.
			$chunks     = $this->split_date_range( $from, $to );
			$history    = null;
			$all_cached = true;

			foreach ( $chunks as $chunk ) {
				$chunk_history = $this->ecowitt->with_connection( $connection )
					->get_observation_history(
						$mac,
						$chunk['from'],
						$chunk['to'],
						$groups,
						$cycle_type
					);

				// Track if any chunk was fetched from API.
				if ( ! $this->ecowitt->was_history_cached() ) {
					$all_cached = false;
				}

				if ( null === $history ) {
					$history = $chunk_history;
				} else {
					$history = $this->merge_history_observations( $history, $chunk_history );
				}
			}

			if ( null === $history ) {
				$history = new History_Observation( array() );
			}
		} catch ( \Exception $e ) {
			return new WP_REST_Response( array( 'error' => esc_html( $e->getMessage() ) ), 500 );
		}

		$history = $this->downsample_history( $history );

		return new WP_REST_Response(
			array(
				'mac'         => $mac,
				'from'        => $from->format( 'c' ),
				'to'          => $to->format( 'c' ),
				'cycle_type'  => $cycle_type,
				'from_cache'  => $all_cached,
				'observation' => $this->serializer->serialize( $history ),
			),
			200
		);
	}

	/**
	 * Auto-select cycle_type based on the date range.
	 *
	 * @param \DateTime $from Start date.
	 * @param \DateTime $to   End date.
	 * @return string The cycle type.
	 */
	private function auto_cycle_type( \DateTime $from, \DateTime $to ): string {
		$diff_hours = ( $to->getTimestamp() - $from->getTimestamp() ) / 3600;

		if ( $diff_hours <= 24 ) {
			return '5min';
		}

		if ( $diff_hours <= 168 ) { // 7 days.
			return '30min';
		}

		if ( $diff_hours <= 2160 ) { // 90 days.
			return '4hour';
		}

		return '1day';
	}

	/**
	 * Split a date range into chunks of ≤365 days.
	 *
	 * The Ecowitt API rejects queries exceeding one year,
	 * so we paginate by splitting into yearly windows.
	 *
	 * @param \DateTime $from Start date.
	 * @param \DateTime $to   End date.
	 * @return array<int, array{from: \DateTime, to: \DateTime}> Array of date chunks.
	 */
	private function split_date_range( \DateTime $from, \DateTime $to ): array {
		$max_days = 365;
		$chunks   = array();
		$current  = clone $from;

		while ( $current < $to ) {
			$chunk_end = clone $current;
			$chunk_end->modify( "+{$max_days} days" );

			if ( $chunk_end > $to ) {
				$chunk_end = clone $to;
			}

			$chunks[] = array(
				'from' => clone $current,
				'to'   => clone $chunk_end,
			);

			$current = clone $chunk_end;
		}

		return $chunks;
	}

	/**
	 * Merge two History_Observation objects by appending measurement arrays.
	 *
	 * @param History_Observation $base  The base observation.
	 * @param History_Observation $extra The additional observation to merge in.
	 * @return History_Observation The merged observation.
	 */
	private function merge_history_observations( History_Observation $base, History_Observation $extra ): History_Observation {
		$merged = $base->observations;

		foreach ( $extra->observations as $group_key => $group_data ) {
			if ( ! is_array( $group_data ) ) {
				continue;
			}

			if ( ! isset( $merged[ $group_key ] ) ) {
				$merged[ $group_key ] = $group_data;
				continue;
			}

			foreach ( $group_data as $field_key => $measurements ) {
				if ( ! isset( $merged[ $group_key ][ $field_key ] ) ) {
					$merged[ $group_key ][ $field_key ] = $measurements;
				} elseif ( is_array( $measurements ) ) {
					$merged[ $group_key ][ $field_key ] = array_merge(
						$merged[ $group_key ][ $field_key ],
						$measurements
					);
				}
			}
		}

		return new History_Observation( $merged );
	}

	/**
	 * Downsample a History_Observation to target a maximum number of data points.
	 *
	 * Keeps every Nth measurement in each field's array so the frontend
	 * receives a manageable number of points. The full dataset remains cached.
	 *
	 * @param History_Observation $history The history observation to downsample.
	 * @param int                 $target  Target maximum data points.
	 * @return History_Observation The downsampled observation.
	 */
	private function downsample_history( History_Observation $history, int $target = 300 ): History_Observation {
		// Find the count from the first group's first field.
		$count = 0;
		foreach ( $history->observations as $group_data ) {
			if ( ! is_array( $group_data ) ) {
				continue;
			}
			foreach ( $group_data as $measurements ) {
				if ( is_array( $measurements ) ) {
					$count = count( $measurements );
					break 2;
				}
			}
		}

		// No downsampling needed.
		if ( $count <= $target ) {
			return $history;
		}

		$step    = (int) ceil( $count / $target );
		$thinned = array();

		foreach ( $history->observations as $group_key => $group_data ) {
			if ( ! is_array( $group_data ) ) {
				continue;
			}

			$thinned[ $group_key ] = array();

			foreach ( $group_data as $field_key => $measurements ) {
				if ( ! is_array( $measurements ) ) {
					$thinned[ $group_key ][ $field_key ] = $measurements;
					continue;
				}

				$thinned[ $group_key ][ $field_key ] = array_values(
					array_filter(
						$measurements,
						function ( $key ) use ( $step ) {
							return 0 === $key % $step;
						},
						ARRAY_FILTER_USE_KEY
					)
				);
			}
		}

		return new History_Observation( $thinned );
	}

	/**
	 * Extract a string parameter from a REST request.
	 *
	 * @param WP_REST_Request<array<string, mixed>> $request REST request.
	 * @param string                                $key     Parameter key.
	 * @return string The sanitized parameter value, or empty string.
	 */
	private function get_string_param( WP_REST_Request $request, string $key ): string {
		$value = $request->get_param( $key );
		return sanitize_text_field( is_string( $value ) ? $value : '' );
	}
}
