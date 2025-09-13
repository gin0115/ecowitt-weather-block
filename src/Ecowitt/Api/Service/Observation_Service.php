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
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\Observation;

use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connection;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service\Ecowitt_Http_Service;

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
	 * Constructor.
	 *
	 * @param Ecowitt_Http_Service $http_service
	 * @param App_Config $config
	 */
	public function __construct( Ecowitt_Http_Service $http_service, App_Config $config ) {
		$this->http_service = $http_service;
		$this->config       = $config;
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
		// https://api.ecowitt.net/api/v3/device/real_time?application_key=APPLICATION_KEY&api_key=API_KEY&mac=YOUR_MAC_CODE_OF_DEVICE&call_back=all

		$url = sprintf(
			'%s/device/real_time?application_key=%s&api_key=%s&mac=%s&call_back=all',
			$base_url,
			$connection->application_key(),
			$connection->api_key(),
			$device->mac
		);

		$response = $this->http_service->request( $url, array() );

		$data = json_decode( $response->body(), true );

		return Observation::from_array( $data );
	}
}
