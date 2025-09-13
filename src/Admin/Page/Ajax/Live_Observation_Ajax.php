<?php

/**
 * Ajax handler for live observations.
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Admin\Page\Ajax;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

use PinkCrab\Ajax\Ajax;
use PinkCrab\Ajax\Ajax_Helper;
use Psr\Http\Message\ResponseInterface;
use PinkCrab\Perique\Services\View\View;
use PinkCrab\Perique\Application\App_Config;
use Psr\Http\Message\ServerRequestInterface;
use PinkCrab\Ajax\Dispatcher\Response_Factory;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\Device;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service\Ecowitt;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connection;
use PinkCrab\Ecowitt_Weather_Block\Admin\Exception\Ajax_Exception;

/**
 * @phpstan-type DeviceNormalized array{id: int, name: string, mac: string, imei: string, type: int, date_zone_id: string, createtime: int, longitude: string, latitude: string, stationtype: string, iotdevice_list: array<int, array{name: string, default_title: string, device_id: string, version: string, createtime: string}>}
 * @phpstan-type IotDevice array{name: string, default_title: string, device_id: string, version: string, createtime: string}
 */
class Live_Observation_Ajax extends Ajax {

	/**
	 * Access to App_Config.
	 *
	 * @var App_Config
	 */
	protected App_Config $app_config;

	/**
	 * Ecowitt.
	 *
	 * @var Ecowitt
	 */
	protected Ecowitt $ecowitt;

	/**
	 * Access to view.
	 *
	 * @var View
	 */
	protected View $view;


	/**
	 * Constructs the object
	 *
	 * @param App_Config $app_config
	 * @param Ecowitt $ecowitt
	 * @param View $view
	 */
	public function __construct( App_Config $app_config, Ecowitt $ecowitt, View $view ) {
		$this->app_config   = $app_config;
		$this->action       = $this->app_config->ajax_live_observation_action;
		$this->nonce_handle = $this->app_config->ajax_live_observation_nonce;
		$this->ecowitt      = $ecowitt;
		$this->view         = $view;
	}


	/**
	 * The callback
	 *
	 * @param \Psr\Http\Message\ServerRequestInterface $request
	 * @param \PinkCrab\Ajax\Dispatcher\Response_Factory $response_factory
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function callback(
		ServerRequestInterface $request,
		Response_Factory $response_factory
	): ResponseInterface {

		// Extract the args from the request, you can also do this manually
		$args = Ajax_Helper::extract_server_request_args( $request );

		$device     = Device::from_array( $this->normalize_device_args( $args ) );
		$connection = Connection::from_array( $this->normalize_connection_args( $args ) );

		// $observation = $this->ecowitt->with_connection( $connection )->get_current_observations( $device );

		// Return with a valid PSR Response.
		return $response_factory->success(
			array(
				'connection' => $connection,
				'device'     => $device,
				'args'       => $args,
			)
		);
	}

	/**
	 * Normalise the ajax request args.
	 *
	 * @param array<string, mixed> $args Raw data from external API - structure unknown
	 * @return DeviceNormalized
	 */
	public function normalise_ajax_request_args( array $args ): array {
		return $this->normalize_device_args( $args );
	}

