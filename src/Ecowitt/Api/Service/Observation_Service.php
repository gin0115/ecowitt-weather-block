<?php

/**
 * Observation Service
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service;

use PinkCrab\Perique\Application\App_Config;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\Device;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\Measurement;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\Observation;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connection;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service\Ecowitt_Http_Service;
use PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Measurement_Mapping;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

/**
 * Observation Service
 */
class Observation_Service {

	/**
	 * Ecowitt HTTP Service.
	 *
	 * @var Ecowitt_Http_Service
	 */
	protected Ecowitt_Http_Service $http_service;

	/**
	 * App Config.
	 *
	 * @var App_Config
	 */
	protected App_Config $config;

	/**
	 * Measurement mapping configuration.
	 *
	 * @var Measurement_Mapping
	 */
	protected Measurement_Mapping $measurement_mapping;

	/**
	 * Constructor.
	 *
	 * @param Ecowitt_Http_Service $http_service
	 * @param App_Config $config
	 * @param Measurement_Mapping $measurement_mapping
	 */
	public function __construct( Ecowitt_Http_Service $http_service, App_Config $config, Measurement_Mapping $measurement_mapping ) {
		$this->http_service        = $http_service;
		$this->config              = $config;
		$this->measurement_mapping = $measurement_mapping;
	}

	/**
	 * Get the api base url.
	 *
	 * @return string
	 */
	public function get_api_base_url(): string {
		// Get API base URL with proper type validation
		$api_base = $this->config->additional['ecowitt_api_base'] ?? 'https://api.ecowitt.net/api/v3';
		if ( ! is_string( $api_base ) ) {
			$api_base = 'https://api.ecowitt.net/api/v3';
		}

		return $api_base;
	}

	/**
	 * Get the live observations for a device.
	 *
	 * @param Device $device
	 * @param Connection $connection
	 * @return Observation
	 */
	public function get_live_observations( Device $device, Connection $connection ): Observation {
		$base_url = $this->get_api_base_url();

		$url = sprintf(
			'%s/device/real_time?application_key=%s&api_key=%s&mac=%s&call_back=all',
			$base_url,
			$connection->application_key(),
			$connection->api_key(),
			$device->mac
		);

		$response = $this->http_service->request( $url, array() );

		$data = json_decode( $response->body(), true );

		// If we dont have a success, throw an exception.
		if ( ! isset( $data['msg'] ) || 'success' !== $data['msg'] ) {
			throw new \Exception( 'Failed to get live observations' );
		}

		// If we have not data, return an empty observation.
		if ( ! isset( $data['data'] ) || ! is_array( $data['data'] ) ) {
			return new Observation( array() );
		}

		// Extract the data and map the Measurements.
		$observations        = $data['data'];
		$mapped_observations = array();

		// Iterate over the measurement group.
		foreach ( $observations as $measurement_group => $measurement_data ) {
			// Iterate over the measurement data.
			foreach ( $measurement_data as $measurement_key => $measurement_value ) {
				// Create a new measurement.
				$mapped_observations[ esc_html( $measurement_group ) ][ esc_html( $measurement_key ) ] = Measurement::from_array( $measurement_value );
			}
		}

		// Convert DTOs to domain measurement objects
		$domain_measurements = $this->convert_measurements_to_domain_objects( $mapped_observations );

		return Observation::from_array( $domain_measurements );
	}

	/**
	 * Convert measurement DTOs to domain measurement objects.
	 *
	 * @param array $mapped_observations Array of [group][key] => Measurement DTO
	 * @return array Array of [group][key] => Domain Measurement object
	 */
	private function convert_measurements_to_domain_objects( array $mapped_observations ): array {
		$domain_objects = array();

		foreach ( $mapped_observations as $group => $measurements ) {
			foreach ( $measurements as $key => $measurement_dto ) {
				$class_name = $this->measurement_mapping->get_measurement_class( $group, $key );

				if ( $class_name ) {
					$domain_objects[ $group ][ $key ] = new $class_name( $measurement_dto );
				}
			}
		}

		return $domain_objects;
	}
}
