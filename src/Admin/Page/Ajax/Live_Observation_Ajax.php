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
use PinkCrab\Ecowitt_Weather_Block\Settings\Settings_Repository;
use PinkCrab\Ecowitt_Weather_Block\Admin\Exception\Ajax_Exception;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connection;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connections;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Observation_Stats;

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
	 * Access to the connections.
	 *
	 * @var Connections
	 */
	protected Connections $connections;


	/**
	 * Constructs the object
	 *
	 * @param App_Config $app_config
	 * @param Ecowitt $ecowitt
	 * @param View $view
	 */
	public function __construct( App_Config $app_config, Ecowitt $ecowitt, View $view, Settings_Repository $settings_repository ) {
		$this->app_config   = $app_config;
		$this->action       = $this->app_config->ajax_live_observation_action;
		$this->nonce_handle = $this->app_config->ajax_live_observation_nonce;
		$this->ecowitt      = $ecowitt;
		$this->view         = $view;
		$this->connections  = $this->get_connections( $settings_repository );
	}

	/**
	 * Get the connections.
	 *
	 * @param Settings_Repository $settings_repository
	 * @return Connections|null
	 */
	public function get_connections( Settings_Repository $settings_repository ): ?Connections {
		$settings = $settings_repository->load();
		return $settings ? $settings->connections() : null;
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

		// Validate the args.
		try {
			$args = $this->validate_args( $args );
		} catch ( Ajax_Exception $e ) {
			return $response_factory->failure( array( 'error' => esc_html( $e->getMessage() ) ) );
		}

		// Get the connection.
		$connection = $this->connections->get( $args['connection'] );

		// If we dont have the connection, return a 404.
		if ( ! $connection ) {
			return $response_factory->not_found();
		}

		// Get the observation.
		$observation = $this->ecowitt->with_connection( $connection )->get_live_observations( $args['device'] );

		// If we dont have the observation, return a 404.
		if ( ! $observation ) {
			return $response_factory->not_found();
		}

		// Return with a valid PSR Response.
		return $response_factory->success(
			array(
				'connection'  => $connection,
				'args'        => $args,
				'mac'         => $args['device'],
				'observation' => $observation,
				'view'        => $this->view->component( new Observation_Stats( $observation ), View::RETURN_VIEW ),
			)
		);
	}

	/**
	 * Validate the args.
	 *
	 * @param array<string, mixed> $args
	 * @return array<string, mixed>
	 */
	private function validate_args( array $args ): array {
		// If we dont have the connection and device props throw an exception.
		if ( ! isset( $args['connection'] ) || ! isset( $args['device'] ) ) {
			throw new Ajax_Exception( 'Missing required fields' );
		}

		return array(
			'connection' => sanitize_text_field( wp_strip_all_tags( $args['connection'] ) ),
			'device'     => sanitize_text_field( wp_strip_all_tags( $args['device'] ) ),
		);
	}
}
