<?php

/**
 * The Device Service for Ecowitt API interactions.
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service;

use PinkCrab\Perique\Application\App_Config;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\Device;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connection;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The Device Service for Ecowitt API interactions.
 */
class Device_Service {

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
	 * Get all devices associated with the current connection.
	 *
	 * Retrieves all devices by recursively fetching paginated results
	 * until all devices are collected. Uses the API's totalPage and pageNum
	 * fields to determine when pagination is complete.
	 *
	 * Expected API response structure:
	 * {
	 *   "code": 0,
	 *   "msg": "success",
	 *   "data": {
	 *     "total": 2,
	 *     "totalPage": 1,
	 *     "pageNum": 1,
	 *     "list": [ ... devices ... ]
	 *   }
	 * }
	 *
	 * @param Connection $connection The API connection to use.
	 * @param int $limit Maximum devices per page (default: 100).
	 * @return Device[] All devices from all pages.
	 */
	public function get_all_devices( Connection $connection, int $limit = 100 ): array {
		// Extract keys once and pass as values for cleaner recursive method
		$application_key = $connection->application_key();
		$api_key         = $connection->api_key();

		// Start recursive collection from page 1
		$devices = $this->collect_devices_recursively( $application_key, $api_key, $limit, 1 );

		// Filter and validate devices are arrays before mapping to Device objects
		$device_objects = array();
		foreach ( $devices as $device_data ) {
			if ( is_array( $device_data ) ) {
				/** @var array<string, mixed> $device_data */
				$device_objects[] = Device::from_array( $device_data );
			}
		}

		return $device_objects;
	}

	/**
	 * Recursively collect devices from paginated API responses.
	 *
	 * @param string $application_key The application key for API authentication.
	 * @param string $api_key The API key for authentication.
	 * @param int $limit Maximum devices per page.
	 * @param int $page Current page number.
	 * @return array<mixed> Devices from this page and all subsequent pages.
	 */
	protected function collect_devices_recursively( string $application_key, string $api_key, int $limit, int $page ): array {
		// Get API base URL with proper type validation
		$api_base = $this->config->additional['ecowitt_api_base'] ?? 'https://api.ecowitt.net/api/v3';
		if ( ! is_string( $api_base ) ) {
			$api_base = 'https://api.ecowitt.net/api/v3';
		}

		$url = sprintf(
			'%s/device/list?application_key=%s&api_key=%s&limit=%d&page=%d',
			$api_base,
			rawurlencode( $application_key ),
			rawurlencode( $api_key ),
			$limit,
			$page
		);

		$response = $this->http_service->request( $url, array() );

		$data = json_decode( $response->body(), true );

		// Validate that we got a valid response - never trust API data
		if ( ! is_array( $data ) ) {
			return array();
		}

		// Handle API errors or invalid responses
		if ( ! isset( $data['code'] ) || ! is_scalar( $data['code'] ) || absint( $data['code'] ) !== 0 ) {
			return array();
		}

		// Check if we have the expected data structure
		if ( ! isset( $data['data'] ) || ! is_array( $data['data'] ) ) {
			return array();
		}

		$response_data = $data['data'];
		if ( ! isset( $response_data['list'] ) || ! is_array( $response_data['list'] ) ) {
			return array();
		}

		$devices_from_this_page = $response_data['list'];

		// Check if there are more pages using the API's pagination info
		$current_page = isset( $response_data['pageNum'] ) && is_scalar( $response_data['pageNum'] ) ? absint( $response_data['pageNum'] ) : $page;
		$total_pages  = isset( $response_data['totalPage'] ) && is_scalar( $response_data['totalPage'] ) ? absint( $response_data['totalPage'] ) : 1;

		// If current page is less than total pages, fetch the next page
		if ( $current_page < $total_pages ) {
			$devices_from_next_pages = $this->collect_devices_recursively( $application_key, $api_key, $limit, $page + 1 );
			return array_merge( $devices_from_this_page, $devices_from_next_pages );
		}

		// This is the last page, just return devices from this page
		return $devices_from_this_page;
	}
}
