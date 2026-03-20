<?php

/**
 * Default API implementation of Live_Data_Provider.
 *
 * Fetches live data directly from the Ecowitt API v3.
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service;

use PinkCrab\Perique\Application\App_Config;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connection;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service\Ecowitt_Http_Service;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

/**
 * API Live Data Provider.
 */
class Api_Live_Data_Provider implements Live_Data_Provider {

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
	 * @param Ecowitt_Http_Service $http_service The HTTP service.
	 * @param App_Config           $config       The app config.
	 */
	public function __construct( Ecowitt_Http_Service $http_service, App_Config $config ) {
		$this->http_service = $http_service;
		$this->config       = $config;
	}

	/**
	 * Get the API base URL.
	 *
	 * @return string
	 */
	protected function get_api_base_url(): string {
		$api_base = $this->config->additional['ecowitt_api_base'] ?? 'https://api.ecowitt.net/api/v3';
		if ( ! is_string( $api_base ) ) {
			$api_base = 'https://api.ecowitt.net/api/v3';
		}

		return $api_base;
	}

	/**
	 * Fetch live observation data from the Ecowitt API.
	 *
	 * @param string     $mac        The device MAC address.
	 * @param Connection $connection The API connection credentials.
	 * @return array<string, mixed>  The raw decoded data from the API response.
	 * @throws \Exception If the API request fails.
	 */
	public function fetch_live( string $mac, Connection $connection ): array {
		$base_url = $this->get_api_base_url();

		$url = sprintf(
			'%s/device/real_time?application_key=%s&api_key=%s&mac=%s&call_back=all',
			$base_url,
			$connection->application_key(),
			$connection->api_key(),
			$mac
		);

		$response = $this->http_service->request( $url, array() );
		$data     = json_decode( $response->body(), true );

		if ( ! is_array( $data ) || ! isset( $data['msg'] ) || 'success' !== $data['msg'] ) {
			throw new \Exception( 'Failed to get live observations' );
		}

		if ( ! isset( $data['data'] ) || ! is_array( $data['data'] ) ) {
			return array();
		}

		/** @var array<string, mixed> */
		return $data['data'];
	}
}
