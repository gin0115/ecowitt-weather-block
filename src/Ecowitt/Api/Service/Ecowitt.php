<?php

/**
 * Primary Ecowitt Service.
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
}
