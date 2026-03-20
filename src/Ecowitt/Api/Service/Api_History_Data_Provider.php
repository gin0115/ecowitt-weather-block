<?php

/**
 * Default API implementation of History_Data_Provider.
 *
 * Fetches history data directly from the Ecowitt API v3.
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service;

use DateTime;
use PinkCrab\Perique\Application\App_Config;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connection;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service\Ecowitt_Http_Service;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service\History_Data_Provider;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

/**
 * API History Data Provider.
 */
class Api_History_Data_Provider implements History_Data_Provider {

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
	 * Fetch history data from the Ecowitt API.
	 *
	 * @param string     $mac        The device MAC address.
	 * @param Connection $connection The API connection credentials.
	 * @param DateTime   $from       The start date.
	 * @param DateTime   $to         The end date.
	 * @param string[]   $groups     The sensor groups to fetch.
	 * @param string     $cycle_type The aggregation interval.
	 * @return array<string, mixed>  The raw decoded data from the API response.
	 * @throws \Exception If the API request fails.
	 */
	public function fetch_history(
		string $mac,
		Connection $connection,
		DateTime $from,
		DateTime $to,
		array $groups,
		string $cycle_type
	): array {
		$base_url = $this->get_api_base_url();

		$url = sprintf(
			'%s/device/history?application_key=%s&api_key=%s&mac=%s&call_back=%s&start_date=%s&end_date=%s&cycle_type=%s',
			$base_url,
			$connection->application_key(),
			$connection->api_key(),
			$mac,
			implode( ',', $groups ),
			$from->format( 'Y-m-d H:i:s' ),
			$to->format( 'Y-m-d H:i:s' ),
			$cycle_type
		);

		$response = $this->http_service->request( $url, array() );
		$data     = json_decode( $response->body(), true );

		// Validate the response.
		if ( ! is_array( $data ) || ! isset( $data['msg'] ) || 'success' !== $data['msg'] ) {
			$error_msg = is_array( $data ) && isset( $data['msg'] ) ? $data['msg'] : 'Unknown error';
			throw new \Exception( 'Failed to get observation history: ' . $error_msg );
		}

		// Return the data array, or empty if not present.
		if ( ! isset( $data['data'] ) || ! is_array( $data['data'] ) ) {
			return array();
		}

		return $data['data'];
	}

	/**
	 * Always false — API provider never serves from cache.
	 *
	 * @return bool
	 */
	public function was_cached(): bool {
		return false;
	}
}