	/**
	 * Normalize device args from ajax request.
	 *
	 * @param array<string, mixed> $args Raw data from external API - structure unknown
	 * @return array{id: int, name: string, mac: string, imei: string, type: int, date_zone_id: string, createtime: int, longitude: string, latitude: string, stationtype: string, iotdevice_list: array<int, array{name: string, default_title: string, device_id: string, version: string, createtime: string}>}
	 * @throws Ajax_Exception If required fields are missing
	 */
	private function normalize_device_args( array $args ): array {
		$device_data = $args['device'] ?? array();
		assert( is_array( $device_data ) );

		// Validate required fields
		$missing_fields = array();
		if ( empty( $device_data['id'] ) ) {
			$missing_fields[] = 'id';
		}
		if ( empty( $device_data['mac'] ) ) {
			$missing_fields[] = 'mac';
		}

		if ( ! empty( $missing_fields ) ) {
			throw Ajax_Exception::missing_required_fields( $missing_fields );
		}

		return array(
			'id'             => absint( $device_data['id'] ),
			'name'           => isset( $device_data['name'] ) && is_string( $device_data['name'] ) ? $device_data['name'] : '',
			'mac'            => isset( $device_data['mac'] ) && is_string( $device_data['mac'] ) ? $device_data['mac'] : '',
			'imei'           => isset( $device_data['imei'] ) && is_string( $device_data['imei'] ) ? $device_data['imei'] : '',
			'type'           => isset( $device_data['type'] ) && is_int( $device_data['type'] ) ? $device_data['type'] : 0,
			'date_zone_id'   => isset( $device_data['date_zone_id'] ) && is_string( $device_data['date_zone_id'] ) ? $device_data['date_zone_id'] : '',
			'createtime'     => isset( $device_data['createtime'] ) && is_int( $device_data['createtime'] ) ? $device_data['createtime'] : 0,
			'longitude'      => isset( $device_data['longitude'] ) && is_string( $device_data['longitude'] ) ? $device_data['longitude'] : '',
			'latitude'       => isset( $device_data['latitude'] ) && is_string( $device_data['latitude'] ) ? $device_data['latitude'] : '',
			'stationtype'    => isset( $device_data['stationtype'] ) && is_string( $device_data['stationtype'] ) ? $device_data['stationtype'] : '',
			'iotdevice_list' => $this->normalize_iotdevice_list( $device_data['iotdevice_list'] ?? array() ),
		);
	}

	/**
	 * Normalize IOT device list from ajax request.
	 *
	 * @param mixed $iotdevice_list Raw IOT device list data
	 * @return array<int, array{name: string, default_title: string, device_id: string, version: string, createtime: string}>
	 */
	private function normalize_iotdevice_list( $iotdevice_list ): array {
		if ( ! is_array( $iotdevice_list ) ) {
			return array();
		}

		return array_map(
			function ( $iot_device ) {
				if ( ! is_array( $iot_device ) ) {
					return array(
						'name'          => '',
						'default_title' => '',
						'device_id'     => '',
						'version'       => '',
						'createtime'    => '',
					);
				}

				return array(
					'name'          => isset( $iot_device['name'] ) && is_string( $iot_device['name'] ) ? $iot_device['name'] : '',
					'default_title' => isset( $iot_device['default_title'] ) && is_string( $iot_device['default_title'] ) ? $iot_device['default_title'] : '',
					'device_id'     => isset( $iot_device['device_id'] ) && is_string( $iot_device['device_id'] ) ? $iot_device['device_id'] : '',
					'version'       => isset( $iot_device['version'] ) && is_string( $iot_device['version'] ) ? $iot_device['version'] : '',
					'createtime'    => isset( $iot_device['createtime'] ) && is_string( $iot_device['createtime'] ) ? $iot_device['createtime'] : '',
				);
			},
			$iotdevice_list
		);
	}

	/**
	 * Normalize connection args from ajax request.
	 *
	 * @param array<string, mixed> $args Raw data from external API - structure unknown
	 * @return array{key: string, application_key: string, api_key: string, mac_address: string, description: string, name: string}
	 */
	private function normalize_connection_args( array $args ): array {
		$connection_data = $args['connection'] ?? array();
		assert( is_array( $connection_data ) );

		return array(
			'key'             => isset( $connection_data['key'] ) && is_string( $connection_data['key'] ) ? $connection_data['key'] : '',
			'application_key' => isset( $connection_data['application_key'] ) && is_string( $connection_data['application_key'] ) ? $connection_data['application_key'] : '',
			'api_key'         => isset( $connection_data['api_key'] ) && is_string( $connection_data['api_key'] ) ? $connection_data['api_key'] : '',
			'mac_address'     => isset( $connection_data['mac_address'] ) && is_string( $connection_data['mac_address'] ) ? $connection_data['mac_address'] : '',
			'description'     => isset( $connection_data['description'] ) && is_string( $connection_data['description'] ) ? $connection_data['description'] : '',
			'name'            => isset( $connection_data['name'] ) && is_string( $connection_data['name'] ) ? $connection_data['name'] : '',
		);
	}
}
