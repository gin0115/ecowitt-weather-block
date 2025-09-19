<?php

/**
 * Device Exception.
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Exception;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Device Exception.
 *
 * Thrown when device-related errors occur.
 */
class Device_Exception extends Ecowitt_Exception {

	/**
	 * Create exception for device not found.
	 *
	 * @param string $device_id
	 * @return self
	 */
	public static function device_not_found( string $device_id ): self {
		return new self( "Device with ID '{$device_id}' not found." );
	}

	/**
	 * Create exception for invalid device.
	 *
	 * @param string $reason
	 * @return self
	 */
	public static function invalid_device( string $reason ): self {
		return new self( "Invalid device: {$reason}" );
	}

	/**
	 * Create exception for failed device operation.
	 *
	 * @param string $operation
	 * @param string $reason
	 * @return self
	 */
	public static function operation_failed( string $operation, string $reason ): self {
		return new self( "Device operation '{$operation}' failed: {$reason}" );
	}
}
