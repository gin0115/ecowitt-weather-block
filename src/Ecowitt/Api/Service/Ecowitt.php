<?php

/**
 * Primary Ecowitt Service.
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service;

use DateTime;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\Device;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\Observation;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connection;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service\Device_Service;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service\Observation_Service;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service\Ecowitt_Http_Service;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Exception\Connection_Exception;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

/**
 * Primary Ecowitt Service.
 */
class Ecowitt {

	/**
	 * HTTP Service.
	 *
	 * @var Ecowitt_Http_Service
	 */
	protected Ecowitt_Http_Service $http_service;

	/**
	 * Device Service.
	 *
	 * @var Device_Service
	 */
	protected Device_Service $device_service;

	/**
	 * Observation Service.
	 *
	 * @var Observation_Service
	 */
	protected Observation_Service $observation_service;

	/**
	 * Current Connection.
	 *
	 * @var Connection|null
	 */
	protected ?Connection $current_connection = null;

	/**
	 * Constructor.
	 *
	 * @param Ecowitt_Http_Service $http_service
	 * @param Device_Service       $device_service
	 * @param Observation_Service  $observation_service
	 */
	public function __construct(
		Ecowitt_Http_Service $http_service,
		Device_Service $device_service,
		Observation_Service $observation_service
	) {
		$this->http_service        = $http_service;
		$this->device_service      = $device_service;
		$this->observation_service = $observation_service;
	}

	/**
	 * With connection.
	 *
	 * @param Connection $connection
	 * @return self
	 */
	public function with_connection( Connection $connection ): self {
		$this->current_connection = $connection;
		return $this;
	}

	/**
	 * Get Devices.
	 *
	 * @return array<Device>
	 */
	public function get_devices(): array {
		// If called without a connection, throw an exception.
		if ( ! $this->current_connection ) {
			throw new Connection_Exception( 'No connection set' );
		}

		return $this->device_service->get_all_devices( $this->current_connection );
	}

	/**
	 * Get current observations.
	 *
	 * @param Device $device
	 * @return Observation|null
	 */
	public function get_live_observations( Device $device ): ?Observation {
		$observation = $this->observation_service->get_live_observations( $device, $this->current_connection );
		return $observation;
	}

	/**
	 * Get observation history.
	 *
	 * @param Device        $device
	 * @param DateTime      $from   The start date.
	 * @param DateTime|null $to     The end date, or null for now.
	 * @return array<Observation>
	 */
	public function get_observation_history( Device $device, DateTime $from, ?DateTime $to = null ): array {
		return $this->observation_service->get_observation_history( $device, $from, $to, $this->current_connection );
	}
}
